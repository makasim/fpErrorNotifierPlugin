<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage decorator 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierDecoratorText extends sfBaseErrorNotifierDecorator
{
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
   * @param string $title
   * 
   * @return string
   */
  protected function _renderTitle($title)
  {
    return $this->notifier()->helper()->formatTitle($title) . "\n";
  }
  
  /**
   * 
   * @param array $data
   * 
   * @return string
   */
  protected function _renderSection(array $data)
  { 
    $body = '';    
    foreach ($data as $name => $value) {
      $body .= "\t{$this->notifier()->helper()->formatTitle($name)}: {$value}\n";
    }
    
    return $body;
  }
}