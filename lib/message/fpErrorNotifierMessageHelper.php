<?php

/**
 *
 * @package    fpErrorNotifier
 * @subpackage message 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierMessageHelper
{
  /**
   * 
   * @param Exception $e
   * 
   * @return array
   */
  public function formatException(Exception $e)
  {
    return array(
      'class' => $e instanceof ErrorException ? 
        fpErrorNotifierErrorCode::getName($e->getSeverity()) : 
        get_class($e),
      'code' =>  $e->getCode(),
      'severity' => $e instanceof ErrorException ? $e->getSeverity() : 'null',
      'message' => $e->getMessage(),
      'file' => "File: {$e->getFile()}, Line: {$e->getLine()}",
      'trace' => $e->getTraceAsString());
  }
  
  /**
   * 
   * @param string $title
   * 
   * @return array
   */
  public function formatSummary($title)
  {
    $context = $this->notifier()->context();
    if (empty($_SERVER['HTTP_HOST'])) {
      $uri = implode(' ', $_SERVER['argv']);
    } else {
      $uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    return array(
      'subject' => $title,
      'uri' => $uri,
      'environment' => sfConfig::get('sf_environment', 'undefined'),
      'module' => $context->getModuleName(),
      'action' => $context->getActionName(),
      'generated at' => date('H:i:s j F Y')
    );
  }
  
  /**
   * 
   * @return array
   */
  public function formatServer()
  {
    return array(
      'server' => $this->dump($_SERVER),
      'session' => $this->dump(isset($_SESSION) ? $_SESSION : null));
  }
  
  /**
   * 
   * @return string
   */
  public function formatSubject($title)
  {
    $env = sfConfig::get('sf_environment', 'undefined');
    
    return "Notification: {$env} - {$title}";
  }
  
  /**
   * 
   * @param string $title
   * 
   * @return string
   */
  public function formatTitle($title)
  {
    $titleArr = trim(str_replace(array('_', '-'), ' ', $title));
    $titleArr = array_filter(explode(' ', $titleArr));

    $title = '';
    foreach ($titleArr as $part) {
      $title .= ' '.ucfirst(strtolower($part));
    }
    
    return trim($title);
  }
  
  /**
   * 
   * @param mixed $value
   * 
   * @return string
   */
  public function formatValue($value)
  {
    is_string($value) || $value = $this->dump($value);
    
    return nl2br(htmlspecialchars($value));
  }
  
  /**
   * 
   * @param mixed $value
   * 
   * @return string
   */
  public function dump($value)
  {
    return var_export($value, true);
  }
  
  /**
   * 
   * @return fpErrorNotifier
   */
  protected function notifier()
  {
    return fpErrorNotifier::getInstance();
  }
}