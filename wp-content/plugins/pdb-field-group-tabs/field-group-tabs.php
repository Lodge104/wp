<?php

/*
 * Plugin Name: Participants Database Field Group Tabs
 * Version: 1.14
 * Description: Creates a tabbed interface for Participants Database field groups 
 * Author: Roland Barker, xnau webdesign
 * Plugin URI: https://xnau.com/shop/field-group-tabs/
 * Text Domain: pdb-field-group-tabs
 * Domain Path: /languages
 * 
 */
spl_autoload_register( 'pdb_field_group_tabs_autoload' );

if ( class_exists( 'Participants_Db' ) ) {
  pdb_field_group_tabs_initialize();
} else {
  add_action( 'participants-database_activated', 'pdb_field_group_tabs_initialize' );
}

function pdb_field_group_tabs_initialize()
{
  global $PDb_Field_Group_Tabs;
  if ( !is_object( $PDb_Field_Group_Tabs ) && version_compare( Participants_Db::$plugin_version, '1.6.2.6', '>' ) ) {
    $PDb_Field_Group_Tabs = new PDb_Field_Group_Tabs( __FILE__ );
  }
}

function pdb_field_group_tabs_autoload( $class )
{
  $file = 'classes/' . $class . '.php';
  if ( !class_exists( $class ) && is_file( trailingslashit( plugin_dir_path( __FILE__ ) ) . $file ) ) {
    include $file;
  }
}
/**
 * sets up and prints that tab control on a singup, single or record form
 * 
 * @param object  $shortcode the current shortcode object
 */
function pdb_field_group_tabs( $shortcode )
{
  global $PDb_Field_Group_Tabs;
  echo $PDb_Field_Group_Tabs->get_tab_control( new PDb_Template( $shortcode ));
}

/**
 * prints the next/submit button
 * 
 * @param object  $shortcode the current shortcode object
 */
function pdb_field_group_tabs_submit_button( $shortcode )
{
  global $PDb_Field_Group_Tabs;
  echo $PDb_Field_Group_Tabs->get_next_or_submit_button( $shortcode->shortcode_atts['submit_button'] );
}
