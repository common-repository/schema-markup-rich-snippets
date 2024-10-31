<?php
/**
 * Metabox - Review Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

$review = [ [ 'rank_math_rich_snippet', 'review' ] ];

$cmb->add_field([
	'id'         => 'rank_math_snippet_review_worst_rating',
	'name'       => esc_html__( 'Worst Rating', 'schema-markup' ),
	'type'       => 'text',
	'default'    => 1,
	'dep'        => $review,
	'attributes' => [ 'type' => 'number' ],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_review_best_rating',
	'name'       => esc_html__( 'Best Rating', 'schema-markup' ),
	'type'       => 'text',
	'default'    => 5,
	'dep'        => $review,
	'attributes' => [ 'type' => 'number' ],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_review_rating_value',
	'name'       => esc_html__( 'Rating Value', 'schema-markup' ),
	'type'       => 'text',
	'dep'        => $review,
	'attributes' => [
		'type' => 'number',
		'min'  => 1,
		'max'  => 5,
		'step' => 0.1,
	],
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_review_location',
	'name'    => esc_html__( 'Review Location', 'schema-markup' ),
	'type'    => 'select',
	'dep'     => $review,
	'classes' => 'nob',
	'default' => 'bottom',
	'options' => [
		'bottom' => esc_html__( 'Below Content', 'schema-markup' ),
		'top'    => esc_html__( 'Above Content', 'schema-markup' ),
		'both'   => esc_html__( 'Above & Below Content', 'schema-markup' ),
		'custom' => esc_html__( 'Custom (use shortcode)', 'schema-markup' ),
	],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_review_shortcode',
	'name'       => ' ',
	'type'       => 'text',
	'classes'    => 'nopt',
	'desc'       => esc_html__( 'Copy & paste this shortcode in the content.', 'schema-markup' ),
	'dep'        => [
		'relation' => 'and',
		[ 'rank_math_rich_snippet', 'review' ],
		[ 'rank_math_snippet_review_location', 'custom' ],
	],
	'attributes' => [
		'readonly' => 'readonly',
		'value'    => '[rank_math_rich_snippet]',
	],
]);
