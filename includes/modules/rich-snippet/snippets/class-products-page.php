<?php
/**
 * The Products Page Class
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

use RANKMATH_SCHEMA\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Products_Page class.
 */
class Products_Page implements Snippet {

	/**
	 * Outputs code to allow recognition of the CollectionPage.
	 *
	 * @link https://schema.org/CollectionPage
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$queried_object = get_queried_object();

		/**
		 * Allow developer to remove snippet data.
		 *
		 * @param bool $unsigned Default: false
		 * @param string $unsigned Taxonomy Name
		 */
		if ( ! is_shop() && ( true === Helper::get_settings( 'titles.remove_' . $queried_object->taxonomy . '_snippet_data' ) || true === apply_filters( 'rank_math/snippet/remove_taxonomy_data', false, $queried_object->taxonomy ) ) ) {
			return $data;
		}

		/**
		 * Allow developer to remove snippet data from Shop page.
		 *
		 * @param bool $unsigned Default: false
		 */
		if ( is_shop() && ( true === Helper::get_settings( 'general.remove_shop_snippet_data' ) || true === apply_filters( 'rank_math/snippet/remove_shop_data', false ) ) ) {
			return $data;
		}

		$data['ProductsPage'] = [
			'@context' => 'https://schema.org/',
			'@graph'   => [],
		];

		while ( have_posts() ) {
			the_post();

			$post_id = get_the_ID();
			$url     = $jsonld->get_post_url( $post_id );

			$part = [
				'@type'       => 'Product',
				'name'        => $jsonld->get_post_title( $post_id ),
				'url'         => $url,
				'@id'         => $url,
				'description' => $jsonld->get_product_desc( $post_id ),
			];

			$data['ProductsPage']['@graph'][] = $part;
		}

		wp_reset_query();

		return $data;
	}
}
