<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierHandlerIgnoreTestCase extends sfBasePhpunitTestCase
{
  protected $notifierBackup;
  
  protected function _start()
  {
    $this->notifierBackup = fpErrorNotifier::getInstance();
    
    $stubHelper = $this->getStub('fpErrorNotifierMessageHelper', array(
      'formatSumary' => array('section' => 'desciption'),
      'formatException' => array('foo' => 'bar')));
    
    $stubMessage = new fpErrorNotifierMessage('foo title');
    $stubMessage = new fpErrorNotifierDecoratorText($stubMessage);
    
    $mockDriver = $this->getMockForAbstractClass('fpBaseErrorNotifierDriver');
    
    $stubRequest = $this->getStub(
      'sfWebRequest', array('getUri' => 'www.example.com'), array(), '', false);
   
    $mockLogger = $this->getMock('sfLogger', array('info', 'doLog'), array(), '', false);
    $stubContext = $this->getStub('sfContext', array(
      'getLogger' => $mockLogger,
      'getRequest' => $stubRequest,
      'getModuleName' => 'fooModule',
      'getActionName' => 'fooAction'));   
    
    $notifier = $this->getStub('fpErrorNotifier', array(
      'decoratedMessage' => $stubMessage,
      'helper' => $stubHelper,
      'driver' => $mockDriver,
      'dispather' => new sfEventDispatcher(),
      'context' => $stubContext), array(), '', false);
    
    fpErrorNotifier::setInstance($notifier);
  }
  
  protected function _end()
  {
    fpErrorNotifier::setInstance($this->notifierBackup);
  }
  
  public function testHandleException()
  { 
    $mockDriver = fpErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->once())
      ->method('notify');
      
    $mockLogger = fpErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new fpErrorNotifierHandlerIgnore(new sfEventDispatcher, array());
    $handler->handleException(new Exception('an exception'));
  }
  
  public function testHandleExceptionIgnoreLoggingEnabled()
  { 
    $mockDriver = fpErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->never())
      ->method('notify');
      
    $mockLogger = fpErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->once())
      ->method('info');
    
    $handler = new fpErrorNotifierHandlerIgnore(new sfEventDispatcher(), array(
      'ignore_exceptions' => array('Exception'),
      'log_ignored' => true));
    
    $handler->handleException(new Exception('an exception'));
  }
  
  public function testHandleExceptionIgnoreLoggingDisabled()
  { 
    $mockDriver = fpErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->never())
      ->method('notify');
      
    $mockLogger = fpErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new fpErrorNotifierHandlerIgnore(new sfEventDispatcher, array(
      'ignore_exceptions' => array('Exception'),
      'log_ignored' => false));
    
    $handler->handleException(new Exception('an exception'));
  }
  
  public function testHandleError()
  { 
    $mockDriver = fpErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->once())
      ->method('notify');
      
    $mockLogger = fpErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new fpErrorNotifierHandlerIgnore(new sfEventDispatcher, array());
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
  
  public function testHandleErrorIgnoreSetToFalse()
  { 
    $mockDriver = fpErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->once())
      ->method('notify');
      
    $mockLogger = fpErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new fpErrorNotifierHandlerIgnore(new sfEventDispatcher, array(
      'ignore_errors' => array()));
    
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
  
  public function testHandleErrorIgnoreLoggingEnabled()
  {   
    $mockDriver = fpErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->never())
      ->method('notify');
      
    $mockLogger = fpErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->once())
      ->method('info');
    
    $handler = new fpErrorNotifierHandlerIgnore(new sfEventDispatcher, array(
      'ignore_errors' => array(E_WARNING),
      'log_ignored' => true));
    
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
  
  public function testHandleErrorIgnoreLoggingDisabled()
  { 
    $mockDriver = fpErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->never())
      ->method('notify');
      
    $mockLogger = fpErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new fpErrorNotifierHandlerIgnore(new sfEventDispatcher, array(
      'ignore_errors' => array(E_WARNING => true),
      'log_ignored' => false));
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
}