<?php
/**
 * The <head> tag.
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\Frontend
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA;

use RANKMATH_SCHEMA\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Head class.
 */
class Head {

	use Hooker;

	/**
	 * Hold generate instance.
	 *
	 * @var Generate
	 */
	public $generate = null;

	/**
	 * The Constructor.
	 */
	public function __construct() {

		$this->action( 'wp_head', 'head', 1 );
		$this->filter( 'language_attributes', 'search_results_schema' );
	}

	/**
	 * Add Search Result Page schema.
	 *
	 * @param  string $output A space-separated list of language attributes.
	 * @return string
	 */
	public function search_results_schema( $output ) {
		if ( ! is_search() ) {
			return $output;
		}

		return preg_replace( '/itemtype="([^"]+)"/', 'itemtype="https://schema.org/SearchResultsPage', $output );
	}

	/**
	 * Main wrapper function attached to wp_head.
	 * This combines all the output on the frontend of the plugin.
	 */
	public function head() {
		global $wp_query;

		$old_wp_query = null;
		if ( ! $wp_query->is_main_query() ) {
			$old_wp_query = $wp_query;
			wp_reset_query();
		}

		$this->credits();

		/**
		 * Allow other plugins to output inside the Rank Math section of the head tag.
		 */
		$this->do_action( 'head' );

		$this->credits( true );

		if ( ! empty( $old_wp_query ) ) {
			$GLOBALS['wp_query'] = $old_wp_query;
			unset( $old_wp_query );
		}
	}

	/**
	 * Credits
	 *
	 * @param boolean $closing Is closing credits needed.
	 */
	private function credits( $closing = false ) {

		if ( $this->do_filter( 'frontend/remove_credit_notice', false ) ) {
			return;
		}

		if ( false === $closing ) {
			if ( ! Helper::is_whitelabel() ) {
				echo "\n<!-- " . esc_html__( 'Search Engine Optimization by Rank Math - https://s.rankmath.com/home', 'schema-markup' ) . " -->\n";
			}
			return;
		}

		if ( ! Helper::is_whitelabel() ) {
			echo '<!-- /' . esc_html__( 'Rank Math WordPress SEO plugin', 'schema-markup' ) . " -->\n\n";
		}
	}
}
