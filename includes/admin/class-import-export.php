<?php
/**
 * The Import Export Class
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Admin;

use RANKMATH_SCHEMA\Runner;
use RANKMATH_SCHEMA\Traits\Ajax;
use RANKMATH_SCHEMA\Traits\Hooker;
use RANKMATH_SCHEMA\Helper as GlobalHelper;
use RANKMATH_SCHEMA\Admin\Importers\Detector;
use MyThemeShop\Admin\Page;
use MyThemeShop\Helpers\Param;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Import_Export class.
 */
class Import_Export implements Runner {

	use Hooker, Ajax;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'init', 'register_page', 1 );
		$this->ajax( 'import_plugin', 'import_plugin' );
		$this->ajax( 'clean_plugin', 'clean_plugin' );
	}

	/**
	 * Register admin pages for plugin.
	 */
	public function register_page() {
		$uri = rank_math_schema()->plugin_url() . 'assets/admin/';
		new Page( 'rank-math-import-export', esc_html__( 'Import &amp; Export', 'schema-markup' ), array(
			'position' => 99,
			'parent'   => 'rank-math-schema',
			'render'   => Admin_Helper::get_view( 'import-export/main' ),
			'onsave'   => array( $this, 'handler' ),
			'classes'  => array( 'rank-math-page' ),
			'assets'   => array(
				'styles'  => array(
					'cmb2-styles'      => '',
					'rank-math-schema-common' => '',
					'rank-math-cmb2'   => '',
				),
				'scripts' => array( 'rank-math-import-export' => $uri . 'js/import-export.js' ),
			),
		));

		GlobalHelper::add_json( 'importConfirm', esc_html__( 'Are you sure you want to import data into Schema Marup?', 'schema-markup' ) );
	}

	/**
	 * Handle import or export.
	 */
	public function handler() {
		if ( ! isset( $_POST['object_id'] ) ) {
			return;
		}

		$object_id = Param::post( 'object_id' );
		if ( 'export-plz' === $object_id ) {
			$this->export();
		}

		if ( isset( $_FILES['import-me'] ) && 'import-plz' === $object_id ) {
			$this->import();
		}
	}

	/**
	 * Handles AJAX plugin run import.
	 */
	public function import_plugin() {
		$this->verify_nonce( 'rank-math-schema-ajax-nonce' );

		$perform = Param::post( 'perform' );
		try {
			$result = Detector::run_by_slug( Param::post( 'pluginSlug' ), 'import', $perform );
			$this->success( $result );
		} catch ( \Exception $e ) {
			$this->error( $e->getMessage() );
		}

	}

	/**
	 * Handles AJAX plugin run clean.
	 */
	public function clean_plugin() {

		$this->verify_nonce( 'rank-math-schema-ajax-nonce' );

		$result = Detector::run_by_slug( Param::post( 'pluginSlug' ), 'cleanup' );

		if ( $result['status'] ) {
			/* translators: Plugin name */
			$this->success( sprintf( esc_html__( 'Cleanup of %s data successfully done.', 'schema-markup' ), $result['importer']->get_plugin_name() ) );
		}

		/* translators: Plugin name */
		$this->error( sprintf( esc_html__( 'Cleanup of %s data failed.', 'schema-markup' ), $result['importer']->get_plugin_name() ) );
	}

	/**
	 * Handle export.
	 */
	private function export() {
		if ( empty( $_POST['panels'] ) ) {
			return;
		}

		$data     = $this->get_export_data( $_POST['panels'] );
		$filename = 'schema-markup-settings-' . date( 'Y-m-d-H-i-s' ) . '.json';

		header( 'Content-Type: application/txt' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		echo wp_json_encode( $data );
		exit;
	}

	/**
	 * Handle import.
	 */
	private function import() {

		// Handle file upload.
		$file = wp_handle_upload( $_FILES['import-me'], array( 'mimes' => array( 'json' => 'application/json' ) ) );
		if ( is_wp_error( $file ) ) {
			GlobalHelper::add_notification( esc_html__( 'Settings could not be imported:', 'schema-markup' ) . ' ' . $file->get_error_message(), [ 'type' => 'error' ] );
			return false;
		}

		if ( is_array( $file ) && isset( $file['error'] ) ) {
			GlobalHelper::add_notification( esc_html__( 'Settings could not be imported:', 'schema-markup' ) . ' ' . $file['error'], [ 'type' => 'error' ] );
			return false;
		}

		if ( ! isset( $file['file'] ) ) {
			GlobalHelper::add_notification( esc_html__( 'Settings could not be imported:', 'schema-markup' ) . ' ' . esc_html__( 'Upload failed.', 'schema-markup' ), [ 'type' => 'error' ] );
			return false;
		}

		// Parse Options.
		$wp_filesystem = WordPress::get_filesystem();
		$settings      = $wp_filesystem->get_contents( $file['file'] );
		$settings      = json_decode( $settings, true );

		\unlink( $file['file'] );

		if ( $this->do_import_data( $settings ) ) {
			GlobalHelper::add_notification( esc_html__( 'Settings successfully imported.', 'schema-markup' ), 'success' );
			return;
		}

		GlobalHelper::add_notification( esc_html__( 'No settings found to be imported.', 'schema-markup' ), [ 'type' => 'info' ] );
	}

	/**
	 * Does import data.
	 *
	 * @param  array $data           Import data.
	 * @param  bool  $suppress_hooks Suppress hooks or not.
	 * @return bool
	 */
	private function do_import_data( array $data, $suppress_hooks = false ) {
		$down = false;
		$hash = array(
			'titles'  => 'rank-math-options-titles',
		);

		$this->run_import_hooks( 'pre_import', $data, $suppress_hooks );

		foreach ( $hash as $key => $option_key ) {
			if ( isset( $data[ $key ] ) && ! empty( $data[ $key ] ) ) {
				$down = true;
				update_option( $option_key, $data[ $key ] );
			}
		}

		$this->run_import_hooks( 'after_import', $data, $suppress_hooks );

		return $down;
	}

	/**
	 * Run import hooks
	 *
	 * @param string $hook     Hook to fire.
	 * @param array  $data     Import data.
	 * @param bool   $suppress Suppress hooks or not.
	 */
	private function run_import_hooks( $hook, $data, $suppress ) {
		if ( ! $suppress ) {
			/**
			 * Fires while importing settings.
			 *
			 * @param array $data Import data.
			 */
			$this->do_action( 'importers/settings/' . $hook, $data );
		}
	}

	/**
	 * Gets export data.
	 *
	 * @param array $panels Which panels do you want to export. It will export all panels if this param is empty.
	 * @return array
	 */
	private function get_export_data( array $panels = array() ) {
		if ( ! $panels ) {
			$panels = [ 'titles' ];
		}

		$settings = rank_math_schema()->settings->all_raw();

		foreach ( $panels as $panel ) {
			if ( isset( $settings[ $panel ] ) ) {
				$data[ $panel ] = $settings[ $panel ];
			}
		}

		return $data;
	}
}