<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierDecoratorHtmlTestCase extends sfBasePhpunitTestCase
{
  protected $notifierBackup;
  
  protected function _start()
  {
    $this->notifierBackup = fpErrorNotifier::getInstance();
    
    $notifier = new fpErrorNotifier(new sfEventDispatcher());
    fpErrorNotifier::setInstance($notifier);
  }
  
  protected function _end()
  {
    fpErrorNotifier::setInstance($this->notifierBackup);
  }
  
  public function testRender()
  {
    $helper = new fpErrorNotifierMessageHelper();
    $notifier = $this->getStubStrict(
      'fpErrorNotifier', array('helper' => $helper), array(), '', false);
    fpErrorNotifier::setInstance($notifier);
    
    $message = new fpErrorNotifierMessage('foo title');
    $message->addSection('test one', array('bar' => 'foo'));

    $decorator = new fpErrorNotifierDecoratorHtml($message);
    
    $text = $decorator->render();

    $this->assertType('string', $text);
    
    $this->assertContains('Summary', $text);
    $this->assertContains('Environment', $text);
    $this->assertContains('Test One', $text);
  }
}