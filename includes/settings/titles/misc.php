<?php
/**
 * The misc settings.
 *
 * @package    RankMath
 * @subpackage RankMath\Settings
 */

$dep = [ [ 'disable_date_archives', 'off' ] ];

$cmb->add_field([
	'id'      => 'disable_date_archives',
	'type'    => 'switch',
	'name'    => esc_html__( 'Date Archives', 'schema-markup' ),
	'desc'    => esc_html__( 'Redirect date archives to homepage.', 'schema-markup' ),
	'options' => [
		'off' => esc_html__( 'Enabled', 'schema-markup' ),
		'on'  => esc_html__( 'Disabled', 'schema-markup' ),
	],
	'default' => 'off',
]);

$cmb->add_field([
	'id'              => 'date_archive_title',
	'type'            => 'text',
	'name'            => esc_html__( 'Date Archive Title', 'schema-markup' ),
	'desc'            => esc_html__( 'Title tag on day/month/year based archives.', 'schema-markup' ),
	'classes'         => 'rank-math-supports-variables rank-math-title',
	'default'         => '%date% %page% %sep% %sitename%',
	'dep'             => $dep,
	'sanitization_cb' => false,
]);

$cmb->add_field([
	'id'              => 'date_archive_description',
	'type'            => 'textarea_small',
	'name'            => esc_html__( 'Date Archive Description', 'schema-markup' ),
	'desc'            => esc_html__( 'Date archive description.', 'schema-markup' ),
	'classes'         => 'rank-math-supports-variables rank-math-description',
	'dep'             => $dep,
	'sanitization_cb' => false,
]);

$cmb->add_field([
	'id'              => 'search_title',
	'type'            => 'text',
	'name'            => esc_html__( 'Search Results Title', 'schema-markup' ),
	'desc'            => esc_html__( 'Title tag on search results page.', 'schema-markup' ),
	'classes'         => 'rank-math-supports-variables rank-math-title',
	'default'         => '%search_query% %page% %sep% %sitename%',
	'sanitization_cb' => false,
]);

$cmb->add_field([
	'id'              => '404_title',
	'type'            => 'text',
	'name'            => esc_html__( '404 Title', 'schema-markup' ),
	'desc'            => esc_html__( 'Title tag on 404 Not Found error page.', 'schema-markup' ),
	'classes'         => 'rank-math-supports-variables rank-math-title',
	'default'         => 'Page Not Found %sep% %sitename%',
	'sanitization_cb' => false,
]);

$cmb->add_field([
	'id'      => 'noindex_date',
	'type'    => 'switch',
	'name'    => esc_html__( 'Noindex Date Archives', 'schema-markup' ),
	'desc'    => esc_html__( 'Prevent date archives from getting indexed by search engines.', 'schema-markup' ),
	'default' => 'on',
]);

$cmb->add_field([
	'id'      => 'noindex_search',
	'type'    => 'switch',
	'name'    => esc_html__( 'Noindex Search Results', 'schema-markup' ),
	'desc'    => esc_html__( 'Prevent search results pages from getting indexed by search engines. Search results could be considered to be thin content and prone to duplicate content issues.', 'schema-markup' ),
	'default' => 'on',
]);

$cmb->add_field([
	'id'      => 'noindex_paginated_pages',
	'type'    => 'switch',
	'name'    => esc_html__( 'Noindex Paginated Pages', 'schema-markup' ),
	'desc'    => wp_kses_post( __( 'Set this to on to prevent /page/2 and further of any archive to show up in the search results.', 'schema-markup' ) ),
	'default' => 'off',
]);

$cmb->add_field([
	'id'      => 'noindex_archive_subpages',
	'type'    => 'switch',
	'name'    => esc_html__( 'Noindex Archive Subpages', 'schema-markup' ),
	'desc'    => esc_html__( 'Prevent paginated archive pages from getting indexed by search engines.', 'schema-markup' ),
	'default' => 'off',
]);

$cmb->add_field([
	'id'      => 'noindex_password_protected',
	'type'    => 'switch',
	'name'    => esc_html__( 'Noindex Password Protected Pages', 'schema-markup' ),
	'desc'    => esc_html__( 'Prevent password protected pages & posts from getting indexed by search engines.', 'schema-markup' ),
	'default' => 'off',
]);
