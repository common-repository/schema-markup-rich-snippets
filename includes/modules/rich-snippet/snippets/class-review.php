<?php
/**
 * The Review Class
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Review class.
 */
class Review implements Snippet {

	use Hooker;

	/**
	 * Review rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$entity = [
			'@context'      => 'https://schema.org',
			'@type'         => 'Review',
			'author'        => [
				'@type' => 'Person',
				'name'  => $jsonld->parts['author'],
			],
			'datePublished' => $jsonld->parts['published'],
			'description'   => $jsonld->parts['desc'],
			'itemReviewed'  => [
				'@type' => 'Thing',
				'name'  => $jsonld->parts['title'],
			],
			'reviewRating'  => [
				'@type'       => 'Rating',
				'worstRating' => Helper::get_post_meta( 'snippet_review_worst_rating' ),
				'bestRating'  => Helper::get_post_meta( 'snippet_review_best_rating' ),
				'ratingValue' => Helper::get_post_meta( 'snippet_review_rating_value' ),
			],
		];

		$jsonld->add_prop( 'thumbnail', $entity['itemReviewed'] );

		$this->filter( 'the_content', 'add_review_to_content', 11 );

		return $entity;
	}

	/**
	 * Injects reviews to content.
	 *
	 * @param  string $content Post content.
	 * @return string
	 *
	 */
	public function add_review_to_content( $content ) {
		global $multipage, $numpages, $page;

		$location = $this->can_add_content();
		if ( false === $location ) {
			return $content;
		}

		$review = do_shortcode( '[rank_math_rich_snippet]' );

		if ( 'top' === $location || 'both' === $location ) {
			$content = $review . $content;
		}

		if ( ( 'bottom' === $location || 'both' === $location ) && ( ! $multipage || $page === $numpages ) ) {
			$content .= $review;
		}

		return $content;
	}

	/**
	 * Can add content.
	 *
	 * @return boolean|string
	 */
	private function can_add_content() {
		if ( ! is_main_query() || ! in_the_loop() ) {
			return false;
		}

		/**
		 * Filter: Allow disabling the review display.
		 *
		 * @param bool $return True to disable.
		 */
		if ( true === $this->do_filter( 'snippet/review/hide_data', false ) ) {
			return false;
		}

		$location = $this->do_filter( 'snippet/review/location', Helper::get_post_meta( 'snippet_review_location' ) );
		$location = $location ? $location : 'bottom';

		if ( 'custom' === $location ) {
			return false;
		}

		return $location;
	}
}
