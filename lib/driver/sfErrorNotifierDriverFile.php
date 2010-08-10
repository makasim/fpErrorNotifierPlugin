<?php

/** 
 *
 * @package    sfErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierDriverFile extends sfBaseErrorNotifierDriver
{
  /**
   * 
   * @param array $options
   */
  public function __construct(array $options = array())
  {
    $options['path'] = isset($options['path']) ? 
      $options['path'] :
      sfConfig::get('sf_data_dir').'/last-error.html';

    parent::__construct($options);
  }
  
  /**
   * 
   * @param sfBaseErrorNotifierMessage $message
   */
  public function notify(sfBaseErrorNotifierMessage $message)
  {    
    $path = $this->getOption('path');
    file_exists($path) && unlink($path);
    
    $data = "
      Content-type: {$message->format()}
      
      {$message}";
    
    file_put_contents($path, $data); 
  }
}