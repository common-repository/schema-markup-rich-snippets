<?php
/**
 * The Restaurant Class
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
 * Restaurant class.
 */
class Restaurant implements Snippet {

	/**
	 * Restaurant rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$local  = new Local;
		$entity = $local->process( $data, $jsonld );

		$entity['@type']       = 'Restaurant';
		$entity['description'] = $jsonld->parts['desc'];
		$entity['hasMenu']     = Helper::get_post_meta( 'snippet_restaurant_menu' );

		$serves_cuisine = trim( Helper::get_post_meta( 'snippet_restaurant_serves_cuisine' ) );
		if ( $serves_cuisine ) {
			$entity['servesCuisine'] = explode( ',', $serves_cuisine );
		}

		return $entity;
	}
}
