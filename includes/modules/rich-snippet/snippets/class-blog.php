<?php
/**
 * The Blog Class
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

defined( 'ABSPATH' ) || exit;

/**
 * Blog class.
 */
class Blog implements Snippet {

	/**
	 * Outputs code to allow recognition of the Blog.
	 *
	 * @link https://schema.org/Blog
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$is_front       = is_front_page() && is_home() || is_front_page();
		$data['schema'] = 'BlogPosting';
		$data['Blog']   = [
			'@context'    => 'https://schema.org/',
			'@type'       => 'Blog',
			'url'         => $is_front ? home_url() : get_permalink( get_option( 'page_for_posts' ) ),
			'headline'    => $is_front ? $jsonld->get_website_name() : get_the_title( get_option( 'page_for_posts' ) ),
			'description' => get_bloginfo( 'description' ),
			'blogPost'    => $jsonld->get_post_collection( $data ),
		];

		return $data;
	}
}
