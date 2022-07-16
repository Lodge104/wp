<?php

/*
 * methods and properties for a tab-enabled shortcode
 * 
 * a tab-enabled shortcode is a participants database shortcode that has field group tabs enabled
 *
 * @package    WordPress
 * @subpackage Participants Database Plugin
 * @author     Roland Barker <webdesign@xnau.com>
 * @copyright  2015  xnau webdesign
 * @license    GPL2
 * @version    0.1
 * @link       http://xnau.com/wordpress-plugins/
 * @depends    
 */

class Tab_Enabled_Shortcode {
  /**
   * @var string the unique idenifier for the shortcode output
   */
  private $id;
  /**
   * @var string the name of the shortcode's modulw
   */
  private $module;
  /**
   * @var string the unique tabs class prefix
   */
  static $prefix = 'pdb-tabs-instance-';
  /**
   * 
   */
  function __construct($module, $id)
  {
    $this->module = $module;
    $this->id = $id;
  }
  /**
   * supplies the unique class string
   * 
   * @return string
   */
  public function tabs_class() {
    return self::$prefix . $this->id();
  }
  /**
   * supplies the unique id
   * 
   * @return string
   */
  public function id() {
    return $this->id;
  }
  /**
   * supplies the module name for the shortcode
   * 
   * @return string
   */
  public function module() {
    return $this->module;
  }
  /**
   * adds a unique class to the shortcode class attribute
   * 
   * @param array $attributes
   * 
   * @return array the amended attributes array
   */
  public function add_unique_class($attributes) {
    $class = isset($attributes['class']) && ! empty($attributes['class']) ? $attributes['class'] . ' ' : '';
    $attributes['class'] = $class . $this->tabs_class();
    return $attributes;
  }
}