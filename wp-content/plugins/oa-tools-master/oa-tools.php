<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://mckernan.in
 * @since             1.1.0
 * @package           OA_Tools
 *
 * @wordpress-plugin
 * Plugin Name:       Order of the Arrow Tools
 * Plugin URI:        http://mckernan.in/plugins/
 * Description:       A plugin of tools for Order of the Arrow focused websites.
 * Version:           1.1.0
 * Author:            Kevin McKernan
 * Author URI:        http://mckernan.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oa-tools
 * Domain Path:       /languages
 * GitHub Plugin URI: mckernanin/oa-tools
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-oa-tools-activator.php
 */
function activate_oa_tools() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oa-tools-activator.php';
	OA_Tools_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-oa-tools-deactivator.php
 */
function deactivate_oa_tools() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oa-tools-deactivator.php';
	OA_Tools_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_oa_tools' );
register_deactivation_hook( __FILE__, 'deactivate_oa_tools' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-oa-tools.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_oa_tools() {

	$plugin = new OA_Tools();
	$plugin->run();

}
run_oa_tools();
