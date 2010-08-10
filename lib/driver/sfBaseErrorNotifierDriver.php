<?php

/** 
 *
 * @package    sfErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
abstract class sfBaseErrorNotifierDriver
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
   * @param sfBaseErrorNotifierMessage $message
   * 
   * @return void
   */
  abstract public function notify(sfBaseErrorNotifierMessage $message);
  
  /**
   * 
   * @param string $name
   */
  public function getOption($name)
  {
    return isset($this->_options[$name]) ? $this->_options[$name] : null;
  }
}