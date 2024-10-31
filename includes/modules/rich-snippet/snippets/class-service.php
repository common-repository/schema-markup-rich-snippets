<?php
/**
 * The Service Class
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
 * Service class.
 */
class Service implements Snippet {

	/**
	 * Service rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$price  = Helper::get_post_meta( 'snippet_service_price' );
		$entity = [
			'@context'        => 'https://schema.org',
			'@type'           => 'Service',
			'name'            => $jsonld->parts['title'],
			'description'     => $jsonld->parts['desc'],
			'serviceType'     => Helper::get_post_meta( 'snippet_service_type' ),
			'offers'          => [
				'@type'         => 'Offer',
				'price'         => $price ? $price : '0',
				'priceCurrency' => Helper::get_post_meta( 'snippet_service_price_currency' ),
			],
			'aggregateRating' => [
				'@type'       => 'AggregateRating',
				'ratingValue' => Helper::get_post_meta( 'snippet_service_rating_value' ),
				'ratingCount' => Helper::get_post_meta( 'snippet_service_rating_count' ),
			],
		];

		return $entity;
	}
}
