<?php

require_once 'fpBaseErrorNotifierDriver.php';

/** 
 *
 * @package    fpErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierDriverFile extends fpBaseErrorNotifierDriver
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
   * @param fpBaseErrorNotifierMessage $message
   */
  public function notify(fpBaseErrorNotifierMessage $message)
  {    
    $path = $this->getOption('path');
    file_exists($path) && unlink($path);

    $data = "
      Content-type: {$message->format()}
      Subject: {$message->subject()}
      
      {$message}";
    
    file_put_contents($path, $data); 
  }
}