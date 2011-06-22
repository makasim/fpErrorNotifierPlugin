<?php

require_once 'fpBaseErrorNotifierDriver.php';

/** 
 *
 * @package    fpErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierDriverNull extends fpBaseErrorNotifierDriver
{ 
  /**
   * 
   * @param fpBaseErrorNotifierMessage $message
   * 
   * @return void
   */ 
  public function notify(fpBaseErrorNotifierMessage $message)
  {
    // it must do nothing.
  }
}