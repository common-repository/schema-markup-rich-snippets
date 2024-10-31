<?php
/**
 * Metabox - Person Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

$person = [ [ 'rank_math_rich_snippet', 'person' ] ];

$cmb->add_field([
	'id'         => 'rank_math_snippet_person_email',
	'type'       => 'text',
	'attributes' => [ 'type' => 'email' ],
	'name'       => esc_html__( 'Email', 'schema-markup' ),
	'dep'        => $person,
]);

$cmb->add_field([
	'id'   => 'rank_math_snippet_person_address',
	'type' => 'address',
	'name' => esc_html__( 'Address', 'schema-markup' ),
	'dep'  => $person,
]);

$cmb->add_field([
	'id'   => 'rank_math_snippet_person_gender',
	'type' => 'text',
	'name' => esc_html__( 'Gender', 'schema-markup' ),
	'dep'  => $person,
]);

$cmb->add_field([
	'id'   => 'rank_math_snippet_person_job_title',
	'type' => 'text',
	'name' => esc_html__( 'Job title', 'schema-markup' ),
	'dep'  => $person,
]);
