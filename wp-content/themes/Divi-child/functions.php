<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_filter( 'auto_update_theme', '__return_true' );
add_filter( 'auto_update_plugin', '__return_true' );

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION

/**
 * Debug Pending Updates
 *
 * Crude debugging method that will spit out all pending plugin
 * and theme updates for admin level users when ?debug_updates is
 * added to a /wp-admin/ URL.
 */
function debug_pending_updates() {

    // Rough safety nets
    if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) return;
    if ( ! isset( $_GET['debug_updates'] ) ) return;

    $output = "";

    // Check plugins
    $plugin_updates = get_site_transient( 'update_plugins' );
    if ( $plugin_updates && ! empty( $plugin_updates->response ) ) {
        foreach ( $plugin_updates->response as $plugin => $details ) {
            $output .= "<p><strong>Plugin</strong> <u>$plugin</u> is reporting an available update.</p>";
        }
    }

    // Check themes
    wp_update_themes();
    $theme_updates = get_site_transient( 'update_themes' );
    if ( $theme_updates && ! empty( $theme_updates->response ) ) {
        foreach ( $theme_updates->response as $theme => $details ) {
            $output .= "<p><strong>Theme</strong> <u>$theme</u> is reporting an available update.</p>";
        }
    }

    if ( empty( $output ) ) $output = "No pending updates found in the database.";

    wp_die( $output );
}
add_action( 'init', 'debug_pending_updates' );