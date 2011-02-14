<?php

require_once dirname(__FILE__) . '/include.php';

/** 
 *
 * @package    fpErrorNotifier
 * @subpackage config 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 * @author     Ton Sharp <forma@66Ton99.org.ua>
 */
class fpErrorNotifierPluginConfiguration extends sfPluginConfiguration
{
  /**
   * 
   * @return void
   */
  public function initialize()
  {
    fpErrorNotifier::initialize($this->configuration);
  }
}