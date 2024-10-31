<?php
/**
 * Metabox - Job Posting Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

use RANKMATH_SCHEMA\Helper;

$jobposting = [ [ 'rank_math_rich_snippet', 'jobposting' ] ];

$cmb->add_field([
	'id'         => 'rank_math_snippet_jobposting_salary',
	'type'       => 'text',
	'name'       => esc_html__( 'Salary (Recommended)', 'schema-markup' ),
	'desc'       => esc_html__( 'Insert amount, e.g. "50.00", or a salary range, e.g. "40.00-50.00".', 'schema-markup' ),
	'classes'    => 'cmb-row-33',
	'dep'        => $jobposting,
	'attributes' => [ 'type' => 'number' ],
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_jobposting_currency',
	'type'    => 'text',
	'name'    => esc_html__( 'Salary Currency', 'schema-markup' ),
	'desc'    => esc_html__( 'ISO 4217 Currency Code', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $jobposting,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_jobposting_payroll',
	'type'    => 'select',
	'name'    => esc_html__( 'Payroll (Recommended)', 'schema-markup' ),
	'desc'    => esc_html__( 'Salary amount is for', 'schema-markup' ),
	'options' => [
		''      => esc_html__( 'None', 'schema-markup' ),
		'YEAR'  => esc_html__( 'Yearly', 'schema-markup' ),
		'MONTH' => esc_html__( 'Monthly', 'schema-markup' ),
		'WEEK'  => esc_html__( 'Weekly', 'schema-markup' ),
		'DAY'   => esc_html__( 'Daily', 'schema-markup' ),
		'HOUR'  => esc_html__( 'Hourly', 'schema-markup' ),
	],
	'classes' => 'cmb-row-33',
	'dep'     => $jobposting,
]);

$cmb->add_field([
	'id'          => 'rank_math_snippet_jobposting_startdate',
	'type'        => 'text_datetime_timestamp',
	'date_format' => 'Y-m-d',
	'name'        => esc_html__( 'Date Posted', 'schema-markup' ),
	'desc'        => wp_kses_post( __( 'The original date on which employer posted the job. You can leave it empty to use the post publication date as job posted date.', 'schema-markup' ) ),
	'classes'     => 'cmb-row-33',
	'dep'         => $jobposting,
]);

$cmb->add_field([
	'id'          => 'rank_math_snippet_jobposting_expirydate',
	'type'        => 'text_datetime_timestamp',
	'date_format' => 'Y-m-d',
	'name'        => esc_html__( 'Expiry Posted', 'schema-markup' ),
	'desc'        => esc_html__( 'The date when the job posting will expire. If a job posting never expires, or you do not know when the job will expire, do not include this property.', 'schema-markup' ),
	'classes'     => 'cmb-row-33',
	'dep'         => $jobposting,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_jobposting_unpublish',
	'type'    => 'switch',
	'name'    => esc_html__( 'Unpublish when expired', 'schema-markup' ),
	'desc'    => esc_html__( 'If checked, post status will be changed to Draft and its URL will return a 404 error, as required by the Rich Result guidelines.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'default' => 'on',
	'dep'     => $jobposting,
]);

$cmb->add_field([
	'id'                => 'rank_math_snippet_jobposting_employment_type',
	'type'              => 'multicheck_inline',
	'name'              => esc_html__( 'Employment Type (Recommended)', 'schema-markup' ),
	'desc'              => esc_html__( 'Type of employment. You can choose more than one value.', 'schema-markup' ),
	'options'           => [
		'FULL_TIME'  => esc_html__( 'Full Time', 'schema-markup' ),
		'PART_TIME'  => esc_html__( 'Part Time', 'schema-markup' ),
		'CONTRACTOR' => esc_html__( 'Contractor', 'schema-markup' ),
		'TEMPORARY'  => esc_html__( 'Temporary', 'schema-markup' ),
		'INTERN'     => esc_html__( 'Intern', 'schema-markup' ),
		'VOLUNTEER'  => esc_html__( 'Volunteer', 'schema-markup' ),
		'PER_DIEM'   => esc_html__( 'Per Diem', 'schema-markup' ),
		'OTHER'      => esc_html__( 'Other', 'schema-markup' ),
	],
	'dep'               => $jobposting,
	'select_all_button' => false,
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_jobposting_organization',
	'type'       => 'text',
	'name'       => esc_html__( 'Hiring Organization', 'schema-markup' ),
	'desc'       => esc_html__( 'The name of the company. Leave empty to use your own company information.', 'schema-markup' ),
	'attributes' => [
		'placeholder' => 'company' === Helper::get_settings( 'titles.knowledgegraph_type' ) ? Helper::get_settings( 'titles.knowledgegraph_name' ) : get_bloginfo( 'name' ),
	],
	'dep'        => $jobposting,
	'classes'    => 'cmb-row-50',
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_jobposting_id',
	'type'    => 'text',
	'name'    => esc_html__( 'Posting ID (Recommended)', 'schema-markup' ),
	'desc'    => esc_html__( 'The hiring organization\'s unique identifier for the job. Leave empty to use the post ID.', 'schema-markup' ),
	'classes' => 'cmb-row-50',
	'dep'     => $jobposting,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_jobposting_url',
	'type'    => 'text_url',
	'name'    => esc_html__( 'Organization URL (Recommended)', 'schema-markup' ),
	'desc'    => esc_html__( 'The URL of the organization offering the job position. Leave empty to use your own company information.', 'schema-markup' ),
	'classes' => 'cmb-row-50',
	'dep'     => $jobposting,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_jobposting_logo',
	'type'    => 'text_url',
	'name'    => esc_html__( 'Organization Logo (Recommended)', 'schema-markup' ),
	'desc'    => esc_html__( 'Logo URL of the organization offering the job position. Leave empty to use your own company information.', 'schema-markup' ),
	'classes' => 'cmb-row-50',
	'dep'     => $jobposting,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_jobposting_address',
	'type'    => 'address',
	'name'    => esc_html__( 'Location', 'schema-markup' ),
	'classes' => 'nob',
	'dep'     => $jobposting,
]);
