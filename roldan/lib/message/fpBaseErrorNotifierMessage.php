<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage message 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
abstract class fpBaseErrorNotifierMessage implements IteratorAggregate
{  
  /**
   * 
   * @var string
   */
  protected $_subject;
  
  /**
   * 
   * @var array
   */
  protected $_data = array();
  
  /**
   * 
   * @var ArrayIterator
   */
  protected $_dataIterator;
  
  /**
   * 
   * @param string|void $title
   */
  public function __construct($title = 'Internal error')
  {   
    $this->addSection('Summary', $this->notifier()->helper()->formatSummary($title));
    
    $this->_subject = $this->notifier()->helper()->formatSubject($title);
  }
  
  /**
   * 
   * @return string
   */
  public function render()
  {
    return $this->subject();
  }
  
    /**
   * 
   * @return string
   */
  public function format()
  {
    return 'text/plain';    
  }
  
  /**
   * 
   * @param string $name
   * @param array $data
   * 
   * @return fpErrorNotifierMessage
   */
  public function addSection($name, array $data)
  {
    $this->_data[$name] = $data;
    
    return $this;
  }
  
  /**
   * 
   * @param string $name
   * 
   * @return fpErrorNotifierMessage
   */
  public function removeSection($name)
  {
    unset($this->_data[$name]);
    
    return $this;
  }
  
  /**
   * 
   * @return string 
   */
  public function subject()
  {
    return $this->_subject;
  }
  
  /**
   * 
   * @return ArrayIterator
   */
  public function getIterator()
  {
    return new ArrayIterator($this->_data);
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