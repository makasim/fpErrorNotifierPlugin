<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierHandlerIgnoreTestCase extends sfBasePhpunitTestCase
{
  protected $notifierBackup;
  
  protected function _start()
  {
    $this->notifierBackup = sfErrorNotifier::getInstance();
    
    $stubHelper = $this->getStub('sfErrorNotifierMessageHelper', array(
      'formatSumary' => array('section' => 'desciption'),
      'formatException' => array('foo' => 'bar')));
    
    $stubMessage = new sfErrorNotifierMessage('foo title');
    $stubMessage = new sfErrorNotifierDecoratorText($stubMessage);
    
    $mockDriver = $this->getMockForAbstractClass('sfBaseErrorNotifierDriver');
   
    $mockLogger = $this->getMock('sfLogger', array('info', 'doLog'), array(), '', false);
    $stubContext = $this->getStub('sfContext', array('getLogger' => $mockLogger));   
    
    $notifier = $this->getStub('sfErrorNotifier', array(
      'decoratedMessage' => $stubMessage,
      'helper' => $stubHelper,
      'driver' => $mockDriver,
      'dispather' => new sfEventDispatcher(),
      'context' => $stubContext), array(), '', false);
    
    sfErrorNotifier::setInstance($notifier);
  }
  
  protected function _end()
  {
    sfErrorNotifier::setInstance($this->notifierBackup);
  }
  
  public function testHandleException()
  { 
    $mockDriver = sfErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->once())
      ->method('notify');
      
    $mockLogger = sfErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new sfErrorNotifierHandlerIgnore(array());
    $handler->handleException(new Exception('an exception'));
  }
  
  public function testHandleExceptionIgnoreLoggingEnabled()
  { 
    $mockDriver = sfErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->never())
      ->method('notify');
      
    $mockLogger = sfErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->once())
      ->method('info');
    
    $handler = new sfErrorNotifierHandlerIgnore(array(
      'ignore_exceptions' => array('Exception'),
      'log_ignored' => true));
    
    $handler->handleException(new Exception('an exception'));
  }
  
  public function testHandleExceptionIgnoreLoggingDisabled()
  { 
    $mockDriver = sfErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->never())
      ->method('notify');
      
    $mockLogger = sfErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new sfErrorNotifierHandlerIgnore(array(
      'ignore_exceptions' => array('Exception'),
      'log_ignored' => false));
    
    $handler->handleException(new Exception('an exception'));
  }
  
  public function testHandleError()
  { 
    $mockDriver = sfErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->once())
      ->method('notify');
      
    $mockLogger = sfErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new sfErrorNotifierHandlerIgnore(array());
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
  
  public function testHandleErrorIgnoreSetToFalse()
  { 
    $mockDriver = sfErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->once())
      ->method('notify');
      
    $mockLogger = sfErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new sfErrorNotifierHandlerIgnore(array(
      'ignore_errors' => array(E_WARNING => false)));
    
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
  
  public function testHandleErrorIgnoreLoggingEnabled()
  {   
    $mockDriver = sfErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->never())
      ->method('notify');
      
    $mockLogger = sfErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->once())
      ->method('info');
    
    $handler = new sfErrorNotifierHandlerIgnore(array(
      'ignore_errors' => array(E_WARNING => true),
      'log_ignored' => true));
    
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
  
  public function testHandleErrorIgnoreLoggingDisabled()
  { 
    $mockDriver = sfErrorNotifier::getInstance()->driver();
    $mockDriver
      ->expects($this->never())
      ->method('notify');
      
    $mockLogger = sfErrorNotifier::getInstance()->context()->getLogger();
    $mockLogger
      ->expects($this->never())
      ->method('info');
    
    $handler = new sfErrorNotifierHandlerIgnore(array(
      'ignore_errors' => array(E_WARNING => true),
      'log_ignored' => false));
    $handler->handleError(E_WARNING, 'an error', 'foo.php', 200);
  }
}