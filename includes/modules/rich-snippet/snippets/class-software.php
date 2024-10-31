<?php
/**
 * The Software Class
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
 * Software class.
 */
class Software implements Snippet {

	/**
	 * Software rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$price  = Helper::get_post_meta( 'snippet_software_price' );
		$entity = [
			'@context'            => 'https://schema.org',
			'@type'               => 'SoftwareApplication',
			'name'                => $jsonld->parts['title'],
			'description'         => $jsonld->parts['desc'],
			'operatingSystem'     => Helper::get_post_meta( 'snippet_software_operating_system' ),
			'applicationCategory' => Helper::get_post_meta( 'snippet_software_application_category' ),
			'offers'              => [
				'@type'         => 'Offer',
				'price'         => $price ? $price : '0',
				'priceCurrency' => Helper::get_post_meta( 'snippet_software_price_currency' ),
			],
			'aggregateRating'     => [
				'@type'       => 'AggregateRating',
				'ratingValue' => Helper::get_post_meta( 'snippet_software_rating_value' ),
				'ratingCount' => Helper::get_post_meta( 'snippet_software_rating_count' ),
			],
		];

		return $entity;
	}
}
