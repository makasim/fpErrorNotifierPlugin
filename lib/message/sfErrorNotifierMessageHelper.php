<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage message 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierMessageHelper
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
      'class' => get_class($e),
      'code' => $e->getCode(),
      'message' => $e->getMessage(),
      'file' => "{$e->getFile()}, Line: {$e->getLine()}",
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
    return array(
      'subject' => $title,
      'environment' => sfConfig::get('sf_environment', 'undefined'),
      'generated at' => date('H:i:s j F Y'));
  }
  
  /**
   * 
   * @return array
   */
  public function formatServer()
  {
    $context = $this->notifier()->context();
    
    return array(
      'module' => $context->getModuleName(),
      'action' => $context->getActionName(),
      'uri' => $context->getRequest()->getUri(),
      'server' => $this->dump($_SERVER),
      'session' => $this->dump(isset($_SESSION) ? $_SESSION : null));
  }
  
  /**
   * 
   * @return string
   */
  public function formatSubject($title)
  {
    $uri = $this->notifier()->context()->getRequest()->getUri();
    $env = sfConfig::get('sf_environment', 'undefined');
    
    return "Notification: {$uri} - {$env} - {$title}";
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
   * @return sfErrorNotifier
   */
  protected function notifier()
  {
    return sfErrorNotifier::getInstance();
  }
}