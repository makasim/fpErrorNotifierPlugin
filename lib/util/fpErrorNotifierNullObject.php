<?php

/**
 * 
 * Universal implementaion of Null object pattern.
 *
 * @package    fpErrorNotifier
 * @subpackage util 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierNullObject implements IteratorAggregate
{
  public function __get($name)
  {
    return $this;
  }
  
  public function __set($name, $value) 
  { 
  }
  
  /**
   *
   * @return NullObject
   */
  public function __call($method, $args)
  {
    return $this;
  }

  /**
   *
   * @return string
   */
  public function __toString()
  {
    return 'undefined';
  }

  /**
   *
   * @return array
   */
  public function toArray()
  {
    return array();
  }
  
  public function getIterator()
  {
    return new EmptyIterator();   
  }
}