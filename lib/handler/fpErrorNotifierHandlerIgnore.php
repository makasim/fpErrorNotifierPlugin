<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage handler 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 * @author     Ton Sharp <forma@66Ton99.org.ua>
 */
class fpErrorNotifierHandlerIgnore extends fpErrorNotifierHandler
{ 
  /**
   * List of known errors
   *
   * @var array
   */
  protected static $errorList = array();
  
  /**
   * 
   * @var array
   */
  protected $options = array(
    'ignore_@' => true,
    'ignore_errors' => array(),
    'ignore_exceptions' => array(),
    'ignore_known_errors' => true,
    'log_ignored' => true);
    
  /**
   * 
   * @return void
   */
  public function initialize()
  { 
    // Prevent blocking of error reporting, becuse of @ - error-control operator.
    if ($this->options['ignore_@'] && 0 == error_reporting()) @error_reporting(-2);
    
    return parent::initialize();
  }
  
  /**
   * (non-PHPdoc)
   * @see fpErrorNotifierHandler::handleError()
   */
  public function handleError($errno, $errstr, $errfile, $errline)
  {
    // Set becvause of @ error-control operator.
    if ($this->options['ignore_@'] && 0 == error_reporting()) return;
    if (in_array($errno, $this->options['ignore_errors'])) {
      $this->logIgnored(new ErrorException($errstr, 0, $errno, $errfile, $errline));      
      return;
    }
    return parent::handleError($errno, $errstr, $errfile, $errline);
  }
  
  /**
   * 
   * @param Exception $e
   * 
   * @return void
   */
  public function handleException(Exception $e)
  {
    if ($this->ignoreException($e) || $this->ignoreError($e) || $this->ignoreKnownError($e)) return;
    parent::handleException($e);
  }
    
  /**
   * 
   *
   * @param Exception $e
   *
   * @return
   */
  protected function ignoreError(Exception $e)
  {
    $code = $e->getCode();
    if (empty($code) && $e instanceof ErrorException) {
      $code = $e->getSeverity();
    }
    if (in_array($code, $this->options['ignore_errors'])) {
      $this->logIgnored($e);      
      return true;
    }
   
    return false;
  }
  
  /**
   * Ignore known error
   * For example: if some error execute in a loop
   *
   * @param Exception $e
   *
   * @return bool
   */
  protected function ignoreKnownError(Exception $e)
  {
    if (empty($this->options['ignore_known_errors'])) return false;
    $errorIdentifier = $e->getFile() . $e->getLine() . $e->getCode() . $e->getMessage();
    if (in_array($errorIdentifier, self::$errorList)) return true;
    self::$errorList[] = $errorIdentifier;
    return false;
  }
  
	/**
   * Clear lisf of known errors
   *
   * @return void
   */
  public static function clearErrorList()
  {
    self::$errorList = array();
  }
  
  /**
   * 
   *
   * @param Exception $e
   *
   * @return
   */
  protected function logIgnored(Exception $e)
  {
    if (!$this->options['log_ignored']) return;
    
    $this->notifier()->context()->getLogger()->info(
      'fpErrorNotifierPlugin: Ignored exception `'.get_class($e).'`. Message `'.$e->getMessage() .
      '`. File `'.$e->getFile().'`. Line `'.$e->getLine().'`'); 
  }
  
  /**
   * Add or remore errors from ignore list on the fly 
   * 
   * @param int $code
   * @param bool $status - true - put, false - remove error from list
   */
  public function setIgnore($code, $status = true)
  {
    if ($status && !in_array($code, $this->options['ignore_errors'])) {
      $this->options['ignore_errors'][] = $code;
    } elseif (!$status && in_array($code, $this->options['ignore_errors'])) {
      unset($this->options['ignore_errors'][array_search($code, $this->options['ignore_errors'])]);
    }
  }
  
	/**
   * 
   *
   * @param Exception $e
   *
   * @return
   */
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

}