<?php
/**
 * Admin helper Functions.
 *
 * This file contains functions need during the admin screens.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Admin;

use RANKMATH_SCHEMA\Helper as GlobalHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Admin_Helper class.
 */
class Admin_Helper {

	/**
	 * Get tooltip html.
	 *
	 * @param  string $message Message to show in tooltip.
	 * @return string
	 */
	public static function get_tooltip( $message ) {
		return '<span class="rank-math-tooltip"><em class="dashicons-before dashicons-editor-help"></em><span>' . $message . '</span></span>';
	}

	/**
	 * Get admin view file.
	 *
	 * @param  string $view View filename.
	 * @return string Complete path to view
	 */
	public static function get_view( $view ) {
		return rank_math_schema()->admin_dir() . "views/{$view}.php";
	}

	/**
	 * Check if current page is post create/edit screen.
	 *
	 * @return bool
	 */
	public static function is_post_edit() {
		global $pagenow;

		return in_array( $pagenow, [ 'post.php', 'post-new.php' ] );
	}

	/**
	 * Check if current page is user or term create/edit screen.
	 *
	 * @return bool
	 */
	public static function is_term_profile_page() {
		global $pagenow;

		return in_array( $pagenow, [ 'term.php', 'profile.php', 'user-edit.php' ] );
	}

	/**
	 * Check if current page is term create/edit screen.
	 *
	 * @return bool
	 */
	public static function is_term_edit() {
		global $pagenow;
		return ( 'term.php' === $pagenow );
	}
}
