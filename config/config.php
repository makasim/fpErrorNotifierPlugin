<?php

$configFiles = $this->getConfigPaths('config/notify.yml');
$config = sfDefineEnvironmentConfigHandler::getConfiguration($configFiles);
    
foreach ($config as $name => $value) {
  sfConfig::set("sf_notify_{$name}", $value);  
}

sfErrorNotifier::setInstance(new sfErrorNotifier($this->getEventDispatcher()));
sfErrorNotifier::getInstance()->handler()->initialize();