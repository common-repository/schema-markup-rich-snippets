<?php
/**
 * The Singular Class
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Traits\Hooker;
use MyThemeShop\Helpers\Conditional;

defined( 'ABSPATH' ) || exit;

/**
 * Singular class.
 */
class Singular implements Snippet {

	use Hooker;

	/**
	 * Generate rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$schema = $this->can_add_schema( $jsonld );
		if ( false === $schema ) {
			return $data;
		}

		$hook = 'snippet/rich_snippet_' . $schema;
		/**
		 * Short-circuit if 3rd party is interested generating his own data.
		 */
		$pre = $this->do_filter( $hook, false, $jsonld->parts, $data );
		if ( false !== $pre ) {
			$data['richSnippet'] = $this->do_filter( $hook . '_entity', $pre );
			return $data;
		}

		$object = $this->get_schema_class( $schema );
		if ( false === $object ) {
			return $data;
		}

		$entity = $object->process( $data, $jsonld );

		// Images.
		$jsonld->add_prop( 'thumbnail', $entity );
		if ( ! empty( $entity['image'] ) && 'video' === $schema ) {
			$entity['thumbnailUrl'] = $entity['image']['url'];
			unset( $entity['image'] );
		}

		$data['richSnippet'] = $this->do_filter( $hook . '_entity', $entity );

		return $data;
	}

	/**
	 * Can add schema.
	 *
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return boolean|string
	 */
	private function can_add_schema( $jsonld ) {
		$schema = Helper::get_post_meta( 'rich_snippet' );

		if (
			! $schema &&
			! metadata_exists( 'post', $jsonld->post_id, 'rank_math_rich_snippet' ) &&
			$schema = Helper::get_settings( "titles.pt_{$jsonld->post->post_type}_default_rich_snippet" ) // phpcs:ignore
		) {
			$schema = Conditional::is_woocommerce_active() && is_product() ? $schema : ( 'article' === $schema ? $schema : '' );
		}

		return $schema;
	}

	/**
	 * Get Schema Class.
	 *
	 * @param string $schema Schema type.
	 * @return bool|Class
	 */
	private function get_schema_class( $schema ) {
		$data = [
			'article'    => '\\RANKMATH_SCHEMA\\RichSnippet\\Article',
			'book'       => '\\RANKMATH_SCHEMA\\RichSnippet\\Book',
			'course'     => '\\RANKMATH_SCHEMA\\RichSnippet\\Course',
			'event'      => '\\RANKMATH_SCHEMA\\RichSnippet\\Event',
			'jobposting' => '\\RANKMATH_SCHEMA\\RichSnippet\\JobPosting',
			'music'      => '\\RANKMATH_SCHEMA\\RichSnippet\\Music',
			'recipe'     => '\\RANKMATH_SCHEMA\\RichSnippet\\Recipe',
			'restaurant' => '\\RANKMATH_SCHEMA\\RichSnippet\\Restaurant',
			'video'      => '\\RANKMATH_SCHEMA\\RichSnippet\\Video',
			'person'     => '\\RANKMATH_SCHEMA\\RichSnippet\\Person',
			'review'     => '\\RANKMATH_SCHEMA\\RichSnippet\\Review',
			'service'    => '\\RANKMATH_SCHEMA\\RichSnippet\\Service',
			'software'   => '\\RANKMATH_SCHEMA\\RichSnippet\\Software',
			'product'    => '\\RANKMATH_SCHEMA\\RichSnippet\\Product',
		];

		if ( isset( $data[ $schema ] ) && class_exists( $data[ $schema ] ) ) {
			return new $data[ $schema ];
		}

		return false;
	}
}
