<?php
/**
 * The admin engine of the plugin.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Admin;

use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Traits\Hooker;
use MyThemeShop\Helpers\Conditional;
use RANKMATH_SCHEMA\Search_Console\Search_Console;

defined( 'ABSPATH' ) || exit;

/**
 * Engine class.
 *
 * @codeCoverageIgnore
 */
class Engine {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {

		rank_math_schema()->admin        = new Admin;
		rank_math_schema()->admin_assets = new Assets;

		$runners = array(
			rank_math_schema()->admin,
			rank_math_schema()->admin_assets,
			new Admin_Menu,
			new Option_Center,
			new Metabox,
			new Import_Export,
			new CMB2_Fields,
			new Deactivate_Survey,
			new Watcher,
		);

		foreach ( $runners as $runner ) {
			$runner->hooks();
		}

		/**
		 * Fires when admin is loaded.
		 */
		$this->do_action( 'admin/loaded' );
	}
}
