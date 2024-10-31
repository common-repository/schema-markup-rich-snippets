<?php
/**
 * Helper Functions.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA;

use RANKMATH_SCHEMA\Helpers\Api;
use RANKMATH_SCHEMA\Helpers\Conditional;
use RANKMATH_SCHEMA\Helpers\Options;
use RANKMATH_SCHEMA\Helpers\WordPress;
use RANKMATH_SCHEMA\Helpers\Choices;
use RANKMATH_SCHEMA\Helpers\Post_Type;
use RANKMATH_SCHEMA\Helpers\Taxonomy;

defined( 'ABSPATH' ) || exit;

/**
 * Helper class.
 */
class Helper {

	use Conditional, Options, WordPress, Choices, Post_Type, Taxonomy;

	/**
	 * Replace `%variable_placeholders%` with their real value based on the current requested page/post/cpt.
	 *
	 * @param  string $content The string to replace the variables in.
	 * @param  array  $args    The object some of the replacement values might come from, could be a post, taxonomy or term.
	 * @param  array  $omit    Variables that should not be replaced by this function.
	 * @return string
	 */
	public static function replace_vars( $content, $args = array(), $omit = array() ) {
		$replacer = new Replace_Vars();

		return $replacer->replace( $content, $args, $omit );
	}

	/**
	 * Add notification.
	 *
	 * @param string $message Message string.
	 * @param array  $options Set of options.
	 */
	public static function add_notification( $message, $options = [] ) {
		rank_math_schema()->notification->add( $message, $options );
	}

	/**
	 * Add notification.
	 *
	 * @param string $notification_id Notification id.
	 */
	public static function remove_notification( $notification_id ) {
		rank_math_schema()->notification->remove_by_id( $notification_id );
	}

	/**
	 * Get Setting.
	 *
	 * @param  string $field_id The field id to get value for.
	 * @param  mixed  $default  The default value if no field found.
	 * @return mixed
	 */
	public static function get_settings( $field_id = '', $default = false ) {
		return rank_math_schema()->settings->get( $field_id, $default );
	}

	/**
	 * Add something to JSON object.
	 *
	 * @param string $key         Unique identifier.
	 * @param mixed  $value       The data itself can be either a single or an array.
	 * @param string $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
	 */
	public static function add_json( $key, $value, $object_name = 'rankMath' ) {
		rank_math_schema()->json->add( $key, $value, $object_name );
	}

	/**
	 * Remove something from JSON object.
	 *
	 * @param string $key         Unique identifier.
	 * @param string $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
	 */
	public static function remove_json( $key, $object_name = 'rankMath' ) {
		rank_math_schema()->json->remove( $key, $object_name );
	}

	/**
	 * Get module by id.
	 *
	 * @param  string $id ID to get module.
	 * @return object Module class object.
	 */
	public static function get_module( $id ) {
		return rank_math_schema()->manager->get_module( $id );
	}

	/**
	 * Modify module status.
	 *
	 * @param string $modules Modules to modify.
	 */
	public static function update_modules( $modules ) {
		$stored = get_option( 'rank_math_modules' );

		foreach ( $modules as $module => $action ) {
			if ( 'off' === $action ) {
				if ( in_array( $module, $stored ) ) {
					$stored = array_diff( $stored, array( $module ) );
				}
				continue;
			}

			$stored[] = $module;
		}

		update_option( 'rank_math_modules', array_unique( $stored ) );
	}
}
