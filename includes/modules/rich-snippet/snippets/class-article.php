<?php
/**
 * The Article Class
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
 * Article class.
 */
class Article implements Snippet {

	/**
	 * Article rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		if ( ! $type = Helper::get_post_meta( 'snippet_article_type' ) ) { // phpcs:ignore
			$type = Helper::get_settings( "titles.pt_{$jsonld->post->post_type}_default_article_type" );
		}

		$entity = [
			'@context'         => 'https://schema.org',
			'@type'            => $type,
			'headline'         => $jsonld->parts['title'],
			'description'      => $jsonld->parts['desc'],
			'datePublished'    => $jsonld->parts['published'],
			'dateModified'     => $jsonld->parts['modified'],
			'publisher'        => $jsonld->get_publisher( $data ),
			'mainEntityOfPage' => [
				'@type' => 'WebPage',
				'@id'   => $jsonld->parts['canonical'],
			],
			'author'           => [
				'@type' => 'Person',
				'name'  => $jsonld->parts['author'],
			],
		];

		if ( isset( $data['Organization'] ) ) {
			unset( $data['Organization'] );
		}

		return $entity;
	}
}
