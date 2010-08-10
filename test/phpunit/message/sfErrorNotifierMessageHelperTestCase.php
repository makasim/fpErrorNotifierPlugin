<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierMessageHelperTestCase extends sfBasePhpunitTestCase
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
  
  public function testFormatException()
  {
    $exception = new Exception('Foo Message', 10);
    
    $helper = new sfErrorNotifierMessageHelper();
    
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
    
    $helper = new sfErrorNotifierMessageHelper();
    
    $summaryData = $helper->formatSummary('FooTitle');
    
    $this->assertType('array', $summaryData);
    
    $expectedKeys =  array('subject', 'environment', 'generated at');
    $this->assertEquals($expectedKeys, array_keys($summaryData));
    
    $this->assertEquals('FooTitle', $summaryData['subject']);
    $this->assertEquals('foo_env', $summaryData['environment']);
  }
  
  public function testFormatServer()
  {
    $stubRequest = $this->getStubStrict(
      'sfWebRequest', array('getUri' => 'www.notify.com'), array(), '', false);
    $stubContext = $this->getStubStrict('sfContext', array(
      'getModuleName' => 'FooModule',
      'getActionName' => 'BarAction',
      'getRequest' => $stubRequest));
    
    $notifier = $this->getStubStrict(
      'sfErrorNotifier', array('context' => $stubContext), array(), '', false);
    sfErrorNotifier::setInstance($notifier);
    
    $helper = new sfErrorNotifierMessageHelper();
    
    $serverData = $helper->formatServer();
    
    $this->assertType('array', $serverData);
    
    $expectedKeys =  array('module', 'action', 'uri', 'server', 'session');
    $this->assertEquals($expectedKeys, array_keys($serverData));
  }
  
  public function testFormatSubject()
  {
    $stubRequest = $this->getStubStrict(
      'sfWebRequest', array('getUri' => 'www.notify.com'), array(), '', false);
    $stubContext = $this->getStubStrict('sfContext', array('getRequest' => $stubRequest));
    
    $notifier = $this->getStubStrict(
      'sfErrorNotifier', array('context' => $stubContext), array(), '', false);
    sfErrorNotifier::setInstance($notifier);
    
    sfConfig::set('sf_environment', 'foo_env');
    
    $helper = new sfErrorNotifierMessageHelper();
    
    $subject = $helper->formatSubject('FooSubject');
    
    $this->assertType('string', $subject);
    $this->assertEquals("Notification: www.notify.com - foo_env - FooSubject", $subject);
  }
  
  /**
   * 
   * @dataProvider providerTitle
   */
  public function testFormatTitle($title, $expectedTitle)
  {
    $helper = new sfErrorNotifierMessageHelper();
    
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