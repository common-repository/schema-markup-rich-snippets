<?php
/**
 * Plugin Activation and De-Activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA;

use RANKMATH_SCHEMA\Traits\Hooker;
use RANKMATH_SCHEMA\Admin\Watcher;
use MyThemeShop\Helpers\WordPress;
use RANKMATH_SCHEMA\Role_Manager\Capability_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Installer class.
 */
class Installer {

	use Hooker;

	/**
	 * Binding all events
	 */
	public function __construct() {
		register_activation_hook( RANKMATH_SCHEMA_FILE, [ $this, 'activation' ] );
		register_deactivation_hook( RANKMATH_SCHEMA_FILE, [ $this, 'deactivation' ] );

		$this->action( 'wpmu_new_blog', 'activate_blog' );
		$this->action( 'activate_blog', 'activate_blog' );
		$this->filter( 'wpmu_drop_tables', 'on_delete_blog' );
	}

	/**
	 * Does something when activating Rank Math.
	 *
	 * @param bool $network_wide Whether the plugin is being activated network-wide.
	 */
	public function activation( $network_wide = false ) {
		if ( ! is_multisite() || ! $network_wide ) {
			$this->activate();
			return;
		}

		$this->network_activate_deactivate( true );
	}

	/**
	 * Does something when deactivating Rank Math.
	 *
	 * @param bool $network_wide Whether the plugin is being activated network-wide.
	 */
	public function deactivation( $network_wide = false ) {
		if ( ! is_multisite() || ! $network_wide ) {
			$this->deactivate();
			return;
		}

		$this->network_activate_deactivate( false );
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @param int $blog_id ID of the new blog.
	 */
	public function activate_blog( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		$this->activate();
		restore_current_blog();
	}

	/**
	 * Uninstall tables when MU blog is deleted.
	 *
	 * @param  array $tables List of tables that will be deleted by WP.
	 * @return array
	 */
	public function on_delete_blog( $tables ) {
		global $wpdb;

		$tables[] = $wpdb->prefix . 'rank_math_schema';
		$tables[] = $wpdb->prefix . 'rank_math_schema_cache';

		return $tables;
	}

	/**
	 * Run network-wide (de-)activation of the plugin.
	 *
	 * @param bool $activate True for plugin activation, false for de-activation.
	 */
	private function network_activate_deactivate( $activate ) {
		global $wpdb;

		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = '0' AND deleted = '0'" );
		if ( empty( $blog_ids ) ) {
			return;
		}

		foreach ( $blog_ids as $blog_id ) {
			$func = true === $activate ? 'activate' : 'deactivate';

			switch_to_blog( $blog_id );
			$this->$func();
			restore_current_blog();
		}
	}

	/**
	 * Runs on activation of the plugin.
	 */
	private function activate() {
		$current_version    = get_option( 'rank_math_schema_version', null );
		$current_db_version = get_option( 'rank_math_schema_db_version', null );

		$this->create_tables();
		$this->create_options();

		if ( is_null( $current_version ) && is_null( $current_db_version ) ) {
			set_transient( '_rank_math_schema_activation_redirect', 1, 30 );
		}

		// Update to latest version.
		update_option( 'rank_math_schema_version', rank_math_schema()->version );
		update_option( 'rank_math_schema_db_version', rank_math_schema()->db_version );

		// Save install date.
		if ( false == get_option( 'rank_math_schema_install_date' ) ) {
			update_option( 'rank_math_schema_install_date', current_time( 'timestamp' ) );
		}

		// Activate Watcher.
		$watcher = new Watcher;
		$watcher->check_activated_plugin();

		$this->clear_cache();
		$this->do_action( 'activate' );
	}

	/**
	 * Runs on deactivate of the plugin.
	 */
	private function deactivate() {
		$this->clear_cache();
		$this->do_action( 'deactivate' );
	}

	/**
	 * Set up the database tables which the plugin needs to function.
	 */
	private function create_tables() {
		global $wpdb;

		$collate      = $wpdb->get_charset_collate();
		$table_schema = [

			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rank_math_schema (
				id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				sources TEXT NOT NULL,
				url_to TEXT NOT NULL,
				header_code SMALLINT(4) UNSIGNED NOT NULL,
				hits BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
				status VARCHAR(25) NOT NULL DEFAULT 'active',
				created DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				updated DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				last_accessed DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY (id),
				KEY (status)
			) $collate;",

			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rank_math_schema_cache (
				id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				from_url TEXT NOT NULL,
				redirection_id BIGINT(20) UNSIGNED NOT NULL,
				object_id BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
				object_type VARCHAR(10) NOT NULL DEFAULT 'post',
				is_redirected TINYINT(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (id),
				KEY (redirection_id)
			) $collate;",

		];

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		foreach ( $table_schema as $table ) {
			dbDelta( $table );
		}
	}

	/**
	 * Create options.
	 */
	private function create_options() {
	}

	/**
	 * Clears the WP or W3TC cache depending on which is used.
	 */
	private function clear_cache() {
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
		}
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
		}
	}
}
