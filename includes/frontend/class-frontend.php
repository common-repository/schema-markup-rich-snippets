<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\Frontend
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA;

use RANKMATH_SCHEMA\Traits\Hooker;
use RANKMATH_SCHEMA\Frontend\Shortcodes;

defined( 'ABSPATH' ) || exit;

/**
 * Frontend class.
 */
class Frontend {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->includes();
		$this->hooks();

		/**
		 * Fires when frontend is included/loaded.
		 */
		$this->do_action( 'frontend/loaded' );
	}

	/**
	 * Include required files.
	 */
	private function includes() {

		rank_math_schema()->shortcodes = new Shortcodes;

	}

	/**
	 * Hook into actions and filters.
	 */
	private function hooks() {
		add_filter( 'wpseo_json_ld_output', '__return_false' );
		$this->action( 'template_redirect', 'integrations' );
	}

	/**
	 * Initialize integrations.
	 */
	public function integrations() {
		rank_math_schema()->head = new Head;
	}
}
