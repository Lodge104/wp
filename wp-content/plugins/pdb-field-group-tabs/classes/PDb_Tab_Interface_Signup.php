<?php

/*
 * sets up the tab interface for the record shortcode
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

class PDb_Tab_Interface_Signup extends PDb_Tab_Interface {

  public function __construct($te_shortcode)
  {
    $this->module = 'signup';
    parent::__construct($te_shortcode);
  }

}