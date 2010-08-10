<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierHandlerTestCase extends sfBasePhpunitTestCase
{
  protected $notifierBackup;
  
  protected function _start()
  {
    $this->notifierBackup = sfErrorNotifier::getInstance();
    
    $stubHelper = $this->getStubStrict('sfErrorNotifierMessageHelper', array(
      'formatSumary' => array('section' => 'desciption'),
      'formatException' => array('foo' => 'bar')));
    
    $stubMessage = new sfErrorNotifierMessage('foo title');
    $stubMessage = new sfErrorNotifierDecoratorText($stubMessage);
    
    $mockDriver = $this->getMockForAbstractClass('sfBaseErrorNotifierDriver');   
    
    $notifier = $this->getStub('sfErrorNotifier', array(
      'decoratedMessage' => $stubMessage,
      'helper' => $stubHelper,
      'driver' => $mockDriver,
      'dispather' => new sfEventDispatcher()), array(), '', false);
    
    sfErrorNotifier::setInstance($notifier);
  }
  
  protected function _end()
  {
    sfErrorNotifier::setInstance($this->notifierBackup);
  }
  
  public function testHandleException()
  { 
    $notifier = sfErrorNotifier::getInstance();
    $notifier->driver()
      ->expects($this->once())
      ->method('notify')
      ->with($this->equalTo($notifier->decoratedMessage('foo')));
    
    $handler = new sfErrorNotifierHandler();
    $handler->handleException(new Exception('an exception'));
  }
  
  public function testHandleError()
  {    
    $notifier = sfErrorNotifier::getInstance();
    $notifier->driver()
      ->expects($this->once())
      ->method('notify')
      ->with($this->equalTo($notifier->decoratedMessage('foo')));
    
    $handler = new sfErrorNotifierHandler(array());
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
  
  public function testHandleEventExceptionThrown()
  {    
    $notifier = sfErrorNotifier::getInstance();
    $notifier->driver()
      ->expects($this->once())
      ->method('notify')
      ->with($this->equalTo($notifier->decoratedMessage('foo')));
    
    $handler = new sfErrorNotifierHandler(array());
    $notifier->dispather()
      ->connect('application.throw_exception', array($handler, 'handleEvent'));
  
    $notifier->dispather()->notifyUntil(
      new sfEvent(new Exception('an exception'), 'application.throw_exception'));
  }
  
  public function testHandleEventPageNotFound()
  {    
    $notifier = sfErrorNotifier::getInstance();
    $notifier->driver()
      ->expects($this->once())
      ->method('notify')
      ->with($this->equalTo($notifier->decoratedMessage('foo')));
    
    $handler = new sfErrorNotifierHandler(array());
    $notifier->dispather()
      ->connect('controller.page_not_found', array($handler, 'handleEvent'));
  
    $notifier->dispather()->notifyUntil(
      new sfEvent(new Exception('an exception'), 'controller.page_not_found'));
  }
}