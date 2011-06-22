<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierTestCase extends sfBasePhpunitTestCase
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
  
  public function testDispather()
  {
    $dispather = new sfEventDispatcher();
    
    $notifier = new fpErrorNotifier($dispather);
    
    $this->assertSame($dispather, $notifier->dispather());
  }
 
  public function testDriver()
  {
    $expectedOptions = array('foo' => 'bar');
    $mock = $this->getMockForAbstractClass(
      'fpBaseErrorNotifierDriver', array($expectedOptions));

    sfConfig::set('sf_notify_driver', array(
      'class' => get_class($mock),
      'options' => $expectedOptions));
    
    $notifier = new fpErrorNotifier(new sfEventDispatcher());
    
    $driver = $notifier->driver();
    
    $this->assertType(get_class($mock), $driver);
    $this->assertAttributeEquals($expectedOptions, '_options', $driver);
  }
  
  public function testHelper()
  {
    $mock = $this->getMock('fpErrorNotifierMessageHelper');
    sfConfig::set('sf_notify_helper', array('class' => get_class($mock)));
    
    $notifier = new fpErrorNotifier(new sfEventDispatcher());
    
    $helper = $notifier->helper();
    
    $this->assertType(get_class($mock), $helper);
  }
  
  /**
   * 
   * @depends testHelper
   */
  public function testMessage()
  {
    $mock = $this->getMockForAbstractClass('fpBaseErrorNotifierMessage');
    sfConfig::set('sf_notify_message', array('class' => get_class($mock)));
    
    $notifier = new fpErrorNotifier(new sfEventDispatcher());
    
    $message = $notifier->message('title');
    
    $this->assertType(get_class($mock), $message);
    
//    $stub = $this->getStubStrict('fpBaseErrorNotifierMessageHelper', array(
//      'summarySection' => array('foo' => 'bar'), 
//      'formatSubject' => 'Foo Subject'));
//
//    sfConfig::set('sf_notify_helper', array('class' => get_class($mock)));
  }
  
  public function testHandler()
  {
    $expectedOptions = array(
      'ignore_errors' => array('E_ALL' => array('info')),
      'ignore_exceptions' => array('FooException'),
      'log_ignored' => 1);
    $mock = $this->getMockForAbstractClass(
      'fpErrorNotifierHandler', array(new sfEventDispatcher, $expectedOptions));

    sfConfig::set('sf_notify_handler', array(
      'class' => get_class($mock),
      'options' => $expectedOptions));
    
    $notifier = new fpErrorNotifier(new sfEventDispatcher());
    
    $handler = $notifier->handler();
    
    $this->assertType(get_class($mock), $handler);
    $this->assertAttributeEquals($expectedOptions, 'options', $handler);
  }
  
  public function testDecorator()
  {
    $stubMessage = new fpErrorNotifierMessage('Foo Title');
    
    $mock = $this->getMockForAbstractClass(
      'fpBaseErrorNotifierDecorator', array(), '', false);
    sfConfig::set('sf_notify_decorator', array('class' => get_class($mock)));
    
    $notifier = new fpErrorNotifier(new sfEventDispatcher());
    $decorator = $notifier->decorator($stubMessage);
    
    $this->assertType(get_class($mock), $decorator);
    $this->assertAttributeEquals($stubMessage, 'message', $decorator);
  }
  
  /**
   * 
   * @depends testDecorator
   * @depends testMessage
   */
  public function testDecoratedMessage()
  {
    $decoratorMock = $this->getMockForAbstractClass(
      'fpBaseErrorNotifierDecorator', array(), '', false);
    sfConfig::set('sf_notify_decorator', array('class' => get_class($decoratorMock)));
    
    $messageMock = $this->getMockForAbstractClass('fpBaseErrorNotifierMessage');
    sfConfig::set('sf_notify_message', array('class' => get_class($messageMock)));
    
    $notifier = new fpErrorNotifier(new sfEventDispatcher());
    
    $decoratedMessage = $notifier->decoratedMessage('Foo Title');
    
    $this->assertType(get_class($decoratorMock), $decoratedMessage);
    $this->assertAttributeType(get_class($messageMock), 'message', $decoratedMessage);
  }
}