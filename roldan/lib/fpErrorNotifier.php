<?php

/**
 *
 * @package    fpErrorNotifier
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifier
{
  /**
   * 
   * @var fpErrorNotifier
   */
  protected static $instance;
  
  /**
   * 
   * @var sfEventDispatcher
   */
  protected $dispather;
  
  /**
   * 
   * @var fpBaseErrorNotifierDriver
   */
  protected $driver;
  
  /**
   * 
   * @var fpErrorNotifierHandler
   */
  protected $handler;
  
  /**
   * 
   * @var fpErrorNotifierMessageHelper
   */
  protected $helper;
  
  /**
   * 
   * @var fpBaseErrorNotifierMessage
   */
  protected $message;
  
  /**
   * 
   * @var fpBaseErrorNotifierDecorator
   */
  protected $decorator;
  
  /**
   * 
   * @param sfEventDispatcher $dispather
   * 
   * @return void
   */
  public function __construct(sfEventDispatcher $dispather)
  {
    $this->dispather = $dispather;
  }
  
  /**
   * 
   * @return sfEventDispatcher
   */
  public function dispather()
  {
    return $this->dispather;
  }
  
  /**
   * 
   * @param fpBaseErrorNotifierMessage
   * 
   * @return fpBaseErrorNotifierDecorator
   */
  public function decorator(fpBaseErrorNotifierMessage $message)
  {
    if (!$this->decorator) {
      $options = sfConfig::get('sf_notify_decorator');
      $class = $options['class'];
      $this->decorator = new $class($message);
    }

    return $this->decorator;
  }

  /**
   * 
   * @return fpBaseErrorNotifierDriver
   */
  public function driver()
  {
    if (!$this->driver) {
      $options = sfConfig::get('sf_notify_driver');
      $class = $options['class'];
      $this->driver = new $class($options['options']);
    }
    
    return $this->driver; 
  }
  
  /**
   * 
   * @param string $title
   * 
   * @return fpBaseErrorNotifierMessage
   */
  public function message($title)
  {
    if (!$this->message) {
      $options = sfConfig::get('sf_notify_message');
      $class = $options['class'];
      $this->message = new $class($title);
    }

    return clone $this->message;
  }
  
  /**
   * 
   * @param string $title
   * 
   * @return fpBaseErrorNotifierDecorator
   */
  public function decoratedMessage($title)
  {
    return $this->decorator($this->message($title));
  }
  
  /**
   * 
   * @return fpErrorNotifierHandler
   */
  public function handler()
  {
    if (!$this->handler) {
      $options = sfConfig::get('sf_notify_handler');
      $class = $options['class'];

      $this->handler = new $class($this->dispather(), $options['options']);
    }
    
    return $this->handler;
  }
  
  /**
   * 
   * @return fpErrorNotifierMessageHelper
   */
  public function helper()
  {
    if (!$this->helper) {
      $options = sfConfig::get('sf_notify_helper');
      $class = $options['class'];
      $this->helper = new $class;
    }

    return $this->helper;
  }
  
  /**
   * 
   * @return sfContext|fpErrorNotifierNullObject
   */
  public function context()
  {
    if (!class_exists('sfContext') || !sfContext::hasInstance()) {
      return new fpErrorNotifierNullObject();
    }
    
    return sfContext::getInstance();
  }
  
  /**
   * 
   * @return fpErrorNotifier
   */
  public static function getInstance()
  {
    return self::$instance;
  }
  
  /**
   * 
   * @param fpErrorNotifier
   * 
   * @return fpErrorNotifier
   */
  public static function setInstance(fpErrorNotifier $notifier)
  {
    return self::$instance = $notifier;
  }
}