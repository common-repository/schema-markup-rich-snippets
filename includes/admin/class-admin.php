<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Admin;

use RANKMATH_SCHEMA\Runner;
use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Traits\Hooker;
use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 *
 * @codeCoverageIgnore
 */
class Admin implements Runner {

	use Hooker;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'admin_footer', 'rank_math_modal' );
	}

	/**
	 * Display dashabord tabs.
	 */
	public function display_dashboard_nav() {
		?>
		<h2 class="nav-tab-wrapper">
			<?php
			foreach ( $this->get_nav_links() as $id => $link ) :
				if ( isset( $link['cap'] ) && ! current_user_can( $link['cap'] ) ) {
					continue;
				}
				?>
			<a class="nav-tab<?php echo Param::get( 'view', 'modules' ) === $id ? ' nav-tab-active' : ''; ?>" href="<?php echo esc_url( Helper::get_admin_url( $link['url'], $link['args'] ) ); ?>" title="<?php echo $link['title']; ?>"><?php echo $link['title']; ?></a>
			<?php endforeach; ?>
		</h2>
		<?php
	}

	/**
	 * Get dashbaord navigation links
	 *
	 * @return array
	 */
	private function get_nav_links() {
		$links = [
			'modules'       => [
				'url'   => '',
				'args'  => 'view=modules',
				'cap'   => 'manage_options',
				'title' => esc_html__( 'Modules', 'schema-markup' ),
			],
			'help'          => [
				'url'   => 'help',
				'args'  => '',
				'cap'   => 'manage_options',
				'title' => esc_html__( 'Help', 'schema-markup' ),
			],
			'import-export' => [
				'url'   => 'import-export',
				'args'  => '',
				'cap'   => 'manage_options',
				'title' => esc_html__( 'Import &amp; Export', 'schema-markup' ),
			],
		];

		if ( Helper::is_plugin_active_for_network() ) {
			unset( $links['help'] );
		}

		return $links;
	}

	/**
	 * Activate Rank Math Modal.
	 */
	public function rank_math_modal() {
		$screen = get_current_screen();

		// Early Bail!
		if ( 'toplevel_page_rank-math-schema' !== $screen->id ) {
			return;
		}

		if ( file_exists( WP_PLUGIN_DIR . '/seo-by-rank-math' ) ) {
			$text         = __( 'Activate Now', 'schema-markup' );
			$path         = 'seo-by-rank-math/rank-math.php';
			$link         = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $path ), 'activate-plugin_' . $path );
			$button_class = 'activate-now';
		} else {
			$text         = __( 'Install for Free', 'schema-markup' );
			$link         = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=seo-by-rank-math' ), 'install-plugin_seo-by-rank-math' );
			$button_class = 'install-now';
		}

		// Scripts.
		rank_math_schema()->admin_assets->enqueue_style( 'plugin-modal' );
		rank_math_schema()->admin_assets->enqueue_script( 'schema-markup-plugin-modal' );

		?>
		<div class="rank-math-feedback-modal rank-math-ui try-rankmath-panel" id="rank-math-schema-feedback-form">
			<div class="rank-math-feedback-content">

				<div class="plugin-card plugin-card-seo-by-rank-math">
					<span class="button-close dashicons dashicons-no-alt alignright"></span>
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a href="https://rankmath.com/wordpress/plugin/seo-suite/" target="_blank">
								<?php esc_html_e( 'WordPress SEO Plugin – Rank Math', 'schema-markup' ); ?>
								<img src="https://ps.w.org/seo-by-rank-math/assets/icon.svg" class="plugin-icon" alt="<?php esc_html_e( 'Rank Math SEO', 'schema-markup' ); ?>">
								</a>
								<span class="vers column-rating">
									<a href="https://wordpress.org/support/plugin/seo-by-rank-math/reviews/" target="_blank">
										<div class="star-rating">
											<div class="star star-full" aria-hidden="true"></div>
											<div class="star star-full" aria-hidden="true"></div>
											<div class="star star-full" aria-hidden="true"></div>
											<div class="star star-full" aria-hidden="true"></div>
											<div class="star star-full" aria-hidden="true"></div>
										</div>
										<span class="num-ratings" aria-hidden="true">(268)</span>
									</a>
								</span>
							</h3>
						</div>

						<div class="desc column-description">
							<p><?php esc_html_e( 'Rank Math is a revolutionary SEO plugin that combines the features of many SEO tools in a single package & helps you multiply your traffic.', 'schema-markup' ); ?></p>
						</div>
					</div>

					<div class="plugin-card-bottom">
						<div class="column-compatibility">
							<span class="compatibility-compatible"><strong><?php esc_html_e( 'Compatible', 'schema-markup' ); ?></strong> <?php esc_html_e( 'with your version of WordPress', 'schema-markup' ); ?></span>
						</div>
						<a href="<?php echo $link; ?>" class="button button-primary <?php echo $button_class; ?>" data-slug="seo-by-rank-math" data-name="Rank Math"><?php echo $text; ?></a>
					</div>
				</div>

			</div>

		</div>
		<?php
	}
}
