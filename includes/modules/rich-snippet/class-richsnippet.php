<?php
/**
 * The Rich Snippet Module
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

use RANKMATH_SCHEMA\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * RichSnippet class.
 */
class RichSnippet {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {

		if ( is_admin() ) {
			new Admin;
		}
		$this->action( 'wp', 'integrations' );
		$this->filter( 'rank_math/help/tabs', 'help_tabs', 11 );

		new Snippet_Shortcode;
	}

	/**
	 * Add help tab into help page.
	 *
	 * @param array $tabs Array of tabs.
	 * @return array
	 */
	public function help_tabs( $tabs ) {
		$tabs['rich-snippet'] = [
			'title' => esc_html__( 'Rich Snippet', 'schema-markup' ),
			'view'  => dirname( __FILE__ ) . '/views/help.php',
		];

		return $tabs;
	}

	/**
	 * Initialize integrations.
	 */
	public function integrations() {
		$type = get_query_var( 'sitemap' );
		if ( ! empty( $type ) ) {
			return;
		}

		new JsonLD;
	}
}
