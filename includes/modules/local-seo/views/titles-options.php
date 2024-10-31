<?php
/**
 * The local seo settings.
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\Local_Seo
 */

use RANKMATH_SCHEMA\Helper;

$company = [ [ 'knowledgegraph_type', 'company' ] ];
$person  = [ [ 'knowledgegraph_type', 'person' ] ];

$cmb->add_field([
	'id'      => 'knowledgegraph_type',
	'type'    => 'radio_inline',
	'name'    => esc_html__( 'Person or Company', 'schema-markup' ),
	'options' => [
		'person'  => esc_html__( 'Person', 'schema-markup' ),
		'company' => esc_html__( 'Organization', 'schema-markup' ),
	],
	'desc'    => esc_html__( 'Choose whether the site represents a person or an organization.', 'schema-markup' ),
	'default' => 'person',
]);

$cmb->add_field([
	'id'      => 'knowledgegraph_name',
	'type'    => 'text',
	'name'    => esc_html__( 'Name', 'schema-markup' ),
	'desc'    => esc_html__( 'Your name or company name', 'schema-markup' ),
	'default' => get_bloginfo( 'name' ),
]);

$cmb->add_field([
	'id'      => 'knowledgegraph_logo',
	'type'    => 'file',
	'name'    => esc_html__( 'Logo', 'schema-markup' ),
	'desc'    => __( '<strong>Min Size: 160Χ90px, Max Size: 1920X1080px</strong>.<br /> A squared image is preferred by the search engines.', 'schema-markup' ),
	'options' => [ 'url' => false ],
]);

$cmb->add_field([
	'id'      => 'url',
	'type'    => 'text',
	'name'    => esc_html__( 'URL', 'schema-markup' ),
	'desc'    => esc_html__( 'URL of the item.', 'schema-markup' ),
	'default' => site_url(),
]);

$cmb->add_field([
	'id'   => 'email',
	'type' => 'text',
	'name' => esc_html__( 'Email', 'schema-markup' ),
	'desc' => esc_html__( 'Search engines display your email address.', 'schema-markup' ),
]);

$cmb->add_field([
	'id'   => 'phone',
	'type' => 'text',
	'name' => esc_html__( 'Phone', 'schema-markup' ),
	'desc' => esc_html__( 'Search engines may prominently display your contact phone number for mobile users.', 'schema-markup' ),
	'dep'  => $person,
]);

$cmb->add_field([
	'id'   => 'local_address',
	'type' => 'address',
	'name' => esc_html__( 'Address', 'schema-markup' ),
]);

$cmb->add_field([
	'id'         => 'local_address_format',
	'type'       => 'textarea_small',
	'name'       => esc_html__( 'Address Format', 'schema-markup' ),
	'desc'       => wp_kses_post( __( 'Format used when the address is displayed using the <code>[rank_math_contact_info]</code> shortcode.<br><strong>Available Tags: {address}, {locality}, {region}, {postalcode}, {country}, {gps}</strong>', 'schema-markup' ) ),
	'default'    => '{address} {locality}, {region} {postalcode}',
	'classes'    => 'rank-math-address-format',
	'attributes' => [
		'rows'        => 2,
		'placeholder' => '{address} {locality}, {region} {country}. {postalcode}.',
	],
	'dep'        => $company,
]);

$cmb->add_field([
	'id'         => 'local_business_type',
	'type'       => 'select',
	'name'       => esc_html__( 'Business Type', 'schema-markup' ),
	'options'    => Helper::choices_business_types( true ),
	'attributes' => ( 'data-s2' ),
	'dep'        => $company,
]);

$opening_hours = $cmb->add_field([
	'id'      => 'opening_hours',
	'type'    => 'group',
	'name'    => esc_html__( 'Opening Hours', 'schema-markup' ),
	'desc'    => esc_html__( 'Select opening hours. You can add multiple sets if you have different opening or closing hours on some days or if you have a mid-day break. Times are specified using 24:00 time.', 'schema-markup' ),
	'options' => [
		'add_button'    => esc_html__( 'Add time', 'schema-markup' ),
		'remove_button' => esc_html__( 'Remove', 'schema-markup' ),
	],
	'dep'     => $company,
	'classes' => 'cmb-group-text-only',
]);

$cmb->add_group_field( $opening_hours, [
	'id'      => 'day',
	'type'    => 'select',
	'options' => [
		'Monday'    => esc_html__( 'Monday', 'schema-markup' ),
		'Tuesday'   => esc_html__( 'Tuesday', 'schema-markup' ),
		'Wednesday' => esc_html__( 'Wednesday', 'schema-markup' ),
		'Thursday'  => esc_html__( 'Thursday', 'schema-markup' ),
		'Friday'    => esc_html__( 'Friday', 'schema-markup' ),
		'Saturday'  => esc_html__( 'Saturday', 'schema-markup' ),
		'Sunday'    => esc_html__( 'Sunday', 'schema-markup' ),
	],
]);

$cmb->add_group_field( $opening_hours, [
	'id'         => 'time',
	'type'       => 'text',
	'default'    => '09:00-17:00',
	'attributes' => [ 'placeholder' => esc_html__( 'e.g. 09:00-17:00', 'schema-markup' ) ],
]);

