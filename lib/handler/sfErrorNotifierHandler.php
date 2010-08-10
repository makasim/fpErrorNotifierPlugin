<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage handler 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierHandler
{ 
  /**
   * 
   * @var array
   */
  protected $options = array(
    'error_reporting' => E_ALL);
  
  /**
   * 
   * @var string
   */
  protected $memoryReserv = '';
  
  /**
   * 
   * @param array $options
   * 
   * @return void
   */
  public function __construct(array $options = array())
  {
    $this->options = array_merge($this->options, $options);
  }
  
  /**
   * 
   * @return void
   */
  public function initialize()
  {
    $this->memoryReserv = str_repeat('x', 1024 * 500);
    
    set_error_handler(array($this, 'handleError'), (int) $this->options['error_reporting']);
    register_shutdown_function(array($this, 'handleFatalError'));
    set_exception_handler(array($this, 'handleException'));
    
    $dispather = $this->notifier()->dispather();
    $dispather->connect('application.throw_exception', array($this, 'handleEvent'));
    $dispather->connect('controller.page_not_found', array($this, 'handleEvent'));
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
    
    $this->notifier()->driver()->notify($message);
  }

	/**
	 * 
	 * @param string $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param string $errline
	 * 
	 * @return void
	 */
	public function handleError($errno, $errstr, $errfile, $errline)
	{ 	  
    $error = new ErrorException($errstr, 0, $errno, $errfile, $errline);
    
	  $this->handleException($error);
	}

  /**
   * 
   * @return void
   */
  public function handleFatalError()
  {    
    $error = error_get_last();
    if (!$error) return;    
    
    $this->freeMemory();

    @$this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
  }
	
  /**
   * 
   * @return void
   */
	protected function freeMemory()
	{
	  $this->memoryReserv = '';
	}
	
	/**
	 * 
	 * @return sfErrorNotifier
	 */
	protected function notifier()
	{
	  return sfErrorNotifier::getInstance();
	}
}