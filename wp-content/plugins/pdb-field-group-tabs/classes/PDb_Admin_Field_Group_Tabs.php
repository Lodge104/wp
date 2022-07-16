<?php

/*
 * handles setting up the tabs in the admin
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

class PDb_Admin_Field_Group_Tabs {
  /**
   * 
   */
  public function __construct()
  {
    add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueues') );;
  }

  /**
   * enqueues the script in the admin if enabled
   * 
   * @param string $hook hame of the current admin page
   */
  public function admin_enqueues($hook)
  {
    if ((stripos($hook, 'participants-database-edit_participant') || stripos($hook, 'participants-database-add_participant'))) {
      $this->update_admin_js();
      wp_enqueue_script('pdb-admin-field-group-tabs', plugins_url('pdb-field-group-tabs/assets/field_group_tabs.js'), array('jquery','jquery-ui-core', 'jquery-ui-tabs', 'pdb-cookie'), '1.2', true);
      wp_enqueue_style('pdb-admin-field-group-tabs', plugins_url('pdb-field-group-tabs/assets/field_group_tabs_admin.css') );
    }
  }

  /**
   * updates the admin JS file
   * 
   */
  private function update_admin_js()
  {
    $old_check = get_option('pdb-field_group_tabs_admin_js');
    $file_contents = $this->admin_javascript();
    
    $new_check = md5($file_contents);
    
    if ($old_check !== $new_check) {
      
      global $PDb_Field_Group_Tabs;
      update_option('pdb-field_group_tabs_admin_js', $new_check);
      $file = fopen( trailingslashit(dirname($PDb_Field_Group_Tabs->plugin_path)) . 'assets/field_group_tabs.js', 'w' );
      fwrite( $file, $file_contents );
      fclose( $file );
      
    }
  }

  /**
   * supplies the admin JS script  
   * 
   * @return string
   */
  private function admin_javascript()
  {
    global $PDb_Field_Group_Tabs;
    $tabconfig = $PDb_Field_Group_Tabs->ui_tabs_config('admin' );
    $funcname = 'PDb_Field_Group_Tabs_Admin';
ob_start()
    ?>
    <script>
<?php echo $funcname ?> = (function($) {
  var container;
  var tab_content_fields;
  var tab_control;
  var page_form;
  var setup_tabs = function() {
    tab_content_fields.each(function() {
      add_tab(this);
    });
  }
  var add_tab = function(el) {
    var el = $(el);
    var tab_anchor = tab_anchor_string(el);
    el.attr('id', tab_anchor);
    $('<li><a href="#' + tab_anchor + '" >' + find_title(el) + '</a><span class="mask"></span></li>').appendTo(tab_control);
  }
  var tab_anchor_string = function(el) {
    return el.prop('class').match(/field-group-[^ "']+/)[0];
  }
  var id_string = function(el) {
    var unique_id = el.prop('class').match(/field-group-([^ "']+)/);
    return unique_id[1];
  }
  var find_title = function(el) {
    var title_el = el.find('.field-group-title');
    var title = title_el.text();
    return title.length ? title : id_string(el);
  }
  var show_invalid = function(el) {
    var tablabel = el.closest('div.ui-tabs-panel').attr('aria-labelledby');
    var this_tab = tab_control.find('li[aria-labelledby='+tablabel+']');
    container.tabs('option','active',this_tab.index());
  }
  var handle_invalid = function(e) {
    var invalid = page_form.find('input:invalid').first();
    if ( invalid.length ) {
      show_invalid(invalid);
    }
  }
  return {
    init: function() {
      container = $('.pdb-admin-edit-participant');
      page_form = container.find('form').first();
      tab_content_fields = container.find('.field-group:not([class*=submit])');
      tab_control = $('<ul class="pdb-tabs" />');
      tab_control.prependTo(page_form);
      setup_tabs();
      container.tabs(<?php echo $tabconfig ?>);
      page_form.on('click','[type=submit]',handle_invalid);
    }
  }
}(jQuery));
jQuery(function() {
  <?php echo $funcname ?>.init();
});
</script>
<?php
    return str_replace( array( '<script>', '</script>' ), '', ob_get_clean() );
  }

}