<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierTestCase extends sfBasePhpunitTestCase
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
  
  public function testDispather()
  {
    $dispather = new sfEventDispatcher();
    
    $notifier = new sfErrorNotifier($dispather);
    
    $this->assertSame($dispather, $notifier->dispather());
  }
 
  public function testDriver()
  {
    $expectedOptions = array('foo' => 'bar');
    $mock = $this->getMockForAbstractClass(
      'sfBaseErrorNotifierDriver', array($expectedOptions));

    sfConfig::set('sf_notify_driver', array(
      'class' => get_class($mock),
      'options' => $expectedOptions));
    
    $notifier = new sfErrorNotifier(new sfEventDispatcher());
    
    $driver = $notifier->driver();
    
    $this->assertType(get_class($mock), $driver);
    $this->assertAttributeEquals($expectedOptions, '_options', $driver);
  }
  
  public function testHelper()
  {
    $mock = $this->getMock('sfErrorNotifierMessageHelper');
    sfConfig::set('sf_notify_helper', array('class' => get_class($mock)));
    
    $notifier = new sfErrorNotifier(new sfEventDispatcher());
    
    $helper = $notifier->helper();
    
    $this->assertType(get_class($mock), $helper);
  }
  
  /**
   * 
   * @depends testHelper
   */
  public function testMessage()
  {
    $mock = $this->getMockForAbstractClass('sfBaseErrorNotifierMessage');
    sfConfig::set('sf_notify_message', array('class' => get_class($mock)));
    
    $notifier = new sfErrorNotifier(new sfEventDispatcher());
    
    $message = $notifier->message('title');
    
    $this->assertType(get_class($mock), $message);
    
//    $stub = $this->getStubStrict('sfBaseErrorNotifierMessageHelper', array(
//      'summarySection' => array('foo' => 'bar'), 
//      'formatSubject' => 'Foo Subject'));
//
//    sfConfig::set('sf_notify_helper', array('class' => get_class($mock)));
  }
  
  public function testHandler()
  {
    $expectedOptions = array(
      'error_reporting' => 5,
      'ignore_errors' => array('E_ALL' => array('info')),
      'ignore_exceptions' => array('FooException'),
      'log_ignored' => 1);
    $mock = $this->getMockForAbstractClass(
      'sfErrorNotifierHandler', array($expectedOptions));

    sfConfig::set('sf_notify_handler', array(
      'class' => get_class($mock),
      'options' => $expectedOptions));
    
    $notifier = new sfErrorNotifier(new sfEventDispatcher());
    
    $handler = $notifier->handler();
    
    $this->assertType(get_class($mock), $handler);
    $this->assertAttributeEquals($expectedOptions, 'options', $handler);
  }
  
  public function testDecorator()
  {
    $stubMessage = new sfErrorNotifierMessage('Foo Title');
    
    $mock = $this->getMockForAbstractClass(
      'sfBaseErrorNotifierDecorator', array(), '', false);
    sfConfig::set('sf_notify_decorator', array('class' => get_class($mock)));
    
    $notifier = new sfErrorNotifier(new sfEventDispatcher());
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
      'sfBaseErrorNotifierDecorator', array(), '', false);
    sfConfig::set('sf_notify_decorator', array('class' => get_class($decoratorMock)));
    
    $messageMock = $this->getMockForAbstractClass('sfBaseErrorNotifierMessage');
    sfConfig::set('sf_notify_message', array('class' => get_class($messageMock)));
    
    $notifier = new sfErrorNotifier(new sfEventDispatcher());
    
    $decoratedMessage = $notifier->decoratedMessage('Foo Title');
    
    $this->assertType(get_class($decoratorMock), $decoratedMessage);
    $this->assertAttributeType(get_class($messageMock), 'message', $decoratedMessage);
  }
}