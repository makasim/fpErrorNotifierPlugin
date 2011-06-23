<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage handler 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 * @author     Ton Sharp <Forma-PRO@66Ton99.org.ua>
 */
class fpErrorNotifierHandlerIgnore extends fpErrorNotifierHandler
{ 
  /**
   * 
   * @var array
   */
  protected $options = array(
    'ignore_@' => true,
    'ignore_errors' => array(),
    'ignore_exceptions' => array(),
    'ignore_duplication' => false,
    'ignore_duplication_time' => 3600,
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
    if ($this->ignoreException($e) || $this->ignoreError($e) || $this->ignoreDuplication($e)) return;

    $this->registerExceptionAsKnown($e);
    
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
   *
   * @param Exception $e
   * 
   * @return boolean
   */
  protected function ignoreDuplication(Exception $e)
  {
    if (false == $this->options['ignore_duplication']) return false;
    
    $key = md5($e->getMessage().$e->getFile().$e->getLine());
    if ($this->getExceptionRegister()->has($key)) {
      $this->logIgnored($e);
      return true;
    }
    
    return false;
  }
  
  /**
   *
   * @param Exception $e 
   * 
   * @return void
   */
  protected function registerExceptionAsKnown(Exception $e)
  {
    if ($this->options['ignore_duplication']) {
      $key = md5($e->getMessage().$e->getFile().$e->getLine());
      $this->getExceptionRegister()->set($key, 1, $this->options['ignore_duplication_time']);
    }
  }
  
  /**
   * 
   * @return sfFileCache
   */
  protected function getExceptionRegister()
  {
    $cacheDir = sfConfig::get('sf_cache_dir') ? sfConfig::get('sf_cache_dir') : sfProjectConfiguration::guessRootDir().'/cache';
    $cacheDir .= '/fpErrorNotifierPlugin';
    
    return new sfFileCache(array('cache_dir' => $cacheDir));
  }
  
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
