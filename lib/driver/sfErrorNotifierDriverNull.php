<?php

/** 
 *
 * @package    sfErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierDriverNull extends sfBaseErrorNotifierDriver
{ 
  /**
   * 
   * @param sfBaseErrorNotifierMessage $message
   * 
   * @return void
   */ 
  public function notify(sfBaseErrorNotifierMessage $message)
  {
    // it must do nothing.
  }
}