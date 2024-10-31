<?php
/**
 * The WordPress helpers.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Helpers
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Helpers;

use RANKMATH_SCHEMA\Post;
use RANKMATH_SCHEMA\Helper;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\WordPress as WP_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * WordPress class.
 */
trait WordPress {

	/**
	 * Get admin url.
	 *
	 * @param  string $page Page id.
	 * @param  array  $args Pass arguments to query string.
	 * @return string
	 */
	public static function get_admin_url( $page = '', $args = array() ) {
		$page = $page ? 'rank-math-' . $page : 'rank-math-schema';
		$args = wp_parse_args( $args, array( 'page' => $page ) );

		return add_query_arg( $args, admin_url( 'admin.php' ) );
	}

	/**
	 * Check if plugin is network active
	 *
	 * @codeCoverageIgnore
	 *
	 * @return boolean
	 */
	public static function is_plugin_active_for_network() {
		if ( ! is_multisite() ) {
			return false;
		}

		// Makes sure the plugin is defined before trying to use it.
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( ! is_plugin_active_for_network( plugin_basename( RANKMATH_SCHEMA_FILE ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Gets post type label.
	 *
	 * @param  string $post_type Post type name.
	 * @param  bool   $singular  Get singular label.
	 * @return string|false
	 */
	public static function get_post_type_label( $post_type, $singular = false ) {
		$object = get_post_type_object( $post_type );
		if ( ! $object ) {
			return false;
		}
		return ! $singular ? $object->labels->name : $object->labels->singular_name;
	}

	/**
	 * Get post meta value.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param  string  $key     Internal key of the value to get (without prefix).
	 * @param  integer $post_id Post ID of the post to get the value for.
	 * @return mixed
	 */
	public static function get_post_meta( $key, $post_id = 0 ) {
		return Post::get_meta( $key, $post_id );
	}

	/**
	 * Get post thumbnail with fallback as
	 *     1. Post thumbnail.
	 *     2. First image in content.
	 *     3. Facebook image if any
	 *     4. Twitter image if any.
	 *     5. Default open graph image set in option panel.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param  int|WP_Post  $post_id Post ID or WP_Post object.
	 * @param  string|array $size    Image size. Accepts any valid image size, or an array of width and height values in pixels.
	 * @return false|array Returns an array (url, width, height, is_intermediate), or false, if no image is available.
	 */
	public static function get_thumbnail_with_fallback( $post_id, $size = 'thumbnail' ) {
		if ( has_post_thumbnail( $post_id ) ) {
			return wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
		}

		preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches );
		$matches = array_filter( $matches );
		if ( ! empty( $matches ) ) {
			return [ $matches[1][0], 200, 200 ];
		}

		return false;
	}

	/**
	 * Helper function to validate & format ISO 8601 duration.
	 *
	 * @param  string $iso8601 Duration which need to be converted to seconds.
	 * @return string
	 */
	public static function get_formatted_duration( $iso8601 ) {
		$end = substr( $iso8601, -1 );
		if ( ! in_array( $end, [ 'D', 'H', 'M', 'S' ] ) ) {
			return '';
		}

		// The format starts with the letter P, for "period".
		return ( ! Str::starts_with( 'P', $iso8601 ) ) ? 'PT' . $iso8601 : $iso8601;
	}
}
