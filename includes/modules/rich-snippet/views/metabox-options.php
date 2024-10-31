<?php
/**
 * Metabox - Rich Snippet Tab
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

use RANKMATH_SCHEMA\Helper;
use MyThemeShop\Helpers\WordPress;

$post_type = WordPress::get_post_type();

if ( ( class_exists( 'WooCommerce' ) && 'product' === $post_type ) || ( class_exists( 'Easy_Digital_Downloads' ) && 'download' === $post_type ) ) {

	$cmb->add_field([
		'id'      => 'rank_math_woocommerce_notice',
		'type'    => 'notice',
		'what'    => 'info',
		'content' => '<span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Rank Math automatically inserts additional Rich Snippet meta data for WooCommerce products. You can set the Rich Snippet Type to "None" to disable this feature and just use the default data added by WooCommerce.', 'schema-markup' ),
	]);

	$cmb->add_field([
		'id'      => 'rank_math_rich_snippet',
		'type'    => 'radio_inline',
		'name'    => esc_html__( 'Rich Snippet Type', 'schema-markup' ),
		/* translators: link to title setting screen */
		'desc'    => wp_kses_post( __( 'Rich Snippets help you stand out in SERPs. <a href="https://rankmath.com/kb/rich-snippets/" target="_blank">Learn more</a>.', 'schema-markup' ) ),
		'options' => [
			'off'     => esc_html__( 'None', 'schema-markup' ),
			'product' => esc_html__( 'Product', 'schema-markup' ),
		],
		'default' => Helper::get_settings( "titles.pt_{$post_type}_default_rich_snippet" ),
	]);

	return;
}

$cmb->add_field([
	'id'      => 'rank_math_rich_snippet',
	'type'    => 'select',
	'name'    => esc_html__( 'Rich Snippet Type', 'schema-markup' ),
	/* translators: link to title setting screen */
	'desc'    => wp_kses_post( __( 'Rich Snippets help you stand out in SERPs. <a href="https://rankmath.com/kb/rich-snippets/" target="_blank">Learn more</a>.', 'schema-markup' ) ),
	'options' => Helper::choices_rich_snippet_types( esc_html__( 'None', 'schema-markup' ) ),
	'default' => Helper::get_settings( "titles.pt_{$post_type}_default_rich_snippet" ),
]);

$headline = Helper::get_settings( "titles.pt_{$post_type}_default_snippet_name" );
$headline = $headline ? $headline : '';

// Common fields.
$cmb->add_field([
	'id'         => 'rank_math_snippet_shortcode',
	'name'       => esc_html__( 'Shortcode', 'schema-markup' ),
	'type'       => 'text',
	'desc'       => esc_html__( 'Copy & paste this shortcode in the content.', 'schema-markup' ),
	'dep'        => [ [ 'rank_math_rich_snippet', 'off,article,review', '!=' ] ],
	'attributes' => [
		'readonly' => 'readonly',
		'value'    => '[rank_math_rich_snippet]',
	],
]);
$cmb->add_field([
	'id'         => 'rank_math_snippet_name',
	'type'       => 'text',
	'name'       => esc_html__( 'Headline', 'schema-markup' ),
	'dep'        => [ [ 'rank_math_rich_snippet', 'off', '!=' ] ],
	'attributes' => [ 'placeholder' => $headline ],
	'classes'    => 'rank-math-supports-variables',
]);

$description = Helper::get_settings( "titles.pt_{$post_type}_default_snippet_desc" );
$description = $description ? $description : '';

$cmb->add_field([
	'id'         => 'rank_math_snippet_desc',
	'type'       => 'textarea',
	'name'       => esc_html__( 'Description', 'schema-markup' ),
	'attributes' => [
		'rows'            => 3,
		'data-autoresize' => true,
		'placeholder'     => $description,
	],
	'classes'    => 'rank-math-supports-variables',
	'dep'        => [ [ 'rank_math_rich_snippet', 'off,book,local', '!=' ] ],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_url',
	'type'       => 'text_url',
	'name'       => esc_html__( 'Url', 'schema-markup' ),
	'attributes' => [
		'rows'            => 3,
		'data-autoresize' => true,
	],
	'dep'        => [ [ 'rank_math_rich_snippet', 'book,event,local,music' ] ],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_author',
	'type'       => 'text',
	'name'       => esc_html__( 'Author', 'schema-markup' ),
	'attributes' => [
		'rows'            => 3,
		'data-autoresize' => true,
	],
	'dep'        => [ [ 'rank_math_rich_snippet', 'book' ] ],
]);

include_once 'article.php';
include_once 'book.php';
include_once 'course.php';
include_once 'event.php';
include_once 'job-posting.php';
include_once 'local.php';
include_once 'music.php';
include_once 'product.php';
include_once 'recipe.php';
include_once 'restaurant.php';
include_once 'video.php';
include_once 'person.php';
include_once 'review.php';
include_once 'software.php';
include_once 'service.php';