$cmb->add_field([
	'id'      => 'opening_hours_format',
	'type'    => 'switch',
	'name'    => esc_html__( 'Opening Hours Format', 'schema-markup' ),
	'options' => [
		'off' => '24:00',
		'on'  => '12:00',
	],
	'desc'    => esc_html__( 'Time format used in the contact shortcode.', 'schema-markup' ),
	'default' => 'off',
	'dep'     => $company,
]);

$phones = $cmb->add_field([
	'id'      => 'phone_numbers',
	'type'    => 'group',
	'name'    => esc_html__( 'Phone Number', 'schema-markup' ),
	'desc'    => esc_html__( 'Search engines may prominently display your contact phone number for mobile users.', 'schema-markup' ),
	'options' => [
		'add_button'    => esc_html__( 'Add number', 'schema-markup' ),
		'remove_button' => esc_html__( 'Remove', 'schema-markup' ),
	],
	'dep'     => $company,
	'classes' => 'cmb-group-text-only',
]);

$cmb->add_group_field( $phones, [
	'id'      => 'type',
	'type'    => 'select',
	'options' => [
		'customer support'    => esc_html__( 'Customer Service', 'schema-markup' ),
		'technical support'   => esc_html__( 'Technical Support', 'schema-markup' ),
		'billing support'     => esc_html__( 'Billing Support', 'schema-markup' ),
		'bill payment'        => esc_html__( 'Bill Payment', 'schema-markup' ),
		'sales'               => esc_html__( 'Sales', 'schema-markup' ),
		'reservations'        => esc_html__( 'Reservations', 'schema-markup' ),
		'credit card support' => esc_html__( 'Credit Card Support', 'schema-markup' ),
		'emergency'           => esc_html__( 'Emergency', 'schema-markup' ),
		'baggage tracking'    => esc_html__( 'Baggage Tracking', 'schema-markup' ),
		'roadside assistance' => esc_html__( 'Roadside Assistance', 'schema-markup' ),
		'package tracking'    => esc_html__( 'Package Tracking', 'schema-markup' ),
	],
	'default' => 'customer_support',
]);
$cmb->add_group_field( $phones, [
	'id'         => 'number',
	'type'       => 'text',
	'attributes' => [ 'placeholder' => esc_html__( 'Format: +1-401-555-1212', 'schema-markup' ) ],
]);

$cmb->add_field([
	'id'   => 'price_range',
	'type' => 'text',
	'name' => esc_html__( 'Price Range', 'schema-markup' ),
	'desc' => esc_html__( 'The price range of the business, for example $$$.', 'schema-markup' ),
	'dep'  => $company,
]);

$about_page    = Helper::get_settings( 'titles.local_seo_about_page' );
$about_options = [ '' => __( 'Select Page', 'schema-markup' ) ];
if ( $about_page ) {
	$about_options[ $about_page ] = get_the_title( $about_page );
}
$cmb->add_field([
	'id'         => 'local_seo_about_page',
	'type'       => 'select',
	'options'    => $about_options,
	'name'       => esc_html__( 'About Page', 'schema-markup' ),
	'desc'       => esc_html__( 'Select a page on your site where you want to show the LocalBusiness meta data.', 'schema-markup' ),
	'attributes' => ( 'data-s2-pages' ),
]);

$contact_page    = Helper::get_settings( 'titles.local_seo_contact_page' );
$contact_options = [ '' => __( 'Select Page', 'schema-markup' ) ];
if ( $contact_page ) {
	$contact_options[ $contact_page ] = get_the_title( $contact_page );
}
$cmb->add_field([
	'id'         => 'local_seo_contact_page',
	'type'       => 'select',
	'options'    => $contact_options,
	'name'       => esc_html__( 'Contact Page', 'schema-markup' ),
	'desc'       => esc_html__( 'Select a page on your site where you want to show the LocalBusiness meta data.', 'schema-markup' ),
	'attributes' => ( 'data-s2-pages' ),
]);

$cmb->add_field([
	'id'   => 'maps_api_key',
	'type' => 'text',
	'name' => esc_html__( 'Google Maps API Key', 'schema-markup' ),
	/* translators: %s expands to "Google Maps Embed API" https://developers.google.com/maps/documentation/embed/ */
	'desc' => sprintf( esc_html__( 'An API Key is required to display embedded Google Maps on your site. Get it here: %s', 'schema-markup' ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">' . __( 'Google Maps Embed API', 'schema-markup' ) . '</a>' ),
	'dep'  => $company,
]);

$cmb->add_field([
	'id'    => 'geo',
	'type'  => 'text',
	'name'  => esc_html__( 'Geo Coordinates', 'schema-markup' ),
	'desc'  => esc_html__( 'Latitude and longitude values separated by comma.', 'schema-markup' ),
	'dep'   => $company,
	'after' => '<strong style="margin-top:20px; display:block; text-align:right;">' . __( '*Multiple Location option is coming soon.', 'schema-markup' ) . '</strong>',
]);
