<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage handler 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierHandler
{ 
  /**
   * 
   * @var array
   */
  protected $options = array();
  
  /**
   * 
   * @var string
   */
  protected $memoryReserv = '';
  
  /**
   * @var sfEventDispatcher
   */
  protected $dispatcher;
  
  /**
   * 
   * @var bool
   */
  protected $isInit = false;
  
  /**
   * 
   * @param array $options
   * 
   * @return void
   */
  public function __construct(sfEventDispatcher $dispatcher, array $options = array())
  {
    $this->dispatcher = $dispatcher;
    $this->options = array_merge($this->options, $options);
  }
  
  /**
   * 
   * @return void
   */
  public function initialize()
  {
    if ($this->isInit) return; 
    $configs = sfConfig::get('sf_notify_driver');
    
    $this->memoryReserv = str_repeat('x', 1024 * 500);
    
    // Register error handler it will process most of erros but not all
    set_error_handler(array($this, 'handleError'));
    // Register shutdown handler it will process other not proced errors 
    register_shutdown_function(array($this, 'handleFatalError'));
    
    set_exception_handler(array($this, 'handleException'));
        
    $dispather = $this->notifier()->dispather();
    $dispather->connect('application.throw_exception', array($this, 'handleEvent'));
    
    $this->isInit = true;
  }
  
  /**
   * 
   * @param sfEvent $event
   * 
   * @return void
   */
  public function handleEvent(sfEvent $event)
  {
    return $this->handleException($event->getSubject());
  }
  
  /**
   * 
   * @param Exception $e
   * 
   * @return void
   */
  public function handleException(Exception $e)
  {
    $message = $this->notifier()->decoratedMessage($e->getMessage());
    $message->addSection('Exception', $this->notifier()->helper()->formatException($e));
    $message->addSection('Server', $this->notifier()->helper()->formatServer());
    
    $this->dispatcher->notify(new sfEvent($message, 'notify.exception'));
    
    $this->notifier()->driver()->notify($message);
  }
  

  /**
   * 
   * @param string $errno
   * @param string $errstr
   * @param string $errfile
   * @param string $errline
   * 
   * @return ErrorException
   */
  public function handleError($errno, $errstr, $errfile, $errline)
  {
    $this->handleException(new ErrorException($errstr, 0, $errno, $errfile, $errline));
    
    return false;
  }

  /**
   * 
   * @return void
   */
  public function handleFatalError()
  {
    $error = error_get_last();

    $skipHandling = 
      !$error || 
      !isset($error['type']) || 
      !in_array($error['type'], fpErrorNotifierErrorCode::getFatals());
    if ($skipHandling) return;

    $this->freeMemory();
    
    @$this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
    
//    $sfE = new sfException();
//    $sfE->setWrappedException($error);
//    $sfE->printStackTrace();
  }
  
  /**
   * 
   * @return void
   */
  protected function freeMemory()
  {
    unset($this->memoryReserv);
  }
  
  /**
   * 
   * @return fpErrorNotifier
   */
  protected function notifier()
  {
    return fpErrorNotifier::getInstance();
  }
}