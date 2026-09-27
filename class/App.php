<?php

/**
 * App global settings and functions
 *
 */

namespace PluginScaffold;

use PluginScaffoldPlugin;

if ( !defined( 'WPINC' ) ) {
    exit;
}

class App {


	public static function get_template( $template, $args = [] ) : string {

		extract( $args );

		ob_start();
		$success = include PluginScaffoldPlugin::basePath() . 'templates/' . $template;
		$template = ob_get_clean();

		if ( !$success ) {
			return '';
		}

		return $template;

	}


}
