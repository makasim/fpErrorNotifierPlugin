<?php

require_once 'fpBaseErrorNotifierDriverMail.php';

/** 
 *
 * @package    fpErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierMailSymfony extends fpBaseErrorNotifierDriverMail
{
  /**
   * (non-PHPdoc)
   * @see plugins/fpErrorNotifierPlugin/lib/driver/fpBaseErrorNotifierDriver#notify($message)
   */
  public function notify(fpBaseErrorNotifierMessage $message)
  {
    if ($context = fpErrorNotifier::getInstance()->context()) return;
    
    $swiftMessage = new Swift_Message();
    $swiftMessage
      ->setTo($this->getOption('to'))
      ->setFrom($this->getOption('from'))
      ->setBody((string) $message)
      ->setFormat($message->format())
      ->setSubject($message->subject());

    @$context->getMailer()->send($swiftMessage);
  }
}