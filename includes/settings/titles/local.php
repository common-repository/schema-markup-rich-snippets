<?php
/**
 * The local seo settings.
 *
 * @package    RankMath
 * @subpackage RankMath\Local_Seo
 */

use RANKMATH_SCHEMA\Helper;

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
