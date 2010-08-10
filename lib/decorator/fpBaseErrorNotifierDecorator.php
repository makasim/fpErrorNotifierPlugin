<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage decorator 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
abstract class fpBaseErrorNotifierDecorator extends fpBaseErrorNotifierMessage
{
  /**
   * 
   * @var fpBaseErrorNotifierMessage
   */
  protected $message;
  
  /**
   * 
   * @param fpBaseErrorNotifierMessage $message
   */
  public function __construct(fpBaseErrorNotifierMessage $message)
  {
    $this->message = $message;
  }
  
  /**
   * 
   * @return string
   */
  public function render()
  {
    $body = '';
    foreach ($this->message as $title => $data) {
      is_array($data) || $data = array($data); 
      
      $body .= $this->_renderTitle($title);
      $body .= $this->_renderSection($data);
    }
    
    return $body;
  }
  
  /**
   * 
   * @param string $title
   */
  abstract protected function _renderTitle($title);
  
  /**
   * 
   * @param array $data
   */
  abstract protected function _renderSection(array $data);
  
  public function __call($name, $args) 
  {
    return call_user_func_array(array($this->message, $name), $args);
  }
  
  public function __set($name, $value)
  {
    $this->message->$name = $value;
  }
  
  public function __get($name)
  {
    return $this->message->$name;
  }
  
  /**
   * 
   * @return string
   */
  public function __toString()
  {
    return $this->render(); 
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
    return $this->message->addSection($name, $data);
  }
  
  /**
   * 
   * @param string $name
   * 
   * @return fpErrorNotifierMessage
   */
  public function removeSection($name)
  {
    return $this->message->removeSection($name);
  }
  
  /**
   * 
   * @return string 
   */
  public function subject()
  {
    return $this->message->subject();
  }
  
  /**
   * 
   * @return ArrayIterator
   */
  public function getIterator()
  {
    return $this->message->getIterator();
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