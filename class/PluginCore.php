<?php

namespace PluginScaffold;

use PluginScaffold\Helpers\DBHelper;
use PluginScaffoldPlugin;

if ( !defined( 'WPINC' ) ) {
    exit;
}


class PluginCore {

    // the only instance
    private static $instance;
	public DBHelper $db_helper;


	/**
	 * @throws Exception
	 */
	private function __construct() {

		load_plugin_textdomain( 'plugin-scaffold', false, PluginScaffoldPlugin::basePath() . '/languages' );

		Initializer::register();

		$this->db_helper = new DBHelper();

	}


    private static function initInstance() : void {

        self::$instance = new self();

    }


    public static function instance() : PluginCore {

        if ( !( self::$instance instanceof self ) ) {
            self::initInstance();
        }

        return self::$instance;

    }


}
