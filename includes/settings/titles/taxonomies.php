<?php
/**
 * The taxonomies settings.
 *
 * @package    RankMath
 * @subpackage RankMath\Settings
 */

use RANKMATH_SCHEMA\Helper;

$taxonomy     = $tab['taxonomy'];
$taxonomy_obj = get_taxonomy( $taxonomy );
$name         = $taxonomy_obj->labels->singular_name;

$cmb->add_field([
	'id'      => 'remove_' . $taxonomy . '_snippet_data',
	'type'    => 'switch',
	'name'    => esc_html__( 'Remove Snippet Data', 'schema-markup' ),
	/* translators: taxonomy name */
	'desc'    => sprintf( esc_html__( 'Remove schema data from %s.', 'schema-markup' ), $name ),
	'default' => ( in_array( $taxonomy, [ 'product_cat', 'product_tag' ] ) ) ? 'on' : 'off',
]);