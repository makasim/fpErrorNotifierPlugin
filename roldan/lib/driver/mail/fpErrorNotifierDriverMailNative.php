<?php

require_once 'fpBaseErrorNotifierDriverMail.php';

/** 
 *
 * @package    fpErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierDriverMailNative extends fpBaseErrorNotifierDriverMail
{
  /**
   * 
   * @param fpBaseErrorNotifierMessage $message
   */
  public function notify(fpBaseErrorNotifierMessage $message)
  {
    $headers = "From: {$this->getOption('from')}\r\n";
    $headers .= "Content-type: {$message->format()}\r\n";  
    
    @mail($this->getOption('to'), $message->subject(), (string) $message, $headers); 
  }
}