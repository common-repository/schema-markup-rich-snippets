<?php
/**
 * The conflicting plugin watcher.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Admin;

use RANKMATH_SCHEMA\Runner;
use RANKMATH_SCHEMA\Traits\Hooker;
use RANKMATH_SCHEMA\Helper as GlobalHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Watcher class.
 */
class Watcher implements Runner {

	use Hooker;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'init', 'init' );
		$this->action( 'activated_plugin', 'check_activated_plugin' );
		$this->action( 'deactivated_plugin', 'check_deactivated_plugin' );
	}

	/**
	 * Set/Deactivate conflicting SEO or Sitemap plugins.
	 */
	public function init() {
		if ( \MyThemeShop\Helpers\Param::get( 'rank_math_deactivate_seo_plugins' ) ) {
			$this->deactivate_conflicting_plugins( 'seo' );
			return;
		}
	}

	/**
	 * Function to run when new plugin is activated.
	 */
	public function check_activated_plugin() {
		$set     = [];
		$plugins = get_option( 'active_plugins', array() );
		foreach ( $this->get_conflicting_plugins() as $plugin => $type ) {
			if ( ! isset( $set[ $type ] ) && in_array( $plugin, $plugins ) ) {
				$set[ $type ] = true;
				$this->set_notification( $type );
			}
		}
	}

	/**
	 * Function to run when plugin is deactivated.
	 *
	 * @param string $plugin Deactivated plugin path.
	 */
	public function check_deactivated_plugin( $plugin ) {
		$plugins = $this->get_conflicting_plugins();
		if ( ! isset( $plugins[ $plugin ] ) ) {
			return;
		}
		$this->remove_notification( $plugins[ $plugin ], $plugin );
	}

	/**
	 * Deactivate conflicting plugins.
	 *
	 * @param string $type Plugin type.
	 */
	private function deactivate_conflicting_plugins( $type ) {
		foreach ( $this->get_conflicting_plugins() as $plugin => $plugin_type ) {
			if ( $type === $plugin_type && is_plugin_active( $plugin ) ) {
				deactivate_plugins( $plugin );
			}
		}

		wp_redirect( remove_query_arg( "rank_math_deactivate_{$type}_plugins" ) );
	}

	/**
	 * Function to set conflict plugin notification.
	 *
	 * @param string $type Plugin type.
	 */
	private function set_notification( $type ) {
		$message = sprintf(
			/* translators: deactivation link */
			esc_html__( 'Please keep only one SEO plugin active, otherwise, you might lose your rankings and traffic. %s.', 'schema-markup' ),
			'<a href="' . add_query_arg( 'rank_math_deactivate_seo_plugins', '1', admin_url( 'plugins.php' ) ) . '">Click here to Deactivate</a>'
		);

		GlobalHelper::add_notification( $message, [
			'id'   => "conflicting_{$type}_plugins",
			'type' => 'error',
		] );
	}

	/**
	 * Function to remove conflict plugin notification.
	 *
	 * @param string $type   Plugin type.
	 * @param string $plugin Plugin name.
	 */
	private function remove_notification( $type, $plugin ) {
		foreach ( $this->get_conflicting_plugins() as $file => $plugin_type ) {
			if ( $plugin !== $file && $type === $plugin_type && is_plugin_active( $file ) ) {
				return;
			}
		}

		GlobalHelper::remove_notification( "conflicting_{$type}_plugins" );
	}

	/**
	 * Function to get all conflicting plugins.
	 *
	 * @return array
	 */
	private function get_conflicting_plugins() {

		$plugins = [
			'wp-schema-pro/wp-schema-pro.php'              => 'seo',
			'all-in-one-schemaorg-rich-snippets/index.php' => 'seo',
		];

		return $plugins;
	}
}
