<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierDecoratorHtmlTestCase extends sfBasePhpunitTestCase
{
  protected $notifierBackup;
  
  protected function _start()
  {
    $this->notifierBackup = sfErrorNotifier::getInstance();
    
    $notifier = new sfErrorNotifier(new sfEventDispatcher());
    sfErrorNotifier::setInstance($notifier);
  }
  
  protected function _end()
  {
    sfErrorNotifier::setInstance($this->notifierBackup);
  }
  
  public function testRender()
  {
    $helper = new sfErrorNotifierMessageHelper();
    $notifier = $this->getStubStrict(
      'sfErrorNotifier', array('helper' => $helper), array(), '', false);
    sfErrorNotifier::setInstance($notifier);
    
    $message = new sfErrorNotifierMessage('foo title');
    $message->addSection('test one', array('bar' => 'foo'));

    $decorator = new sfErrorNotifierDecoratorHtml($message);
    
    $text = $decorator->render();

    $this->assertType('string', $text);
    
    $this->assertContains('Summary', $text);
    $this->assertContains('Environment', $text);
    $this->assertContains('Test One', $text);
  }
}