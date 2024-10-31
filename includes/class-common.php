<?php
/**
 * The Global functionality of the plugin.
 *
 * Defines the functionality loaded both on admin and frontend.
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA;

use RANKMATH_SCHEMA\Traits\Ajax;
use RANKMATH_SCHEMA\Traits\Hooker;
use MyThemeShop\Helpers\Arr;
use MyThemeShop\Helpers\Str;

defined( 'ABSPATH' ) || exit;

/**
 * Common class.
 */
class Common {

	use Hooker, Ajax;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->filter( 'rank_math_schema/excluded_taxonomies', 'default_excluded_taxonomies' );
		add_action( 'init', [ '\RANKMATH_SCHEMA\Replace_Vars', 'setup' ], 99 );
	}

	/**
	 * Exclude taxonomies.
	 *
	 * @param  array $taxonomies Excluded taxonomies.
	 * @return array
	 */
	public function default_excluded_taxonomies( $taxonomies ) {
		if ( ! current_theme_supports( 'post-formats' ) ) {
			unset( $taxonomies['post_format'] );
		}
		unset( $taxonomies['product_shipping_class'] );

		return $taxonomies;
	}
}
