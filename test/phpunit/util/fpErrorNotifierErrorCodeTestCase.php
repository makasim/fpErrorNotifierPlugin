<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage test 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierErrorCodeTestCase extends sfBasePhpunitTestCase
{
  public function testConstantsEquals()
  {
    $this->assertEquals(fpErrorNotifierErrorCode::E_ERROR, E_ERROR);
    $this->assertEquals(fpErrorNotifierErrorCode::E_RECOVERABLE_ERROR, E_RECOVERABLE_ERROR);
    $this->assertEquals(fpErrorNotifierErrorCode::E_WARNING, E_WARNING);
    $this->assertEquals(fpErrorNotifierErrorCode::E_PARSE, E_PARSE);
    $this->assertEquals(fpErrorNotifierErrorCode::E_NOTICE, E_NOTICE);
    $this->assertEquals(fpErrorNotifierErrorCode::E_STRICT, E_STRICT); 
    $this->assertEquals(fpErrorNotifierErrorCode::E_CORE_ERROR, E_CORE_ERROR);
    $this->assertEquals(fpErrorNotifierErrorCode::E_CORE_WARNING, E_CORE_WARNING);
    $this->assertEquals(fpErrorNotifierErrorCode::E_COMPILE_ERROR, E_COMPILE_ERROR);
    $this->assertEquals(fpErrorNotifierErrorCode::E_COMPILE_WARNING, E_COMPILE_WARNING);
    $this->assertEquals(fpErrorNotifierErrorCode::E_USER_ERROR, E_USER_ERROR);
    $this->assertEquals(fpErrorNotifierErrorCode::E_USER_WARNING, E_USER_WARNING);
    $this->assertEquals(fpErrorNotifierErrorCode::E_USER_NOTICE, E_USER_NOTICE);
    $this->assertEquals(fpErrorNotifierErrorCode::E_ALL, E_ALL);
    $this->assertEquals(fpErrorNotifierErrorCode::E_UNKNOWN, 'E_UNKNOWN');
  }
  
  public function testGetAll()
  {
    $errors = fpErrorNotifierErrorCode::getAll();
    
    $this->assertType('array', $errors);
    $this->assertEquals(15, count($errors));
    $this->assertContains(fpErrorNotifierErrorCode::E_ERROR, $errors);
  }
  
  public function testGetFatals()
  {
    $errors = fpErrorNotifierErrorCode::getFatals();
    
    $this->assertType('array', $errors);
    $this->assertEquals(5, count($errors));
    $this->assertContains(fpErrorNotifierErrorCode::E_CORE_ERROR, $errors);
  }
  
  public function testGetCode()
  {
    $this->assertEquals(
      fpErrorNotifierErrorCode::E_CORE_ERROR, 
      fpErrorNotifierErrorCode::getCode('E_CORE_ERROR'));
    
    $this->assertEquals(
      fpErrorNotifierErrorCode::E_UNKNOWN, 
      fpErrorNotifierErrorCode::getCode('E_FOO'));
  }
  
  public function testGetName()
  {
    $this->assertEquals(
      'E_CORE_ERROR',
      fpErrorNotifierErrorCode::getName(fpErrorNotifierErrorCode::E_CORE_ERROR));
    
    $this->assertEquals(
      'E_UNKNOWN', 
      fpErrorNotifierErrorCode::getName('FOO'));
  }
}