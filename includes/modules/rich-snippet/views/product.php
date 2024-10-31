<?php
/**
 * Metabox - Product Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

$product = [ [ 'rank_math_rich_snippet', 'product' ] ];

$cmb->add_field([
	'id'   => 'rank_math_snippet_product_sku',
	'type' => 'text',
	'name' => esc_html__( 'Product SKU', 'schema-markup' ),
	'dep'  => $product,
]);

$cmb->add_field([
	'id'   => 'rank_math_snippet_product_brand',
	'type' => 'text',
	'name' => esc_html__( 'Product Brand', 'schema-markup' ),
	'dep'  => $product,
]);

$cmb->add_field([
	'id'   => 'rank_math_snippet_product_currency',
	'type' => 'text',
	'name' => esc_html__( 'Product Currency', 'schema-markup' ),
	'desc' => esc_html__( 'ISO 4217 Currency Code', 'schema-markup' ),
	'dep'  => $product,
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_product_price',
	'type'       => 'text',
	'name'       => esc_html__( 'Product Price', 'schema-markup' ),
	'dep'        => $product,
	'attributes' => [ 'type' => 'number' ],
]);

$cmb->add_field([
	'id'          => 'rank_math_snippet_product_price_valid',
	'type'        => 'text_date',
	'date_format' => 'Y-m-d',
	'name'        => esc_html__( 'Price Valid Until', 'schema-markup' ),
	'desc'        => esc_html__( 'The date after which the price will no longer be available.', 'schema-markup' ),
	'dep'         => $product,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_product_instock',
	'type'    => 'switch',
	'name'    => esc_html__( 'Product In-Stock', 'schema-markup' ),
	'dep'     => $product,
	'classes' => 'nob',
	'default' => 'on',
]);
