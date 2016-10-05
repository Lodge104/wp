<?php
   /*
   Plugin Name: Fiat Alert Bar
   Plugin URI: http://fiatinsight.com/plugins/fiat-alert
   Description: Create alerts using a custom post type, which can appear anywhere that you embed the [fiatalert] shortcode in your template.
   Version: 1.5
   Author: Fiat Insight
   Author URI: http://fiatinsight.com
   License: GPL2
   */

//Add Custom Post Type for Fiat Alert

add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'fiat_alert',
		array(
			'labels' => array(
				'name' => __( 'Alerts' ),
				'singular_name' => __( 'Alert' )
			),
		'public' => true,
		'menu_icon' => plugins_url( 'fiat-alert.png', __FILE__ ),
		'has_archive' => true,
		)
	);
}

//Add Fiat Alert Function

function fiatalert_func( $content ) {
	$args = array( 'post_type' => 'fiat_alert', 'posts_per_page' => 1 );
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
	echo '<div class="fiat-alert">';
	echo '<button type="button" class="close" data-dismiss="alert" style="padding: 20px 15px 0 0;">&times;</button>';
	the_content();
	echo '</div>';
	endwhile;
	wp_reset_query();
}

//Add Fiat Alert Shortcode

add_shortcode( 'fiatalert', 'fiatalert_func' );

//Call the Fiat Alert Stylesheet using wp_enqueue_style & wp_register_style

add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

//Add Fiat Shortcode in text widgets

add_filter('widget_text', 'do_shortcode');

function prefix_add_my_stylesheet() {
        wp_register_style( 'prefix-style', plugins_url('fiat-alert-style.css', __FILE__) );
        wp_enqueue_style( 'prefix-style' );
}

//Add Links to the Plugins Page

add_filter('plugin_row_meta',  'Register_Plugins_Links', 10, 2);
function Register_Plugins_Links ($links, $file) {
               $base = plugin_basename(__FILE__);
               if ($file == $base) {
                       $links[] = '<a href="http://wordpress.org/plugins/fiat-alert-bar/faq/">' . __('FAQs') . '</a>';
               }
               return $links;
       }