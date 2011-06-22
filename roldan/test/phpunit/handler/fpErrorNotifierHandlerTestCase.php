<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierHandlerTestCase extends sfBasePhpunitTestCase
{
  protected $notifierBackup;
  
  protected function _start()
  {
    $this->notifierBackup = fpErrorNotifier::getInstance();
    
    $stubHelper = $this->getStubStrict('fpErrorNotifierMessageHelper', array(
      'formatSumary' => array('section' => 'desciption'),
      'formatException' => array('foo' => 'bar')));
    
    $stubMessage = new fpErrorNotifierMessage('foo title');
    $stubMessage = new fpErrorNotifierDecoratorText($stubMessage);
    
    $mockDriver = $this->getMockForAbstractClass('fpBaseErrorNotifierDriver');   
    
    $notifier = $this->getStub('fpErrorNotifier', array(
      'decoratedMessage' => $stubMessage,
      'helper' => $stubHelper,
      'driver' => $mockDriver,
      'dispather' => new sfEventDispatcher()), array(), '', false);
    
    fpErrorNotifier::setInstance($notifier);
  }
  
  protected function _end()
  {
    fpErrorNotifier::setInstance($this->notifierBackup);
  }
  
  public function testHandleException()
  { 
    $notifier = fpErrorNotifier::getInstance();
    $notifier->driver()
      ->expects($this->once())
      ->method('notify')
      ->with($this->equalTo($notifier->decoratedMessage('foo')));
    
    $handler = new fpErrorNotifierHandler(new sfEventDispatcher);
    $handler->handleException(new Exception('an exception'));
  }
  
  public function testHandleError()
  {    
    $notifier = fpErrorNotifier::getInstance();
    $notifier->driver()
      ->expects($this->once())
      ->method('notify')
      ->with($this->equalTo($notifier->decoratedMessage('foo')));
    
    $handler = new fpErrorNotifierHandler(new sfEventDispatcher, array());
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
  
  public function testHandleEventExceptionThrown()
  {    
    $notifier = fpErrorNotifier::getInstance();
    $notifier->driver()
      ->expects($this->once())
      ->method('notify')
      ->with($this->equalTo($notifier->decoratedMessage('foo')));
    
    $handler = new fpErrorNotifierHandler(new sfEventDispatcher, array());
    $notifier->dispather()
      ->connect('application.throw_exception', array($handler, 'handleEvent'));
  
    $notifier->dispather()->notifyUntil(
      new sfEvent(new Exception('an exception'), 'application.throw_exception'));
  }
  
  public function testHandleEventPageNotFound()
  {    
    $notifier = fpErrorNotifier::getInstance();
    $notifier->driver()
      ->expects($this->once())
      ->method('notify')
      ->with($this->equalTo($notifier->decoratedMessage('foo')));
    
    $handler = new fpErrorNotifierHandler(new sfEventDispatcher, array());
    $notifier->dispather()
      ->connect('controller.page_not_found', array($handler, 'handleEvent'));
  
    $notifier->dispather()->notifyUntil(
      new sfEvent(new Exception('an exception'), 'controller.page_not_found'));
  }
}