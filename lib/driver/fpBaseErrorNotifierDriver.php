<?php

/** 
 *
 * @package    fpErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
abstract class fpBaseErrorNotifierDriver
{ 
  /**
   * 
   * @var array
   */
  protected $_options = array();
  
  /**
   * 
   * @param array $options
   */
  public function __construct(array $options = array())
  {
    $this->_options = $options;
  }
  
  /**
   * 
   * @param fpBaseErrorNotifierMessage $message
   * 
   * @return void
   */
  abstract public function notify(fpBaseErrorNotifierMessage $message);
  
  /**
   * 
   * @param string $name
   */
  public function getOption($name)
  {
    return isset($this->_options[$name]) ? $this->_options[$name] : null;
  }
}