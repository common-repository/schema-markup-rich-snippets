<?php
/**
 * The Edd Product Class
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

use EDD_Download;
use RANKMATH_SCHEMA\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Product_Edd class.
 */
class Product_Edd {

	/**
	 * Set product data for rich snippet.
	 *
	 * @param array  $entity Array of json-ld entity.
	 * @param JsonLD $jsonld JsonLD Instance.
	 */
	public function set_product( &$entity, $jsonld ) {
		$product_id = get_the_ID();
		$permalink  = get_permalink();
		$product    = new EDD_Download( $product_id );

		$entity['url']         = $permalink;
		$entity['name']        = $jsonld->post->post_title;
		$entity['description'] = strip_shortcodes( $jsonld->post->post_content );
		$entity['category']    = Product::get_category( $product_id, 'download_category' );

		// Sku.
		if ( $product->get_sku() ) {
			$entity['sku'] = $product->get_sku();
		}

		// Offers.
		$seller     = Product::get_seller( $jsonld );
		$variations = $this->has_variations( $product );
		if ( false !== $variations ) {
			$entity['offers'] = [];
			foreach ( $variations as $variation ) {
				$offer = [
					'@type'         => 'Offer',
					'description'   => $variation['name'],
					'price'         => $variation['amount'],
					'priceCurrency' => edd_get_currency(),
					'seller'        => $seller,
					'url'           => $permalink,
				];

				$entity['offers'][] = $offer;
			}

			return;
		}

		// Single offer.
		$entity['offers'] = [
			'@type'         => 'Offer',
			'price'         => $product->get_price() ? $product->get_price() : '0',
			'priceCurrency' => edd_get_currency(),
			'seller'        => $seller,
			'url'           => $permalink,
		];
	}

	/**
	 * If product is variable send variations.
	 *
	 * @param  EDD_Download $product Current product.
	 *
	 * @return array|boolean
	 */
	private function has_variations( $product ) {
		if ( ! $product->has_variable_prices() ) {
			return false;
		}

		$variations = $product->get_prices();
		return ! empty( $variations ) ? $variations : false;
	}
}
