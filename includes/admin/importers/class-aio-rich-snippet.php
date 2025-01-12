<?php
/**
 * The AIO Rich Snippet Import Class
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\Admin\Importers
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Admin\Importers;

use RANKMATH_SCHEMA\Admin\Admin_Helper;
use MyThemeShop\Helpers\DB;

defined( 'ABSPATH' ) || exit;

/**
 * Import_AIO_Rich_Snippet class.
 */
class AIO_Rich_Snippet extends Plugin_Importer {

	/**
	 * The plugin name.
	 *
	 * @var string
	 */
	protected $plugin_name = 'AIO Schema Rich Snippet';

	/**
	 * Meta key, used in SQL LIKE clause for delete query.
	 *
	 * @var string
	 */
	protected $meta_key = '_bsf_post_type';

	/**
	 * Array of option keys to import and clean
	 *
	 * @var array
	 */
	protected $option_keys = array( 'bsf_', 'bsf_%' );

	/**
	 * Array of choices keys to import
	 *
	 * @var array
	 */
	protected $choices = array( 'postmeta' );

	/**
	 * Returns array of choices of action which can be performed for plugin
	 *
	 * @return array
	 */
	public function get_choices() {
		return array(
			'postmeta' => esc_html__( 'Import Rich Snippets', 'schema-markup' ) . Admin_Helper::get_tooltip( esc_html__( 'Import all Schema data for Posts, Pages, and custom post types.', 'schema-markup' ) ),
		);
	}

	/**
	 * Import post meta of plugin.
	 *
	 * @return array
	 */
	protected function postmeta() {
		$this->set_pagination( $this->get_post_ids( true ) );
		$snippet_posts = $this->get_post_ids();

		foreach ( $snippet_posts as $snippet_post ) {
			$type      = $this->is_allowed_type( $snippet_post->meta_value );
			$meta_keys = $this->get_metakeys( $type );
			if ( false === $type || false === $meta_keys ) {
				continue;
			}

			$this->set_postmeta( $snippet_post->post_id, $type, $meta_keys );
			update_post_meta( $snippet_post->post_id, 'rank_math_rich_snippet', $type );
		}

		return $this->get_pagination_arg();
	}

	/**
	 * Get all post ids of all allowed post types only.
	 *
	 * @param bool $count If we need count only for pagination purposes.
	 * @return int|array
	 */
	protected function get_post_ids( $count = false ) {
		$paged = $this->get_pagination_arg( 'page' );
		$table = DB::query_builder( 'postmeta' )->where( 'meta_key', '_bsf_post_type' );

		return $count ? absint( $table->selectCount( 'meta_id' )->getVar() ) :
			$table->page( $paged - 1, $this->items_per_page )->get();
	}

	/**
	 * Get snippet types.
	 *
	 * @return array
	 */
	private function get_types() {
		return [
			'1'  => 'review',
			'2'  => 'event',
			'5'  => 'person',
			'6'  => 'product',
			'7'  => 'recipe',
			'8'  => 'software',
			'9'  => 'video',
			'10' => 'article',
			'11' => 'service',
		];
	}

	/**
	 * Is snippet type allowed.
	 *
	 * @param string $type Type to check.
	 *
	 * @return bool
	 */
	private function is_allowed_type( $type ) {
		$types = $this->get_types();
		return isset( $types[ $type ] ) ? $types[ $type ] : false;
	}

	/**
	 * Set snippet meta.
	 *
	 * @param int    $post_id   Post id.
	 * @param string $type      Current snippet type.
	 * @param array  $meta_keys Array of meta keys to save.
	 */
	private function set_postmeta( $post_id, $type, $meta_keys ) {
		foreach ( $meta_keys as $snippet_key => $snippet_value ) {
			$value = get_post_meta( $post_id, '_bsf_' . $snippet_key, true );
			$value = in_array( $snippet_key, [ 'event_start_date', 'event_end_date' ] ) ? strtotime( $value ) : $value;
			if ( $this->has_address( $type, $snippet_key ) ) {
				$address[ $snippet_value ] = $value;
				$value                     = $address;
				$snippet_value             = "{$type}_address";
			}

			update_post_meta( $post_id, 'rank_math_snippet_' . $snippet_value, $value );
		}
	}

	/**
	 * Check if the snippet has address field.
	 *
	 * @param string $type        Snippet type.
	 * @param string $snippet_key Snippet meta key.
	 *
	 * @return bool
	 */
	private function has_address( $type, $snippet_key ) {
		$event_array  = [ 'event_organization', 'event_street', 'event_local', 'event_region', 'event_postal_code' ];
		$person_array = [ 'people_street', 'people_local', 'people_local', 'people_region', 'people_postal' ];

		return ( 'event' === $type && in_array( $snippet_key, $event_array, true ) ) ||
			( 'person' === $type && in_array( $snippet_key, $person_array, true ) );
	}

	/**
	 * Get meta keys hash to import.
	 *
	 * @param string $type Type to get keys for.
	 *
	 * @return array
	 */
	private function get_metakeys( $type ) {
		$hash = [
			'review'   => [
				'item_reviewer' => 'name',
				'item_name'     => 'desc',
				'rating'        => 'review_rating_value',
			],
			'article'  => [
				'article_name' => 'name',
				'article_desc' => 'desc',
			],
			'event'    => [
				'event_title'        => 'name',
				'event_organization' => 'addressCountry',
				'event_street'       => 'streetAddress',
				'event_local'        => 'addressLocality',
				'event_region'       => 'addressRegion',
				'event_postal_code'  => 'postalCode',
				'event_desc'         => 'desc',
				'event_start_date'   => 'event_startdate',
				'event_end_date'     => 'event_enddate',
				'event_price'        => 'event_price',
				'event_cur'          => 'event_currency',
				'event_ticket_url'   => 'event_ticketurl',
			],
			'person'   => [
				'people_fn'        => 'name',
				'people_nickname'  => 'desc',
				'people_photo'     => 'name',
				'people_job_title' => 'job_title',
				'people_street'    => 'streetAddress',
				'people_local'     => 'addressLocality',
				'people_region'    => 'addressRegion',
				'people_postal'    => 'postalCode',
			],
			'product'  => [
				'product_brand'  => 'product_brand',
				'product_name'   => 'name',
				'product_price'  => 'product_price',
				'product_cur'    => 'product_currency',
			],
			'recipe'   => [
				'recipes_name'       => 'name',
				'recipes_preptime'   => 'recipe_preptime',
				'recipes_cooktime'   => 'recipe_cooktime',
				'recipes_totaltime'  => 'recipe_totaltime',
				'recipes_desc'       => 'desc',
				'recipes_ingredient' => 'recipe_ingredients',
			],
			'software' => [
				'software_rating' => 'software_rating_value',
				'software_price'  => 'software_price',
				'software_cur'    => 'software_price_currency',
				'software_name'   => 'name',
				'software_os'     => 'software_operating_system',
				'software_cat'    => 'software_application_category',
			],
			'video'    => [
				'video_title'    => 'name',
				'video_desc'     => 'desc',
				'video_thumb'    => 'rank_math_twitter_title',
				'video_url'      => 'video_url',
				'video_emb_url'  => 'video_embed_url',
				'video_duration' => 'video_duration',
			],
			'service'  => [
				'service_type' => 'service_type',
				'service_desc' => 'desc',
			],
		];

		return isset( $hash[ $type ] ) ? $hash[ $type ] : false;
	}
}
