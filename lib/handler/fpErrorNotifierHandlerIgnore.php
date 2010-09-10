<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage handler 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierHandlerIgnore extends fpErrorNotifierHandler
{ 
  /**
   * 
   * @var array
   */
  protected $options = array(
    'ignore_errors' => array(),
    'ignore_exceptions' => array(),
    'log_ignored' => true);
  
  /**
   * 
   * @param Exception $e
   * 
   * @return void
   */
  public function handleException(Exception $e)
  {
    if ($this->ignoreException($e) || $this->ignoreError($e)) return;
    
    parent::handleException($e);
  }
  
  protected function ignoreException(Exception $e)
  {    
    foreach ($this->options['ignore_exceptions'] as $ignoreClass) {
      if ($e instanceof $ignoreClass) {
        $this->logIgnored($e);
        return true;
      }
    }
    
    return false;
  }
  
  protected function ignoreError(Exception $e)
  {
    $code = $e->getCode();
    if (empty($code) && $e instanceof ErrorException) {
      $code = $e->getSeverity();
    }
    $ignore_errors = $this->options['ignore_errors'];
    if (isset($ignore_errors[$code]) && $ignore_errors[$code]) {
      $this->logIgnored($e);
      return true;
    }
   
    return false;
  }
  
  protected function logIgnored(Exception $e)
  {
    if (!$this->options['log_ignored']) return;
    
    $this->notifier()->context()->getLogger()->info(
      'fpErrorNotifierPlugin: Ignored exception `'.get_class($e).'`. Message `'.$e->getMessage().
      '`. File `'.$e->getFile().'`. Line `'.$e->getLine().'`'); 
  }
}