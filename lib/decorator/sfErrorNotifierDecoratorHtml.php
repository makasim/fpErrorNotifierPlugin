<?php

/**
 *
 * @package    sfErrorNotifier
 * @subpackage decorator 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class sfErrorNotifierDecoratorHtml extends sfBaseErrorNotifierDecorator
{
  /**
   * 
   * @return string
   */
  public function format()
  {
    return 'text/html';
  }
  
  /**
   * 
   * @return string
   */
  public function render()
  {
    return '<div style="font-family: Verdana, Arial;">'.parent::render().'</div>';
  }
  
  /**
   * 
   * @param array $data
   * 
   * @return string
   */
  protected function _renderSection(array $data)
  {
    $body = '<table cellspacing="1" width="100%">';
    
    foreach ($data as $name => $value) {
      $body .= $this->_renderRow($name, $value);
    }
    
    $body .= '</table>';
    
    return $body;
  }
  
  /**
   * 
   * @param string $th
   * @param string $td
   * 
   * @return string
   */
  protected function _renderRow($th, $td = '')
  {
    return "
      <tr style=\"padding: 4px;spacing: 0;text-align: left;\">\n
        <th style=\"background:#cccccc\" width=\"140px\">
          {$this->notifier()->helper()->formatTitle($th)}:
        </th>\n
        <td style=\"padding: 4px;spacing: 0;text-align: left;background:#eeeeee\">
          {$this->_prepareValue($td)}
        </td>\n
      </tr>";  
  } 
  
  /**
   * 
   * @param string $title
   * 
   * @return string
   */
  protected function _renderTitle($title)
  {
    return "<h1 style=\"background: #0055A4; color:#ffffff;padding:5px;\">
        {$this->notifier()->helper()->formatTitle($title)}
      </h1>";
  }
  
  /**
   * 
   * @param string $value
   * 
   * @return string
   */
  protected function _prepareValue($value)
  {
    $return = "<pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>";
    $return .= $this->notifier()->helper()->formatValue($value);
    $return .= '</pre>';
    
    return $return;
  }
}