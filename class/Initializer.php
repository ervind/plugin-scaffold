<?php

namespace PluginScaffold;

use PluginScaffold\Helpers\DBHelper;
use PluginScaffoldPlugin;

if ( !defined( 'WPINC' ) ) {
	exit;
}

class Initializer {


	protected static $registered = false;


	public static function register() : void {

		// can be called only once
		if ( self::$registered ) {
			return;
		}

		self::$registered = true;
		$inst = new self();
		$inst->register_hooks();

	}


	public function register_hooks() : void {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 9990 );
		add_action( 'init', [ $this, 'setup_db' ] );

		add_filter( 'body_class', [ $this, 'filter_body_class' ] );
		add_filter( 'gform_noconflict_scripts', [ $this, 'register_script' ] );
		add_filter( 'upload_mimes', [ $this, 'add_upload_mimes' ] );

	}


	/**
	 * @throws Exception
	 */
	public function enqueue_scripts() : void {

		wp_enqueue_style( 'plugin-scaffold', PluginScaffoldPlugin::baseUrl() . 'assets/style.css', [], PluginScaffoldPlugin::pluginVersion() );
		wp_enqueue_script( 'plugin-scaffold', PluginScaffoldPlugin::baseUrl() . 'assets/scripts.js', [ 'jquery' ], PluginScaffoldPlugin::pluginVersion() );
		wp_localize_script( 'plugin-scaffold', 'PLUGIN_SCAFFOLD', $this->get_js_vars() );

	}


	/**
	 * @throws Exception
	 */
	public function get_js_vars() : array {

		return [];

	}


	/**
	 * @throws Exception
	 */
	public function admin_enqueue_scripts() : void {

		wp_enqueue_style( 'plugin-scaffold-admin', PluginScaffoldPlugin::baseUrl() . 'assets/admin.css', [], PluginScaffoldPlugin::pluginVersion() );
		wp_enqueue_script( 'plugin-scaffold-admin', PluginScaffoldPlugin::baseUrl() . 'assets/admin.js', [ 'jquery' ], PluginScaffoldPlugin::pluginVersion() );

	}


	public function filter_body_class( $classes ) {

		if ( get_field( 'body_class' ) ) {
			$classes[] = get_field( 'body_class' );
		}

		return $classes;

	}


	public function register_script( $scripts ) {

		$scripts[] = 'plugin-scaffold-admin';
		$scripts[] = 'plugin-scaffold';
		return $scripts;

	}


	public function setup_db() : void {

		$db_class = new DBHelper();
		$db_class->maybe_update_db();

	}


	public function add_upload_mimes( $types ) {

		$types["json"] = 'text/plain';
		return $types;

	}


}
