<?php
/**
 * Metabox - Local Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

$local = [ [ 'rank_math_rich_snippet', 'local,restaurant' ] ];

$cmb->add_field([
	'id'   => 'rank_math_snippet_local_address',
	'type' => 'address',
	'name' => esc_html__( 'Address', 'schema-markup' ),
	'dep'  => $local,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_local_geo',
	'type'    => 'text',
	'name'    => esc_html__( 'Geo Coordinates', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $local,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_local_phone',
	'type'    => 'text',
	'name'    => esc_html__( 'Phone Number', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $local,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_local_price_range',
	'type'    => 'text',
	'name'    => esc_html__( 'Price Range', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $local,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_local_opens',
	'type'    => 'text_time',
	'name'    => esc_html__( 'Opening Time', 'schema-markup' ),
	'classes' => 'cmb-row-50',
	'dep'     => $local,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_local_closes',
	'type'    => 'text_time',
	'name'    => esc_html__( 'Closing Time', 'schema-markup' ),
	'classes' => 'cmb-row-50',
	'dep'     => $local,
]);

$cmb->add_field([
	'id'                => 'rank_math_snippet_local_opendays',
	'type'              => 'multicheck_inline',
	'name'              => esc_html__( 'Open Days', 'schema-markup' ),
	'options'           => [
		'monday'    => esc_html__( 'Monday', 'schema-markup' ),
		'tuesday'   => esc_html__( 'Tuesday', 'schema-markup' ),
		'wednesday' => esc_html__( 'Wednesday', 'schema-markup' ),
		'thursday'  => esc_html__( 'Thursday', 'schema-markup' ),
		'friday'    => esc_html__( 'Friday', 'schema-markup' ),
		'saturday'  => esc_html__( 'Saturday', 'schema-markup' ),
		'sunday'    => esc_html__( 'Sunday', 'schema-markup' ),
	],
	'select_all_button' => false,
	'dep'               => $local,
]);
