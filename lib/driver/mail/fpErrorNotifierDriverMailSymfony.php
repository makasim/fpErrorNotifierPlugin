<?php

require_once 'fpBaseErrorNotifierDriverMail.php';

/** 
 *
 * @package    fpErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierDriverMailSymfony extends fpBaseErrorNotifierDriverMail
{
  /**
   * (non-PHPdoc)
   * @see plugins/fpErrorNotifierPlugin/lib/driver/fpBaseErrorNotifierDriver#notify($message)
   */
  public function notify(fpBaseErrorNotifierMessage $message)
  {
    $swiftMessage = new Swift_Message();
    $swiftMessage
      ->setTo($this->getOption('to'))
      ->setFrom($this->getOption('from'))
      ->setBody((string) $message)
      ->setContentType($message->format())
      ->setSubject($message->subject());

    @fpErrorNotifier::getInstance()->context()->getMailer()->send($swiftMessage);
  }
}