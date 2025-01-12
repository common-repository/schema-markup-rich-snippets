<?php
/**
 * Dashboard page template.
 *
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Admin
 */

use RANKMATH_SCHEMA\Admin\Admin_Helper;
use RANKMATH_SCHEMA\Admin\System_Info;

$is_network_admin  = is_network_admin();
$is_network_active = RANKMATH_SCHEMA\Helper::is_plugin_active_for_network();
$current_tab       = $is_network_active && $is_network_admin ? 'help' : ( isset( $_GET['view'] ) ? filter_input( INPUT_GET, 'view' ) : 'modules' );
?>
<div class="wrap rank-math-wrap limit-wrap">

	<span class="wp-header-end"></span>

	<h1><?php esc_html_e( 'Welcome to Rank Math!', 'schema-markup' ); ?></h1>

	<div class="rank-math-text">
		<?php esc_html_e( 'The most complete WordPress SEO plugin to convert your website into a traffic generating machine.', 'schema-markup' ); ?>
	</div>


	<?php
	if ( ! ( $is_network_active && $is_network_admin ) ) {
		rank_math_schema()->admin->display_dashboard_nav();
	}
	?>

	<?php
	if ( $is_network_active && ! $is_network_admin && 'help' === $current_tab ) {
		return;
	}

	// phpcs:disable
	// Display modules activation and deactivation form.
	if ( 'modules' === $current_tab ) {
		rank_math_schema()->manager->display_form();
	}
	// phpcs:enable
	?>
</div>
