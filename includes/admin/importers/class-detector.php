<?php
/**
 * The functionality to detect whether we should import from another SEO plugin
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\Admin\Importers
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Admin\Importers;

use RANKMATH_SCHEMA\Traits\Hooker;
use RANKMATH_SCHEMA\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Detector class.
 */
class Detector {

	use Hooker;

	/**
	 * Plugins we can import from
	 *
	 * @var array
	 */
	public static $plugins = null;

	/**
	 * Class constructor
	 */
	public function __construct() {
		if ( Helper::is_redirections_active() ) {
			$this->filter( 'rank_math/importers/detect_plugins', 'get_redirections_plugin' );
		}
	}

	/**
	 * Detects whether we can import anything
	 */
	public function detect() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! is_null( self::$plugins ) ) {
			return self::$plugins;
		}
		self::$plugins = [];

		$plugins = $this->get();
		foreach ( $plugins as $slug => $plugin ) {
			if ( $this->is_plugin_detectable( $plugin, $plugins ) ) {
				continue;
			}

			$importer = new $plugin['class']( $plugin['file'] );
			if ( $this->run( $importer, 'detect' ) ) {
				self::$plugins[ $slug ] = [
					'name'    => $importer->get_plugin_name(),
					'file'    => $importer->get_plugin_file(),
					'choices' => $importer->get_choices(),
				];
			}
		}

		return self::$plugins;
	}

	/**
	 * Check if plugin is detectable.
	 *
	 * @param array $check   Plugin to check.
	 * @param array $plugins Plugins data.
	 *
	 * @return bool
	 */
	private function is_plugin_detectable( $check, $plugins ) {
		// Check if parent is set.
		if ( isset( $check['parent'] ) && isset( self::$plugins[ $check['parent'] ] ) ) {
			return true;
		}

		// Check if plugin has premium and it is active.
		if ( isset( $check['premium'] ) && is_plugin_active( $plugins[ $check['premium'] ]['file'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Detects active plugins
	 *
	 * @return array
	 */
	public function active_plugins() {
		$plugins = [];
		if ( is_null( self::$plugins ) ) {
			foreach ( $this->get() as $slug => $data ) {
				if ( is_plugin_active( $data['file'] ) ) {
					$plugins[ $slug ] = true;
				}
			}
		}

		return $plugins;
	}

	/**
	 * Run action by slug.
	 *
	 * @param string $slug    The importer slug that needs to perform this action.
	 * @param string $action  The action to perform.
	 * @param string $perform The action to perform when running import action.
	 */
	public static function run_by_slug( $slug, $action, $perform = '' ) {
		$detector  = new self;
		$importers = $detector->get();
		if ( ! isset( $importers[ $slug ] ) ) {
			return false;
		}

		$importer = $importers[ $slug ];
		$importer = new $importer['class']( $importer['file'] );
		$status   = $detector->run( $importer, $action, $perform );

		return \compact( 'importer', 'status' );
	}

	/**
	 * Run import class.
	 *
	 * @param Plugin_Importer $importer The importer that needs to perform this action.
	 * @param string          $action   The action to perform.
	 * @param string          $perform  The action to perform when running import action.
	 */
	public function run( $importer, $action = 'detect', $perform = '' ) {
		if ( 'cleanup' === $action ) {
			return $importer->run_cleanup();
		} elseif ( 'import' === $action ) {
			return $importer->run_import( $perform );
		}

		return $importer->run_detect();
	}

	/**
	 * Returns an array of importers available
	 *
	 * @return array Available importers
	 */
	public function get() {
		$plugins = apply_filters( 'rank_math/importers/detect_plugins', [
			'aio-rich-snippet' => [
				'class' => '\\RANKMATH_SCHEMA\\Admin\\Importers\\AIO_Rich_Snippet',
				'file'  => 'all-in-one-schemaorg-rich-snippets/index.php',
			],
			'wp-schema-pro'    => [
				'class' => '\\RANKMATH_SCHEMA\\Admin\\Importers\\WP_Schema_Pro',
				'file'  => 'wp-schema-pro/wp-schema-pro.php',
			],
		]);

		return $plugins;
	}

	/**
	 * Returns an array of importers available
	 * 
	 * @param array Available importers
	 *
	 * @return array Available importers
	 */
	public function get_redirections_plugin( $plugins ) {
		$redirections = array(
			'yoast'            => array(
				'class'   => '\\RankMath_Redirections\\Admin\\Importers\\Yoast',
				'file'    => 'wordpress-seo/wp-seo.php',
				'premium' => 'yoast-premium',
			),
			'yoast-premium'    => array(
				'class'  => '\\RankMath_Redirections\\Admin\\Importers\\Yoast',
				'file'   => 'wordpress-seo-premium/wp-seo-premium.php',
				'parent' => 'yoast',
			),
			'redirections'     => array(
				'class' => '\\RankMath_Redirections\\Admin\\Importers\\Redirections',
				'file'  => 'redirection/redirection.php',
			),
		);

		$plugins = array_merge( $plugins, $redirections );

		return $plugins;
	}
}
