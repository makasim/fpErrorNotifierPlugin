<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierMessageTestCase extends sfBasePhpunitTestCase
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
  
  public function testConstruct()
  {
    $stubHelper = $this->getStubStrict('fpErrorNotifierMessageHelper', array(
      'formatSummary' => array('foo' => 'bar'),
      'formatSubject' => 'Foo Subject'));
    
    $notifier = $this->getStubStrict(
      'fpErrorNotifier', array('helper' => $stubHelper), array(), '', false);
    fpErrorNotifier::setInstance($notifier);
    
    $message = new fpErrorNotifierMessage('foo');
    
    $this->assertAttributeEquals(
      array('Summary' => array('foo' => 'bar')), '_data', $message);
      
    $this->assertEquals('Foo Subject', $message->subject());
    
    return $message;
  }
  
  /**
   * 
   * @depends testConstruct
   */
  public function testRender(fpErrorNotifierMessage $message)
  {
    $this->assertEquals($message->subject(), $message->render());
  }
  
  /**
   * 
   * @depends testConstruct
   */
  public function testAddSection(fpErrorNotifierMessage $message)
  {
    $message->addSection('Test', array('bar' => 'foo'));
    
    $expectedData = array(
      'Summary' => array('foo' => 'bar'),
      'Test' => array('bar' => 'foo'));
    
    $this->assertAttributeEquals($expectedData, '_data', $message);
    
    return $message;
  }
  
  /**
   * @depends testAddSection
   */
  public function testGetIterator(fpErrorNotifierMessage $message)
  {
    $this->assertType('IteratorAggregate', $message);
    
    $iterator = $message->getIterator();

    $this->assertTrue($iterator->valid());
    $this->assertEquals('Summary', $iterator->key());
    $this->assertEquals(array('foo' => 'bar'), $iterator->current());
    
    $iterator->next();
    
    $this->assertTrue($iterator->valid());
    $this->assertEquals('Test', $iterator->key());
    $this->assertEquals(array('bar' => 'foo'), $iterator->current());
    
    $iterator->next();
    
    $this->assertFalse($iterator->valid());
  }
  
  
  
  
}