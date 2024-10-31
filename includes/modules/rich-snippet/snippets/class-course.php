<?php
/**
 * The Course Class
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
 * Course class.
 */
class Course implements Snippet {

	/**
	 * Course rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$entity = [
			'@context'    => 'https://schema.org',
			'@type'       => 'Course',
			'name'        => $jsonld->parts['title'],
			'description' => $jsonld->parts['desc'],
			'provider'    => [
				'@type'  => 'Organization',
				'name'   => Helper::get_post_meta( 'snippet_course_provider' ),
				'sameAs' => Helper::get_post_meta( 'snippet_course_provider_url' ),
			],
		];

		if ( isset( $data['Organization'] ) ) {
			unset( $data['Organization'] );
		}

		return $entity;
	}
}
