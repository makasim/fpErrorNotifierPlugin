<?php

/** 
 *
 * @package    sfErrorNotifier
 * @subpackage config 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifier2PluginConfiguration extends sfPluginConfiguration
{
  /**
   * 
   * @return void
   */
  public function initialize()
  {
    $this->_initializeConfig();

    $this->notifier()->handler()->initialize();
  }
  
  /**
   * 
   * @return sfErrorNotifier
   */
  protected function notifier()
  {
    sfErrorNotifier::setInstance(
      new sfErrorNotifier($this->configuration->getEventDispatcher()));
      
    return sfErrorNotifier::getInstance();
  }
  
  /**
   * 
   * @return void
   */
  protected function _initializeConfig()
  {
    $configFiles = $this->configuration->getConfigPaths('config/notify.yml');
    $config = sfDefineEnvironmentConfigHandler::getConfiguration($configFiles);
    
    foreach ($config as $name => $value) {
      sfConfig::set("sf_notify_{$name}", $value);  
    }
  }
}