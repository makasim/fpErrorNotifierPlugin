<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage handler 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierHandlerIgnore extends sfErrorNotifierHandler
{ 
  /**
   * 
   * @var array
   */
  protected $options = array(
    'error_reporting' => E_ALL,
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
    if ($this->ignoreException($e)) return;

    parent::handleException($e);
  }

	/**
	 * 
	 * @param string $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param string $errline
	 * 
	 * @return void
	 */
	public function handleError($errno, $errstr, $errfile, $errline)
	{ 	  
    $error = new ErrorException($errstr, 0, $errno, $errfile, $errline);
    if ($this->ignoreError($error)) return;
 
    parent::handleError($errno, $errstr, $errfile, $errline);
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
  
  protected function ignoreError(ErrorException $e)
  {
    $ignore_errors = $this->options['ignore_errors'];
    if (isset($ignore_errors[$e->getSeverity()]) && $ignore_errors[$e->getSeverity()]) {
      $this->logIgnored($e);
      return true;
    }
   
    return false;
  }
  
  protected function logIgnored(Exception $e)
  {
    if (!$this->options['log_ignored']) return;
    
    $this->notifier()->context()->getLogger()->info(
      'sfErrorNotifierPlugin: Ignored exception `'.get_class($e).'`. Message `'.$e->getMessage().
      '`. File `'.$e->getFile().'`. Line `'.$e->getLine().'`'); 
  }
}