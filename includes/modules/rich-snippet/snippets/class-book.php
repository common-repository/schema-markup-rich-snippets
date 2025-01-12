<?php
/**
 * The Book Class
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
 * Book class.
 */
class Book implements Snippet {

	/**
	 * Book rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$entity = [
			'@context' => 'https://schema.org',
			'@type'    => 'Book',
			'name'     => $jsonld->parts['title'],
			'author'   => [
				'@type' => 'Person',
				'name'  => $jsonld->parts['author'],
			],
			'url'      => $jsonld->parts['url'],
			'hasPart'  => [],
		];

		foreach ( $this->get_editions() as $edition ) {
			$entity['hasPart'][] = $this->get_work_entity( $edition );
		}

		return $entity;
	}

	/**
	 * Get work entity.
	 *
	 * @param  array $edition Edition data.
	 * @return array
	 */
	private function get_work_entity( $edition ) {
		$work = [
			'@type'         => 'Book',
			'bookEdition'   => $edition['book_edition'],
			'bookFormat'    => 'https://schema.org/' . $edition['book_format'],
			'datePublished' => $edition['date_published'],
		];

		$fields = [ 'isbn', 'name', 'author', 'url' ];
		foreach ( $fields as $field ) {
			if ( ! empty( $edition[ $field ] ) ) {
				$work[ $field ] = $edition[ $field ];
			}
		}

		if ( ! empty( $edition['url'] ) ) {
			$work['@id'] = $edition['url'];
		}

		return $work;
	}

	/**
	 * Get book editions.
	 *
	 * @return array
	 */
	private function get_editions() {
		$editions = (array) Helper::get_post_meta( 'snippet_book_editions' );
		$editions = array_filter( $editions, [ $this, 'filter_edition' ], ARRAY_FILTER_USE_BOTH );
		return array_reverse( $editions );
	}

	/**
	 * Filter edition.
	 *
	 * @param  array $edition Array of edition.
	 * @param  mixed $key     Key of edition.
	 * @return boolean
	 */
	protected function filter_edition( $edition, $key ) {
		return ! ( empty( $edition ) || empty( $edition['name'] ) );
	}
}
