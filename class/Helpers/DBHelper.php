<?php

namespace PluginScaffold\Helpers;

if ( !defined( 'WPINC' ) ) {
	exit;
}

class DBHelper {


	protected string $charset_collate;
	public const DB_VERSION = '1.0';	// It has to be adjusted to the plugin version in which it was last changed


	public function __construct() {
		global $wpdb;

		$this->charset_collate = $wpdb->get_charset_collate();

	}


	public function maybe_update_db() : void {

		if ( version_compare( self::DB_VERSION, $this->get_saved_db_version() ) == 1 ) {
			$this->update_db_version();
		}

	}


	public function get_table_name( $sub_name ) : string {
		global $wpdb;

		return $wpdb->prefix . 'plugin_scaffold_' . $sub_name;

	}


	private function generate_table( $sql ) : array {

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		return dbDelta( $sql );

	}


	private function get_saved_db_version() {

		$saved_db_version = get_option( 'plugin_scaffold_db_version' );

		if ( $saved_db_version ) {
			return $saved_db_version;
		}

		return 0;

	}


	public function update_db_version() : void {

		update_option( 'plugin_scaffold_db_version', self::DB_VERSION );

	}


}
