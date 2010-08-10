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
   * @param fpBaseErrorNotifierMessage
   * 
   * @return fpBaseErrorNotifierDecorator
   */
  public function decorator(fpBaseErrorNotifierMessage $message)
  {
    $options = sfConfig::get('sf_notify_decorator');
    $class = $options['class'];
    
    return new $class($message);  
  }

  /**
   * 
   * @return fpBaseErrorNotifierDriver
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
   * @return fpBaseErrorNotifierMessage
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
    $options = sfConfig::get('sf_notify_handler');
    $class = $options['class'];

    return new $class($options['options']); 
  }
  
  /**
   * 
   * @return fpErrorNotifierMessageHelper
   */
  public function helper()
  {
    $options = sfConfig::get('sf_notify_helper');
    $class = $options['class'];
    
    return new $class;
  }
  
  /**
   * 
   * @return sfContext|fpErrorNotifierNullObject
   */
  public function context()
  {
    return sfContext::hasInstance() ? 
      sfContext::getInstance() : 
      new fpErrorNotifierNullObject();
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