<?php
/*
Plugin Name: Lodge Leadership
Plugin URI: http://mckernan.in/lodge-leadership/
Description: Easily manage Order of the Arrow Leadership Positions
Author: Kevin McKernan
Author URI: http://mckernan.in
Version: 0.4
GitHub Plugin URI: mckernanin/oa-leadership
Tested up to: 4.1
*/

/*

CONTENTS
1. Base URLs
2. Includes
3. Enqueue Assets
4. Customizer Settings
5. ACF Related Functions
	
*/


/*============
1. Base URLs
============*/
if ( ! defined( 'OALDR_BASE_FILE' ) )
	define( 'OALDR_BASE_FILE', __FILE__ );
if ( ! defined( 'OALDR_BASE_DIR' ) )
	define( 'OALDR_BASE_DIR', dirname( OALDR_BASE_FILE ) );
if ( ! defined( 'OALDR_PLUGIN_URL' ) )
	define( 'OALDR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/*============
2. Includes
============*/
 
include( plugin_dir_path( __FILE__ ) . 'inc/post-types.php');
include( plugin_dir_path( __FILE__ ) . 'inc/shortcodes.php');
include( plugin_dir_path( __FILE__ ) . 'inc/taxonomies.php');

/*============
3. Enqueue Assets
=============*/

function oaldr_scripts() {
	wp_register_script('mixitup', 'http://cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js', true);
	wp_register_script('oaldr-main', plugin_dir_url(__FILE__) . '/js/oaldr-main.js', true);
	wp_register_style('oaldr-styles', plugin_dir_url(__FILE__) . '/css/style.css');
}

add_action('wp_enqueue_scripts', 'oaldr_scripts');

/*============
4. Customizer Settings
============*/

function oaldr_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'oaldr_settings' , array(
	    'title'      => __( 'OA Leadership Settings', 'oaldr' ),
	    'priority'   => 30,
	) );

	$wp_customize->add_setting( 'oaldr_headshot_default' , array(
	    'default'     => '',
	    'transport'   => 'refresh',
	) );

	$wp_customize->add_control(
       new WP_Customize_Image_Control(
           $wp_customize,
           'oaldr_headshot_default_control',
           array(
               'label'      => __( 'Upload a placeholder', 'OA Leadership Position Plugin' ),
               'section'    => 'oaldr_settings',
               'settings'   => 'oaldr_headshot_default',
               'context'    => 'your_setting_context' 
           )
       )
   );

	$wp_customize->add_setting( 'oaldr_categorize_positions', array(
		'default'		=> 'lodge',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'refresh'
	) );

	$wp_customize->add_control( 'oaldr_categorize_positions', array(
		'label'		=> __( 'Categorize Positions by?', 'OA Leadership Position Plugin' ),
		'section'	=> 'oaldr_settings',
		'settings'	=> 'oaldr_categorize_positions',
		'type'		=> 'select',
		'choices'	=> array(
			'chapter' => __( 'Chapter', 'OA Leadership Position Plugin' ),			
			'lodge'   => __( 'Lodge', 'OA Leadership Position Plugin' ),
			'section' => __( 'Section', 'OA Leadership Position Plugin' ),
		),
	) );
}
add_action( 'customize_register', 'oaldr_customize_register' );

/*============
5. ACF Related Functions
============*/

add_filter('acf/settings/save_json', 'oaldr_acf_json_save_point');
 
function oaldr_acf_json_save_point( $path ) {
    
    // update path
    $path = plugin_dir_path(__FILE__) . '/acf-json';
    
    
    // return
    return $path;
    
}

add_filter('acf/settings/load_json', 'oaldr_acf_json_load_point');

function oaldr_acf_json_load_point( $paths ) {
    
    // remove original path (optional)
    unset($paths[0]);
    
    
    // append path
    $paths[] = plugin_dir_path(__FILE__) . '/acf-json';
    
    
    // return
    return $paths;
    
}