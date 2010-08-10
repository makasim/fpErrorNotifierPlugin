<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierMessageTestCase extends sfBasePhpunitTestCase
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
  
  public function testConstruct()
  {
    $stubHelper = $this->getStubStrict('sfErrorNotifierMessageHelper', array(
      'formatSummary' => array('foo' => 'bar'),
      'formatSubject' => 'Foo Subject'));
    
    $notifier = $this->getStubStrict(
      'sfErrorNotifier', array('helper' => $stubHelper), array(), '', false);
    sfErrorNotifier::setInstance($notifier);
    
    $message = new sfErrorNotifierMessage('foo');
    
    $this->assertAttributeEquals(
      array('Summary' => array('foo' => 'bar')), '_data', $message);
      
    $this->assertEquals('Foo Subject', $message->subject());
    
    return $message;
  }
  
  /**
   * 
   * @depends testConstruct
   */
  public function testRender(sfErrorNotifierMessage $message)
  {
    $this->assertEquals($message->subject(), $message->render());
  }
  
  /**
   * 
   * @depends testConstruct
   */
  public function testAddSection(sfErrorNotifierMessage $message)
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
  public function testGetIterator(sfErrorNotifierMessage $message)
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