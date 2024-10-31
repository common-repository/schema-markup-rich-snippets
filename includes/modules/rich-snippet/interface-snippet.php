<?php
/**
 * The Snippet Interface
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

defined( 'ABSPATH' ) || exit;

/**
 * Snippet interface.
 */
interface Snippet {

	/**
	 * Process snippet data
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld Instance of JsonLD.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld );
}
