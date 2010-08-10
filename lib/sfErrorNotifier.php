<?php

/**
 *
 * @package    sfErrorNotifier
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifier
{
  /**
   * 
   * @var sfErrorNotifier
   */
  protected static $instance;
  
  /**
   * 
   * @var sfEventDispatcher
   */
  protected $dispather;
  
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
   * @param sfBaseErrorNotifierMessage
   * 
   * @return sfBaseErrorNotifierDecorator
   */
  public function decorator(sfBaseErrorNotifierMessage $message)
  {
    $options = sfConfig::get('sf_notify_decorator');
    $class = $options['class'];
    
    return new $class($message);  
  }

  /**
   * 
   * @return sfBaseErrorNotifierDriver
   */
  public function driver()
  {
    $options = sfConfig::get('sf_notify_driver');
    $class = $options['class'];
    
    return new $class($options['options']); 
  }
  
  /**
   * 
   * @param string $title
   * 
   * @return sfBaseErrorNotifierMessage
   */
  public function message($title)
  {
    $options = sfConfig::get('sf_notify_message');
    $class = $options['class'];

    return new $class($title); 
  }
  
  /**
   * 
   * @param string $title
   * 
   * @return sfBaseErrorNotifierDecorator
   */
  public function decoratedMessage($title)
  {
    return $this->decorator($this->message($title));
  }
  
  /**
   * 
   * @return sfErrorNotifierHandler
   */
  public function handler()
  {
    $options = sfConfig::get('sf_notify_handler');
    $class = $options['class'];

    return new $class($options['options']); 
  }
  
  /**
   * 
   * @return sfErrorNotifierMessageHelper
   */
  public function helper()
  {
    $options = sfConfig::get('sf_notify_helper');
    $class = $options['class'];
    
    return new $class;
  }
  
  /**
   * 
   * @return sfContext|sfErrorNotifierNullObject
   */
  public function context()
  {
    return sfContext::hasInstance() ? 
      sfContext::getInstance() : 
      new sfErrorNotifierNullObject();
  }
  
  /**
   * 
   * @return sfErrorNotifier
   */
  public static function getInstance()
  {
    return self::$instance;
  }
  
  /**
   * 
   * @param sfErrorNotifier
   * 
   * @return sfErrorNotifier
   */
  public static function setInstance(sfErrorNotifier $notifier)
  {
    return self::$instance = $notifier;
  }
}