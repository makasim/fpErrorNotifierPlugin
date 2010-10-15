<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierMessageHelperTestCase extends sfBasePhpunitTestCase
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
  
  public function testFormatException()
  {
    $exception = new Exception('Foo Message', 10);
    
    $helper = new fpErrorNotifierMessageHelper();
    
    $exceptionData = $helper->formatException($exception);

    $this->assertType('array', $exceptionData);
    
    $expectedKeys =  array('class', 'code', 'message', 'file', 'trace');
    $this->assertEquals($expectedKeys, array_keys($exceptionData));
    
    $this->assertEquals('Exception', $exceptionData['class']);
    $this->assertEquals(10, $exceptionData['code']);
    $this->assertEquals('Foo Message', $exceptionData['message']);
  }
  
  public function testFormatSummary()
  {
    sfConfig::set('sf_environment', 'foo_env');
    
    $stubContext = $this->getStubStrict('sfContext', array(
      'getModuleName' => 'FooModule',
      'getActionName' => 'BarAction'));
    
    $notifier = $this->getStubStrict(
      'fpErrorNotifier', array('context' => $stubContext), array(), '', false);
    fpErrorNotifier::setInstance($notifier);
    
    $helper = new fpErrorNotifierMessageHelper();
    
    $summaryData = $helper->formatSummary('FooTitle');
    
    $this->assertType('array', $summaryData);
    
    $expectedKeys =  array('subject', 'uri', 'environment', 'module', 'action', 'generated at');
    $this->assertEquals($expectedKeys, array_keys($summaryData));
    
    $this->assertEquals('FooTitle', $summaryData['subject']);
    $this->assertEquals('foo_env', $summaryData['environment']);
  }
  
  public function testFormatServer()
  {
    
    
    $helper = new fpErrorNotifierMessageHelper();
    
    $serverData = $helper->formatServer();
    
    $this->assertType('array', $serverData);
    
    $expectedKeys =  array('server', 'session');
    $this->assertEquals($expectedKeys, array_keys($serverData));
  }
  
  public function testFormatSubject()
  {
    sfConfig::set('sf_environment', 'foo_env');
    
    $helper = new fpErrorNotifierMessageHelper();
    
    $subject = $helper->formatSubject('FooSubject');
    
    $this->assertType('string', $subject);
    $this->assertEquals("Notification: foo_env - FooSubject", $subject);
  }
  
  /**
   * 
   * @dataProvider providerTitle
   */
  public function testFormatTitle($title, $expectedTitle)
  {
    $helper = new fpErrorNotifierMessageHelper();
    
    $actualTitle = $helper->formatTitle($title);
    
    $this->assertEquals($expectedTitle, $actualTitle);
  }
  
  public function providerTitle()
  {
    return array(
      array('foo', 'Foo'),
      array('foo bar', 'Foo Bar'),
      array('foo  bar', 'Foo Bar'),
      array('foo_bar-lol', 'Foo Bar Lol'));
  }
}