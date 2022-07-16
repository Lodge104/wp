<?php

/*
 * sets up the tab interface for the single shortcode
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

class PDb_Tab_Interface_Single extends PDb_Tab_Interface {

  public function __construct($te_shortcode)
  {
    $this->module = 'single';
    parent::__construct($te_shortcode);
  }

  /**
   * supplies the javascript for setting the tabbed interface
   */
  protected function page_javascript()
  {
    global $PDb_Field_Group_Tabs;
    $tabconfig = $PDb_Field_Group_Tabs->ui_tabs_config( $this->module );
    $funcname = 'PDb_Field_Group_Tabs_' . $this->unique_id;
    $js = <<<JS
$funcname = (function($) {
  return {
    init: function() {
      $('.$this->unique_class').tabs($tabconfig);
    }
  }
}(jQuery));
jQuery(function() {
  $funcname.init();
});
JS;
    return '<script type="text/javascript" defer>' . $js . '</script>';
  }

  /**
   * prints the tab control
   * 
   * this is meant to be used in a Participants Database template
   * 
   * @param object $record
   * @param bool $print if false, the control is returned instead of printed
   * 
   * @return string|null
   */
  public static function get_tab_control(PDb_Template $record, $print = false) {
    $output = '<ul class="pdb-tab-control">';
    $tab_pattern = '<li><a href="#%s">%s</a></li>';
    foreach ($record->groups as $group) {
      $output .= sprintf($tab_pattern, Participants_Db::$prefix . $group->name, $group->title);
    }
    $output .= '</ul>';
    if ($print) {
      echo $output;
    } else {
      return $output;
    }
  }
}

?>
