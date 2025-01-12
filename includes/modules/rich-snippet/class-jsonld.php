<?php
/**
 * Outputs schema code specific for Google's JSON LD stuff
 *
 * @since      0.9.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Traits\Hooker;
use MyThemeShop\Helpers\Url;
use MyThemeShop\Helpers\Conditional;

defined( 'ABSPATH' ) || exit;

/**
 * JsonLD class.
 */
class JsonLD {

	use Hooker;

	/**
	 * Hold post object.
	 *
	 * @var WP_Post
	 */
	public $post = null;

	/**
	 * Hold post ID.
	 *
	 * @var ID
	 */
	public $post_id = 0;

	/**
	 * Hold post parts.
	 *
	 * @var array
	 */
	public $parts = [];

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->action( 'rank_math_schema/head', 'json_ld', 90 );
		$this->action( 'rank_math_schema/json_ld', 'add_context_data' );
	}

	/**
	 * JSON LD output function that the functions for specific code can hook into.
	 */
	public function json_ld() {
		global $post;

		if ( is_singular() ) {
			$this->post    = $post;
			$this->post_id = $post->ID;
			$this->get_parts();
		}

		/**
		 * Collect data to output in JSON-LD.
		 *
		 * @param array  $unsigned An array of data to output in json-ld.
		 * @param JsonLD $unsigned JsonLD instance.
		 */
		$data = $this->do_filter( 'json_ld', [], $this );
		if ( is_array( $data ) && ! empty( $data ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( array_values( array_filter( $data ) ) ) . '</script>' . "\n";
		}
	}

	/**
	 * Get Default Schema Data.
	 *
	 * @param array $data Array of json-ld data.
	 *
	 * @return array
	 */
	public function add_context_data( $data ) {
		/**
		 * Allow developer to disable the breadcrumb json-ld output.
		 *
		 * @param bool $unsigned Default: true
		 */
		$is_product_page = ( is_tax() && in_array( get_query_var( 'taxonomy' ), get_object_taxonomies( 'product' ) ) ) || ( function_exists( 'is_shop' ) ) && is_shop();

		$snippets = [
			'\\RANKMATH_SCHEMA\\RichSnippet\\Website'         => is_front_page(),
			'\\RANKMATH_SCHEMA\\RichSnippet\\Search_Results'  => is_search(),
			'\\RANKMATH_SCHEMA\\RichSnippet\\Author'          => is_author(),
			'\\RANKMATH_SCHEMA\\RichSnippet\\Products_Page'   => $is_product_page,
			'\\RANKMATH_SCHEMA\\RichSnippet\\Collection_Page' => ! $is_product_page && ( is_category() || is_tag() || is_tax() ),
			'\\RANKMATH_SCHEMA\\RichSnippet\\Blog'            => is_home(),
			'\\RANKMATH_SCHEMA\\RichSnippet\\Singular'        => is_singular(),
		];

		foreach ( $snippets as $class => $can_run ) {
			if ( $can_run && class_exists( $class ) ) {
				$class = new $class;
				$data  = $class->process( $data, $this );
			}
		}

		return $data;
	}

	/**
	 * Add property to entity.
	 *
	 * @param string $prop   Name of the property to add into entity.
	 * @param array  $entity Array of json-ld entity.
	 */
	public function add_prop( $prop, &$entity ) {
		if ( empty( $prop ) ) {
			return;
		}

		$hash = [
			'email' => [ 'titles.email', 'email' ],
			'image' => [ 'titles.knowledgegraph_logo', 'logo' ],
			'phone' => [ 'titles.phone', 'telephone' ],
		];

		// phpcs:disable
		if ( isset( $hash[ $prop ] ) && $value = Helper::get_settings( $hash[ $prop ][0] ) ) {
			$entity[ $hash[ $prop ][1] ] = $value;
			return;
		}

		if ( 'url' === $prop && $url = Helper::get_settings( 'titles.url' ) ) {
			$entity['url'] = ! Url::is_relative( $url ) ? $url : 'http://' . $url;
			return;
		}

		if ( 'address' === $prop && $address = Helper::get_settings( 'titles.local_address' ) ) {
			$entity['address'] = [ '@type' => 'PostalAddress' ] + $address;
			return;
		}

		// phpcs:enable

		if ( 'thumbnail' === $prop ) {
			$image = Helper::get_thumbnail_with_fallback( get_the_ID(), 'full' );
			if ( ! empty( $image ) ) {
				$entity['image'] = [
					'@type'  => 'ImageObject',
					'url'    => $image[0],
					'width'  => $image[1],
					'height' => $image[2],
				];
			}

			return;
		}
	}

	/**
	 * Get website name with a fallback to bloginfo( 'name' ).
	 *
	 * @return string
	 */
	public function get_website_name() {
		$name = Helper::get_settings( 'titles.knowledgegraph_name' );

		return $name ? $name : get_bloginfo( 'name' );
	}

	/**
	 * Get post parts
	 *
	 * @param array $data Array of json-ld data.
	 *
	 * @return array
	 */
	public function get_post_collection( $data ) {
		$parts = [];

		while ( have_posts() ) {
			the_post();

			$post_id = get_the_ID();
			$schema  = Helper::get_post_meta( 'rich_snippet', $post_id );
			if ( ! $schema ) {
				continue;
			}

			$title = $this->get_post_title( $post_id );
			$url   = $this->get_post_url( $post_id );

			$part = [
				'@type'            => isset( $data['schema'] ) ? $data['schema'] : $schema,
				'headline'         => $title,
				'name'             => $title,
				'url'              => $url,
				'mainEntityOfPage' => $url,
				'dateModified'     => get_post_modified_time( 'Y-m-d\TH:i:sP', true ),
				'datePublished'    => get_post_time( 'Y-m-d\TH:i:sP', true ),
				'author'           => $this->get_author(),
				'publisher'        => $this->get_publisher( $data ),
				'image'            => $this->get_post_thumbnail( $post_id ),
				'keywords'         => $this->get_post_terms( $post_id ),
				'commentCount'     => get_comments_number(),
				'comment'          => $this->get_comments( $post_id ),
			];

			if ( 'article' === $schema ) {
				$part['wordCount'] = str_word_count( get_the_content() );
			}

			$parts[] = $part;
		}

		wp_reset_query();

		return $parts;
	}

	/**
	 * Get publisher
	 *
	 * @param array $data Entity.
	 *
	 * @return array
	 */
	public function get_publisher( $data ) {
		if ( ! isset( $data['Organization'] ) && ! isset( $data['Person'] ) ) {
			return [
				'@type' => 'Organization',
				'name'  => $this->get_website_name(),
				'logo'  => [
					'@type' => 'ImageObject',
					'url'   => Helper::get_settings( 'titles.knowledgegraph_logo' ),
				],
			];
		}

		$entity = [];
		if ( isset( $data['Organization'] ) ) {
			$this->set_publisher( $entity, $data['Organization'] );
			$logo = isset( $entity['publisher']['logo']['url'] ) ? $entity['publisher']['logo']['url'] : '';
		}

		if ( isset( $data['Person'] ) ) {
			$this->set_publisher( $entity, $data['Person'] );
			$logo                        = Helper::get_settings( 'titles.knowledgegraph_logo' );
			$entity['publisher']['logo'] = [
				'@type' => 'ImageObject',
				'url'   => $logo,
			];
		}

		$entity['publisher']['@type'] = 'Organization';

		return $entity['publisher'];
	}

	/**
	 * Get post thumbnail if any
	 *
	 * @param int $post_id  Post id to get featured image  for.
	 *
	 * @return array
	 */
	public function get_post_thumbnail( $post_id = 0 ) {
		if ( ! has_post_thumbnail( $post_id ) ) {
			return false;
		}

		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );

		return [
			'@type'  => 'ImageObject',
			'url'    => $image[0],
			'height' => $image[2],
			'width'  => $image[1],
		];
	}

	/**
	 * Get post terms
	 *
	 * @param int    $post_id  Post id to get terms  for.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return array
	 */
	public function get_post_terms( $post_id = 0, $taxonomy = false ) {
		if ( false === $taxonomy ) {
			$taxonomy = get_queried_object();
			if ( ! is_object( $taxonomy ) ) {
				return [];
			}
			$taxonomy = $taxonomy->taxonomy;
		}

		$terms = wp_get_post_terms( $post_id, $taxonomy, [ 'fields' => 'names' ] );
		return is_wp_error( $terms ) || empty( $terms ) ? [] : $terms;
	}

	/**
	 * Get comments data
	 *
	 * @param int $post_id Post id to get comments for.
	 *
	 * @return array
	 */
	public function get_comments( $post_id = 0 ) {
		$post_comments = get_comments([
			'post_id' => $post_id,
			'number'  => 10,
			'status'  => 'approve',
			'type'    => 'comment',
		]);

		if ( empty( $post_comments ) ) {
			return '';
		}

		$comments = [];
		foreach ( $post_comments as $comment ) {
			$comments[] = [
				'@type'       => 'Comment',
				'dateCreated' => $comment->comment_date,
				'description' => $comment->comment_content,
				'author'      => [
					'@type' => 'Person',
					'name'  => $comment->comment_author,
					'url'   => $comment->comment_author_url,
				],
			];
		}

		return $comments;
	}

	/**
	 * Get author data
	 *
	 * @return array
	 */
	public function get_author() {
		$author = [
			'@type' => 'Person',
			'name'  => get_the_author_meta( 'display_name' ),
			'url'   => esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		];

		if ( get_the_author_meta( 'description' ) ) {
			$author['description'] = get_the_author_meta( 'description' );
		}

		if ( version_compare( get_bloginfo( 'version' ), '4.2', '>=' ) ) {
			$image = get_avatar_url( get_the_author_meta( 'user_email' ), 96 );
			if ( $image ) {
				$author['image'] = [
					'@type'  => 'ImageObject',
					'url'    => $image,
					'height' => 96,
					'width'  => 96,
				];
			}
		}

		return $author;
	}

	/**
	 * Set publisher/provider data for JSON-LD.
	 *
	 * @param array  $entity Array of json-ld entity.
	 * @param array  $organization Organization data.
	 * @param string $type         Type data set to. Default: 'publisher'.
	 */
	public function set_publisher( &$entity, $organization, $type = 'publisher' ) {
		$keys = [ '@context', '@type', 'url', 'name', 'logo', 'image', 'contactPoint', 'sameAs' ];
		foreach ( $keys as $key ) {
			if ( ! isset( $organization[ $key ] ) ) {
				continue;
			}

			$entity[ $type ][ $key ] = 'logo' !== $key ? $organization[ $key ] : [
				'@type' => 'ImageObject',
				'url'   => $organization[ $key ],
			];
		}
	}

	/**
	 * Set address for JSON-LD.
	 *
	 * @param string $schema Schema to get data for.
	 * @param array  $entity Array of json-ld entity to attach data to.
	 */
	public function set_address( $schema, &$entity ) {
		$address = Helper::get_post_meta( "snippet_{$schema}_address" );

		// Early Bail!
		if ( ! is_array( $address ) || empty( $address ) ) {
			return;
		}

		$entity['address'] = [ '@type' => 'PostalAddress' ];
		foreach ( $address as $key => $value ) {
			$entity['address'][ $key ] = $value;
		}
	}

	/**
	 * Set data to entity.
	 *
	 * Loop through post meta value grab data and attache it to the entity.
	 *
	 * @param array $hash   Key to get data and Value to save as.
	 * @param array $entity Array of json-ld entity to attach data to.
	 */
	public function set_data( $hash, &$entity ) {
		foreach ( $hash as $metakey => $dest ) {
			$entity[ $dest ] = Helper::get_post_meta( $metakey, $this->post_id );
		}
	}

	/**
	 * Get post parts.
	 */
	private function get_parts() {
		wp_reset_query();
		$parts = [
			'title'     => $this->get_post_title(),
			'url'       => $this->get_post_url(),
			'canonical' => get_the_permalink(),
			'modified'  => get_post_modified_time( 'Y-m-d\TH:i:sP', true ),
			'published' => get_post_time( 'Y-m-d\TH:i:sP', true ),
			'excerpt'   => wp_strip_all_tags( get_the_excerpt(), true ),
		];

		// Description.
		$desc = Helper::get_post_meta( 'snippet_desc' );
		if ( ! $desc ) {
			$desc = Helper::replace_vars( Helper::get_settings( "titles.pt_{$this->post->post_type}_default_snippet_desc" ), $this->post );
		}
		$parts['desc'] = $desc ? $desc : $parts['excerpt'];

		// Author.
		$author          = Helper::get_post_meta( 'snippet_author' );
		$parts['author'] = $author ? $author : get_the_author_meta( 'display_name', $this->post->post_author );

		$this->parts = $parts;
	}

	/**
	 * Get post title.
	 *
	 * @param  int $post_id Post ID to get title for.
	 * @return string
	 */
	public function get_post_title( $post_id = 0 ) {
		$title = Helper::get_post_meta( 'snippet_name', $post_id );
		if ( ! $title && ! empty( $this->post ) ) {
			$title = Helper::replace_vars( Helper::get_settings( "titles.pt_{$this->post->post_type}_default_snippet_name" ), $this->post );
		}
		return $title ? $title : get_the_title( $post_id );
	}

	/**
	 * Get post url.
	 *
	 * @param  int $post_id Post ID to get url for.
	 * @return string
	 */
	public function get_post_url( $post_id = 0 ) {
		$url = Helper::get_post_meta( 'snippet_url', $post_id );
		return $url ? $url :get_the_permalink( $post_id );
	}

	/**
	 * Get product description.
	 *
	 * @param  int $post_id Post ID to get url for.
	 * @return string
	 */
	public function get_product_desc( $post_id = 0 ) {
		$product = wc_get_product( $post_id );
		if ( empty( $product ) ) {
			return;
		}

		return wp_strip_all_tags( do_shortcode( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ), true );
	}
}
