<?php

$configFiles = array(
  sfConfig::get('sf_plugins_dir').'/sfErrorNotifier2Plugin/config/notify.yml');

$finder = sfFinder::type('file')->name('notify.yml');
$configFiles = array_merge(
  $configFiles, 
  $finder->in(sfConfig::get('sf_plugins_dir')),
  $finder->in(sfConfig::get('sf_root_dir').'/config'),
  $finder->in(sfConfig::get('sf_root_dir').'/apps'));
$configFiles = array_unique($configFiles);

$config = array();
foreach($configFiles as $file) {
  $config = sfToolkit::arrayDeepMerge(
    $config,
    replaceConstants(mergeEnvironment(sfYaml12::load($file))));
}

foreach ($config as $name => $value) {
  sfConfig::set("sf_notify_{$name}", $value);  
}

sfErrorNotifier::setInstance(new sfErrorNotifier());
sfErrorNotifier::getInstance()->handler()->initialize();

function mergeEnvironment($config)
{  
  return sfToolkit::arrayDeepMerge(
    isset($config['default']) && is_array($config['default']) ? $config['default'] : array(),
    isset($config['all']) && is_array($config['all']) ? $config['all'] : array(),
    isset($config[sfConfig::get('sf_environment')]) && is_array($config[sfConfig::get('sf_environment')]) ? $config[sfConfig::get('sf_environment')] : array()
  );
}

function replaceConstants($value)
{
  if (is_array($value))
  {
    array_walk_recursive($value, create_function('&$value', '$value = sfToolkit::replaceConstants($value);'));
  }
  else
  {
    $value = sfToolkit::replaceConstants($value);
  }

  return $value;
}