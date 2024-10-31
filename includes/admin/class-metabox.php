<?php
/**
 * The metabox functionality of the plugin.
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Admin;

use CMB2_hookup;
use RANKMATH_SCHEMA\CMB2;
use RANKMATH_SCHEMA\Runner;
use RANKMATH_SCHEMA\Replace_Vars;
use RANKMATH_SCHEMA\Traits\Hooker;
use RANKMATH_SCHEMA\Helper;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Url;
use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

/**
 * Metabox class.
 */
class Metabox implements Runner {

	use Hooker;

	/**
	 * Metabox id.
	 *
	 * @var string
	 */
	private $metabox_id = 'rank_math_metabox';

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'admin_enqueue_scripts', 'enqueue' );
		$this->action( 'cmb2_admin_init', 'add_main_metabox', 30 );
		$this->action( 'cmb2_' . CMB2::current_object_type() . '_process_fields_' . $this->metabox_id, 'save_meta' );
	}

	/**
	 * Enqueue Styles and Scripts required for metabox.
	 */
	public function enqueue() {
		// Early bail out if is not the valid screen or if it's WPBakery's Frontend editor.
		$screen = get_current_screen();
		if ( ! in_array( $screen->base, array( 'post', 'term', 'profile', 'user-edit' ) ) || ( class_exists( 'Vc_Manager' ) && Param::get( 'vc_action' ) ) ) {
			return;
		}

		// Styles.
		CMB2_hookup::enqueue_cmb_css();
		Replace_Vars::setup_json();
		wp_enqueue_style( 'rank-math-metabox', rank_math_schema()->plugin_url() . '/assets/admin/css/metabox.css', array( 'rank-math-schema-common', 'rank-math-cmb2' ), rank_math_schema()->version );

		// JSON data.
		Helper::add_json( 'locale', substr( get_locale(), 0, 2 ) );
		Helper::add_json( 'overlayImages', Helper::choices_overlay_images() );
		Helper::add_json( 'customPermalinks', (bool) get_option( 'permalink_structure', false ) );
		Helper::add_json( 'defautOgImage', Helper::get_settings( 'titles.open_graph_image', '' ) );
		Helper::add_json( 'postSettings', array(
			'linkSuggestions' => Helper::get_settings( 'titles.pt_' . $screen->post_type . '_link_suggestions' ),
			'useFocusKeyword' => 'focus_keywords' === Helper::get_settings( 'titles.pt_' . $screen->post_type . '_ls_use_fk' ),
		) );

		$js = rank_math_schema()->plugin_url() . 'assets/admin/js/';

		if ( Admin_Helper::is_post_edit() ) {
			global $post;
			Helper::add_json( 'objectID', $post->ID );
			wp_enqueue_script( 'rank-math-post-metabox', $js . 'post-metabox.js', array( 'rank-math-schema-common' ), rank_math_schema()->version, true );
		}

	}

	/**
	 * Add main metabox.
	 */
	public function add_main_metabox() {

		$cmb = new_cmb2_box( array(
			'id'               => $this->metabox_id,
			'title'            => esc_html__( 'Rank Math SEO', 'schema-markup' ),
			'object_types'     => $this->get_object_types(),
			'taxonomies'       => Helper::get_allowed_taxonomies(),
			'new_term_section' => false,
			'new_user_section' => 'add-existing-user',
			'context'          => 'normal',
			'priority'         => $this->get_priority(),
			'cmb_styles'       => false,
			'classes'          => 'rank-math-metabox-wrap' . ( Admin_Helper::is_term_profile_page() ? ' rank-math-metabox-frame' : '' ),
		) );

		$tabs = $this->get_tabs();
		$cmb->add_field( array(
			'id'   => 'setting-panel-container-' . $this->metabox_id,
			'type' => 'meta_tab_container_open',
			'tabs' => $tabs,
		) );

		foreach ( $tabs as $id => $tab ) {

			$cmb->add_field( array(
				'id'   => 'setting-panel-' . $id,
				'type' => 'tab_open',
			) );

			include_once $tab['file'];

			/**
			 * Add setting into specific tab of main metabox.
			 *
			 * The dynamic part of the hook name. $id, is the tab id.
			 *
			 * @param CMB2 $cmb CMB2 object.
			 */
			$this->do_action( 'metabox/settings/' . $id, $cmb );

			$cmb->add_field( array(
				'id'   => 'setting-panel-' . $id . '-close',
				'type' => 'tab_close',
			) );
		}

		$cmb->add_field( array(
			'id'   => 'setting-panel-container-close-' . $this->metabox_id,
			'type' => 'tab_container_close',
		) );

		CMB2::pre_init( $cmb );
	}

	/**
	 * Save post meta handler.
	 *
	 * @param  CMB2 $cmb CMB2 metabox object.
	 */
	public function save_meta( $cmb ) {
		/**
		 * Hook into save handler for main metabox.
		 *
		 * @param CMB2 $cmb CMB2 object.
		 */
		$this->do_action( 'metabox/process_fields', $cmb );
	}

	/**
	 * Get object types to register metabox to
	 *
	 * @return array
	 */
	private function get_object_types() {
		$taxonomies   = Helper::get_allowed_taxonomies();
		$object_types = Helper::get_allowed_post_types();

		if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
			$object_types[] = 'term';
			$this->description_field_editor();
			remove_filter( 'pre_term_description', 'wp_filter_kses' );
			remove_filter( 'term_description', 'wp_kses_data' );
		}

		if ( $this->is_user_metabox() ) {
			$object_types[] = 'user';
		}

		return $object_types;
	}

	/**
	 * Get metabox priority
	 *
	 * @return string
	 */
	private function get_priority() {
		$post_type = Param::get(
			'post_type',
			get_post_type( Param::get( 'post', 0, FILTER_VALIDATE_INT ) )
		);
		$priority = 'product' === $post_type ? 'default' : 'high';

		return $this->do_filter( 'metabox/priority', $priority );
	}

	/**
	 * Adds custom category description editor.
	 *
	 * @return {void}
	 */
	private function description_field_editor() {
		$taxonomy        = filter_input( INPUT_GET, 'taxonomy', FILTER_DEFAULT, array( 'options' => array( 'default' => '' ) ) );
		$taxonomy_object = get_taxonomy( $taxonomy );
		if ( empty( $taxonomy_object ) || empty( $taxonomy_object->public ) ) {
			return;
		}

		if ( ! Helper::get_settings( 'titles.tax_' . $taxonomy . '_add_meta_box' ) ) {
			return;
		}
	}

	/**
	 * Is user metabox enabled.
	 *
	 * @return bool
	 */
	private function is_user_metabox() {
		return ( false === Helper::get_settings( 'titles.disable_author_archives' ) && Helper::get_settings( 'titles.author_add_meta_box' ) );
	}

	/**
	 * Get tabs.
	 *
	 * @return array
	 */
	private function get_tabs() {

		/**
		 * Allow developers to add new tabs into main metabox.
		 *
		 * @param array $tabs Array of tabs.
		 */
		return $this->do_filter( 'metabox/tabs', [] );
	}
}
