<?php
/**
 * The option center of the plugin.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Admin;

use RANKMATH_SCHEMA\CMB2;
use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Runner;
use RANKMATH_SCHEMA\Traits\Hooker;
use MyThemeShop\Helpers\Arr;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Option_Center class.
 */
class Option_Center implements Runner {

	use Hooker;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'init', 'register_title_settings', 125 );
		$this->filter( 'rank_math_schema/settings/title', 'title_post_type_settings', 1 );
		$this->filter( 'rank_math_schema/settings/title', 'title_taxonomy_settings', 1 );
	}

	/**
	 * Register SEO Titles & Meta Settings.
	 */
	public function register_title_settings() {
		$tabs = [
			'local'    => [
				'icon'  => 'fa fa-map-marker',
				'title' => esc_html__( 'Local SEO', 'schema-markup' ),
				/* translators: Redirection page url */
				'desc'  => sprintf( wp_kses_post( __( 'This tab contains settings related to contact information & opening hours of your local business. Use the <code>[rank_math_contact_info]</code> shortcode to display contact information in a nicely formatted way. %s. You should also claim your business on Google if you have not already.', 'schema-markup' ) ), '<a href="https://rankmath.com/kb/titles-and-meta/#local-seo" target="_blank">' . esc_html__( 'Learn more', 'schema-markup' ) . '</a>' ),
			],
			'social'   => [
				'icon'  => 'fa fa-retweet',
				'title' => esc_html__( 'Social Meta', 'schema-markup' ),
				/* translators: Link to social setting KB article */
				'desc'  => sprintf( esc_html__( 'This tab contains settings related to social networks and feeds. Social page URLs will be displayed in the contact shortcode and added to the pages as metadata to be displayed in Knowledge Graph cards. Unable to find the details? %s for the tutorial.', 'schema-markup' ), '<a href="https://rankmath.com/kb/titles-and-meta/#social-meta" target="_blank">' . esc_html__( 'Click here', 'schema-markup' ) . '</a>' ),
			],
		];

		/**
		 * Allow developers to add new section into title setting option panel.
		 *
		 * @param array $tabs
		 */
		$tabs = $this->do_filter( 'settings/title', $tabs );

		new Options([
			'key'        => 'rank-math-options-titles',
			'title'      => esc_html__( 'Rich Snippets', 'schema-markup' ),
			'menu_title' => esc_html__( 'Rich Snippets', 'schema-markup' ),
			'capability' => 'manage_options',
			'folder'     => 'titles',
			'tabs'       => $tabs,
		]);

		if ( is_admin() ) {
			Helper::add_json( 'postTitle', 'Post Title' );
			Helper::add_json( 'postUri', home_url( '/post-title' ) );
			Helper::add_json( 'blogName', get_bloginfo( 'name' ) );
		}
	}

	/**
	 * Add post type tabs into title option panel
	 *
	 * @param  array $tabs Hold tabs for optional panel.
	 * @return array
	 */
	public function title_post_type_settings( $tabs ) {
		$icons = Helper::choices_post_type_icons();
		$links = [
			'post'       => '<a href="https://rankmath.com/kb/titles-and-meta/#Posts" target="_blank">' . esc_html__( 'Learn more', 'schema-markup' ) . '</a>.',
			'page'       => '<a href="https://rankmath.com/kb/titles-and-meta/#pages" target="_blank">' . esc_html__( 'Learn more', 'schema-markup' ) . '</a>.',
			'product'    => '<a href="https://rankmath.com/kb/titles-and-meta/#products" target="_blank">' . esc_html__( 'Learn more', 'schema-markup' ) . '</a>.',
			'attachment' => '<a href="https://rankmath.com/kb/titles-and-meta/#media" target="_blank">' . esc_html__( 'Learn more', 'schema-markup' ) . '</a>.',
		];

		$names = [
			'post'       => 'single %s',
			'page'       => 'single %s',
			'product'    => 'product pages',
			'attachment' => 'media %s',
		];

		$tabs['p_types'] = [
			'title' => esc_html__( 'Post Types:', 'schema-markup' ),
			'type'  => 'seprator',
		];

		foreach ( Helper::get_accessible_post_types() as $post_type ) {
			$obj      = get_post_type_object( $post_type );
			$link     = isset( $links[ $obj->name ] ) ? $links[ $obj->name ] : '';
			$obj_name = isset( $names[ $obj->name ] ) ? sprintf( $names[ $obj->name ], $obj->name ) : $obj->name;

			$tabs[ 'post-type-' . $obj->name ] = [
				'title'     => $obj->label,
				'icon'      => isset( $icons[ $obj->name ] ) ? $icons[ $obj->name ] : $icons['default'],
				/* translators: 1. post type name 2. link */
				'desc'      => sprintf( esc_html__( 'This tab contains SEO options for %1$s. %2$s', 'schema-markup' ), $obj_name, $link ),
				'post_type' => $obj->name,
				'file'      => rank_math_schema()->includes_dir() . 'settings/titles/post-types.php',
			];
		}

		return $tabs;
	}

	/**
	 * Add taxonomy tabs into title option panel
	 *
	 * @param  array $tabs Hold tabs for optional panel.
	 * @return array
	 */
	public function title_taxonomy_settings( $tabs ) {
		$icons = Helper::choices_taxonomy_icons();

		$hash_name = [
			'category'    => 'category archive pages',
			'product_cat' => 'Product category pages',
			'product_tag' => 'Product tag pages',
		];

		$hash_link = [
			'category'    => '<a href="https://rankmath.com/kb/titles-and-meta/#categories" target="_blank">' . esc_html__( 'Learn more', 'schema-markup' ) . '</a>.',
			'post_tag'    => '<a href="https://rankmath.com/kb/titles-and-meta/#tags" target="_blank">' . esc_html__( 'Learn more', 'schema-markup' ) . '</a>.',
			'product_cat' => '<a href="https://rankmath.com/kb/titles-and-meta/#product-categories" target="_blank">' . esc_html__( 'Learn more', 'schema-markup' ) . '</a>.',
			'product_tag' => '<a href="https://rankmath.com/kb/titles-and-meta/#product-tags" target="_blank">' . esc_html__( 'Learn more', 'schema-markup' ) . '</a>.',
		];

		foreach ( Helper::get_accessible_taxonomies() as $taxonomy ) {
			$attached = implode( ' + ', $taxonomy->object_type );

			// Seprator.
			$tabs[ $attached ] = [
				'title' => ucwords( $attached ) . ':',
				'type'  => 'seprator',
			];

			$link          = isset( $hash_link[ $taxonomy->name ] ) ? $hash_link[ $taxonomy->name ] : '';
			$taxonomy_name = isset( $hash_name[ $taxonomy->name ] ) ? $hash_name[ $taxonomy->name ] : $taxonomy->label;

			$tabs[ 'taxonomy-' . $taxonomy->name ] = [
				'icon'     => isset( $icons[ $taxonomy->name ] ) ? $icons[ $taxonomy->name ] : $icons['default'],
				'title'    => $taxonomy->label,
				/* translators: 1. taxonomy name 2. link */
				'desc'     => sprintf( esc_html__( 'This tab contains SEO options for %1$s. %2$s', 'schema-markup' ), $taxonomy_name, $link ),
				'taxonomy' => $taxonomy->name,
				'file'     => rank_math_schema()->includes_dir() . 'settings/titles/taxonomies.php',
			];
		}

		if ( isset( $tabs['taxonomy-post_format'] ) ) {
			$tab = $tabs['taxonomy-post_format'];
			unset( $tabs['taxonomy-post_format'] );
			$tab['title']      = esc_html__( 'Post Formats', 'schema-markup' );
			$tab['page_title'] = esc_html__( 'Post Formats Archive', 'schema-markup' );
			Arr::insert( $tabs, [ 'taxonomy-post_format' => $tab ], 5 );
		}

		return $tabs;
	}
}
