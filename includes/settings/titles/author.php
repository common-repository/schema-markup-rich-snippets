<?php
/**
 * The authors settings.
 *
 * @package    RankMath
 * @subpackage RankMath\Settings
 */

$cmb->add_field([
	'id'      => 'author_add_meta_box',
	'type'    => 'switch',
	'name'    => esc_html__( 'Add SEO Meta Box for Users', 'schema-markup' ),
	'desc'    => esc_html__( 'Add SEO Meta Box for user profile pages. Access to the Meta Box can be fine tuned with code, using a special filter hook.', 'schema-markup' ),
	'default' => 'on',
]);
