<?php

/**
 *
 * Plugin Name:			Plugin Scaffold
 * Description:			Blank plugin for developing new custom plugins
 * Version:				1.0
 * Requires at least:	6.9
 * Requires PHP:		8.3
 * Author:				Ervin Domonkos
 * Author URI:			https://ervinpress.com/
 * License:				GPL v2 or later
 * License URI:			https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         plugin-scaffold
 * Domain Path:         /languages
 * Requires Plugins:	advanced-custom-fields-pro
 *
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    exit;
}

// autoloader
require __DIR__ . '/vendor/autoload.php';

use PluginScaffold\PluginCore;

// base class
// phpcs:ignore
class PluginScaffoldPlugin {


	// Get the URL directory path (with trailing slash)
	public static function baseUrl() : string {
		return plugin_dir_url( __FILE__ );
	}


	// Get the filesystem directory path (with trailing slash)
	public static function basePath() : string {
		return plugin_dir_path( __FILE__ );
	}


	// Get the plugin basename
	public static function basePluginName() : string {
		return plugin_basename( __FILE__ );
	}


	public static function pluginFileName() : string {
		return __FILE__;
	}


	public static function pluginVersion() : string {
		return get_plugin_data( __FILE__ )['Version'];
	}


}


/**
 * Use this function like you would a global variable, except without needing to declare the global.
 */
if ( !function_exists( 'PluginScaffold' ) ) {
	function PluginScaffold() : PluginCore {
		return PluginCore::instance();
	}
}


// Gets Plugin Running.
add_action( 'plugins_loaded', 'PluginScaffold', 1290 );
