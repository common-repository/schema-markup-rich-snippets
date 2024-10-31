<?php
/**
 * The Search Class
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

defined( 'ABSPATH' ) || exit;

/**
 * Search_Results class.
 */
class Search_Results implements Snippet {

	/**
	 * Outputs code to allow recognition of the SearchResultsPage.
	 *
	 * @link https://schema.org/SearchResultsPage
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$data['SearchResultsPage'] = [
			'@context' => 'https://schema.org',
			'@type'    => 'SearchResultsPage',
		];

		return $data;
	}
}
