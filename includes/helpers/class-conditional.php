<?php
/**
 * The Conditional helpers.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Helpers
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Helpers;

use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Admin\Admin_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Conditional class.
 */
trait Conditional {

	/**
	 * Check if whitelabel filter is active.
	 *
	 * @return boolean
	 */
	public static function is_whitelabel() {
		return apply_filters( 'rank_math/whitelabel', false );
	}

	/**
	 * Checks if the WP-REST-API is available.
	 *
	 * @param  string $minimum_version The minimum version the API should be.
	 * @return bool Returns true if the API is available.
	 */
	public static function is_api_available( $minimum_version = '2.0' ) {
		return ( defined( 'REST_API_VERSION' ) && version_compare( REST_API_VERSION, $minimum_version, '>=' ) );
	}

	/**
	 * Checks if 404-monitor plugin is active.
	 *
	 * @return bool Returns true if the API is available.
	 */
	public static function is_404_monitor_active() {
		return defined( 'RANK_MATH_MONITOR_FILE' );
	}

	/**
	 * Checks if Redirections plugin is active.
	 *
	 * @return bool Returns true if the API is available.
	 */
	public static function is_redirections_active() {
		return defined( 'RANK_MATH_REDIRECTIONS_FILE' );
	}
}
