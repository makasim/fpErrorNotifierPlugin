<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage exception 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierFilterException extends sfFilter
{
  /**
   * 
   * @param $filterChain
   */
  public function execute($filterChain)
  {
    try {
      $filterChain->execute();
    } catch (Exception $e) {
      sfErrorNotifier::getInstance()->handler()->handleException($e);
      throw $e;
    }
  }
}