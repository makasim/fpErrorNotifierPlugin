<?php

/** 
 *
 * @package    sfErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierDriverMailNative extends sfBaseErrorNotifierDriverMail
{
  /**
   * 
   * @param sfBaseErrorNotifierMessage $message
   */
  public function notify(sfBaseErrorNotifierMessage $message)
  {
    $headers = "From: {$this->getOption('from')}\r\n";
    $headers .= "Content-type: {$message->format()}\r\n";  
    
    @mail($this->getOption('to'), $message->subject(), (string) $message, $headers); 
  }
}