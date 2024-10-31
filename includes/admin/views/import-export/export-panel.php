<?php
/**
 * Export panel template.
 *
 * @package    RankMath
 * @subpackage RANKMATH_SCHEMA\Admin
 */

use RANKMATH_SCHEMA\Helper;
?>
<form class="rank-math-export-form cmb2-form" action="" method="post">

	<h3><?php esc_html_e( 'Export Settings', 'schema-markup' ); ?></h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="status"><?php esc_html_e( 'Panels', 'schema-markup' ); ?></label></th>
				<td>
					<ul class="cmb2-checkbox-list no-select-all cmb2-list">
						<li><input type="checkbox" class="cmb2-option" name="panels[]" id="status1" value="titles" checked="checked"> <label for="status1"><?php esc_html_e( 'Titles &amp; Metas', 'schema-markup' ); ?></label></li>
						<?php if ( Helper::is_404_monitor_active() || Helper::is_redirections_active() ) { ?>
							<li><input type="checkbox" class="cmb2-option" name="panels[]" id="status2" value="general" checked="checked"> <label for="status2"><?php esc_html_e( 'General Settings', 'schema-markup' ); ?></label></li>
						<?php } ?>
					</ul>
					<p class="description"><?php esc_html_e( 'Choose the panels to export.', 'schema-markup' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>

	<footer>
		<input type="hidden" name="object_id" value="export-plz">
		<button type="submit" class="button button-primary button-xlarge"><?php esc_html_e( 'Export', 'schema-markup' ); ?></button>
	</footer>

</form>
