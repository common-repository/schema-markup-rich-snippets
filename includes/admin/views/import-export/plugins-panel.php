<?php
/**
 * Plugins panel template.
 *
 * @package    RankMath
 * @subpackage RankMath\Admin
 */

use RANKMATH_SCHEMA\Admin\Importers\Detector;

$detector = new Detector;
$plugins  = $detector->detect();
?>
<form class="rank-math-export-form cmb2-form" action="" method="post">

	<h3><?php esc_html_e( 'Plugin Importers', 'schema-markup' ); ?></h3>

	<div class="list-table with-action at-top">

		<?php if ( empty( $plugins ) ) : ?>
		<p class="empty-notice"><?php echo esc_html__( 'No plugin detected with importable data.', 'schema-markup' ); ?></p>
		<?php else : ?>
		<table class="form-table cmb2-wrap">
			<tbody>
				<?php foreach ( $plugins as $slug => $importer ) : ?>
					<tr class="importer-header">
						<th>
							<strong><?php echo $importer['name']; ?></strong>
						</th>
						<td>
							<button type="button" class="button button-primary rank-math-action" data-action="importPlugin" data-slug="<?php echo esc_attr( $slug ); ?>" data-active="<?php echo esc_attr( is_plugin_active( $importer['file'] ) ); ?>"><?php esc_html_e( 'Import', 'schema-markup' ); ?></button>
							<button type="button" class="button button-link-delete rank-math-action" data-action="cleanPlugin" data-slug="<?php echo esc_attr( $slug ); ?>"><?php esc_html_e( 'Clean', 'schema-markup' ); ?></button>
						</td>
					</tr>
					<tr class="choices">
						<td colspan="2">
							<ul class="cmb2-checkbox-list cmb2-list no-select-all">
								<?php
								foreach ( $importer['choices'] as $key => $label ) :
									$id = "{$slug}_{$key}";
									?>
									<li>
										<input type="checkbox" class="cmb2-option" name="<?php echo $slug; ?>[]" id="<?php echo $id; ?>" value="<?php echo $key; ?>" checked="checked">
										<label for="<?php echo $id; ?>"><?php echo $label; ?></label>
									</li>
								<?php endforeach; ?>
							</ul>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php endif; ?>
	</div>

</form>
