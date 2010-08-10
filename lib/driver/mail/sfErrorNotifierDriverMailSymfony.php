<?php

/** 
 *
 * @package    sfErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierMailSymfony extends sfBaseErrorNotifierDriverMail
{
  /**
   * (non-PHPdoc)
   * @see plugins/sfErrorNotifier2Plugin/lib/driver/sfBaseErrorNotifierDriver#notify($message)
   */
  public function notify(sfBaseErrorNotifierMessage $message)
  {
    if (!sfContext::hasInstance()) return;
    
    $context = sfContext::getInstance();
    
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