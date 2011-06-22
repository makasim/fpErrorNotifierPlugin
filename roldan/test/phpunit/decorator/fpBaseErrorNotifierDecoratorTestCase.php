<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpBaseErrorNotifierDecoratortTestCase extends sfBasePhpunitTestCase
{
  public function testConstruct()
  {
    $message = new fpErrorNotifierMessage('foo title');
    
    $decorator = 
      $this->getMockForAbstractClass('fpBaseErrorNotifierDecorator', array($message));
      
    $this->assertAttributeSame($message, 'message', $decorator);
  }
  
  /**
   * 
   */
  public function testCallProxyCallsToMessage()
  {
    $message = $this->getStubStrict('fpErrorNotifierMessage', array('getFoo' => 'foo'));
    
    $decorator = 
      $this->getMockForAbstractClass('fpBaseErrorNotifierDecorator', array($message));
      
    $result = $decorator->getFoo();
    
    $this->assertEquals('foo', $result);
  }
  
  /**
   * 
   * @dataProvider providerProxyMethods
   */
  public function testMethodCallsProxiesToMessageObject($proxedMethod, $args = array())
  {
    $message = $this->getStubStrict('fpErrorNotifierMessage', array($proxedMethod => 'foo'));
    
    $decorator = 
      $this->getMockForAbstractClass('fpBaseErrorNotifierDecorator', array($message));
      
      
      
    $result = call_user_func_array(array($decorator, $proxedMethod), $args);
    
    $this->assertEquals('foo', $result);
  }
  
  /**
   * 
   */
  public static function providerProxyMethods()
  {
    return array(
      array('addSection', array('sectionFoo', array())),
      array('removeSection', array('sectionFoo')),
      array('subject'),
      array('getIterator'));
  }
}