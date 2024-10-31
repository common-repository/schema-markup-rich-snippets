<?php
/**
 * The post type settings.
 *
 * @package    RankMath
 * @subpackage RankMath\Settings
 */

use RANKMATH_SCHEMA\Helper;

$post_type     = $tab['post_type'];
$post_type_obj = get_post_type_object( $post_type );
$name          = $post_type_obj->labels->singular_name;
$custom_default  = 'off';
$richsnp_default = [
	'post'    => 'article',
	'product' => 'product',
];

if ( 'post' === $post_type || 'page' === $post_type ) {
	$custom_default = 'off';
} elseif ( 'attachment' === $post_type ) {
	$custom_default = 'on';
}

$primary_taxonomy_hash = [
	'post'    => 'category',
	'product' => 'product_cat',
];



if ( 'product' === $post_type || 'download' === $post_type ) {

	$cmb->add_field([
		'id'      => 'pt_' . $post_type . '_default_rich_snippet',
		'type'    => 'radio_inline',
		'name'    => esc_html__( 'Rich Snippet Type', 'schema-markup' ),
		/* translators: link to title setting screen */
		'desc'    => __( 'Default rich snippet selected when creating a new product.', 'schema-markup' ),
		'options' => [
			'off'     => esc_html__( 'None', 'schema-markup' ),
			'product' => esc_html__( 'Product', 'schema-markup' ),
		],
		'default' => $this->do_filter( 'settings/snippet/type', 'product', $post_type ),
	]);

} else {
	$cmb->add_field([
		'id'      => 'pt_' . $post_type . '_default_rich_snippet',
		'type'    => 'select',
		'name'    => esc_html__( 'Rich Snippet Type', 'schema-markup' ),
		'desc'    => esc_html__( 'Default rich snippet selected when creating a new post of this type. ', 'schema-markup' ),
		'options' => Helper::choices_rich_snippet_types( esc_html__( 'None (Click here to set one)', 'schema-markup' ) ),
		'default' => $this->do_filter( 'settings/snippet/type', isset( $richsnp_default[ $post_type ] ) ? $richsnp_default[ $post_type ] : 'off', $post_type ),
	]);

	// Common fields.
	$cmb->add_field([
		'id'              => 'pt_' . $post_type . '_default_snippet_name',
		'type'            => 'text',
		'name'            => esc_html__( 'Headline', 'schema-markup' ),
		'dep'             => [ [ 'pt_' . $post_type . '_default_rich_snippet', 'off', '!=' ] ],
		'classes'         => 'rank-math-supports-variables',
		'default'         => '%title%',
		'sanitization_cb' => false,
	]);

	$cmb->add_field([
		'id'              => 'pt_' . $post_type . '_default_snippet_desc',
		'type'            => 'textarea',
		'name'            => esc_html__( 'Description', 'schema-markup' ),
		'attributes'      => [
			'rows'            => 3,
			'data-autoresize' => true,
		],
		'classes'         => 'rank-math-supports-variables',
		'dep'             => [ [ 'pt_' . $post_type . '_default_rich_snippet', 'off,book,local', '!=' ] ],
		'sanitization_cb' => false,
		'default'         => '%excerpt%',
	]);
}

// Article fields.
$article_dep = [ [ 'pt_' . $post_type . '_default_rich_snippet', 'article' ] ];
/* translators: Google article snippet doc link */
$article_desc = 'person' === Helper::get_settings( 'titles.knowledgegraph_type' ) ? '<div class="notice notice-warning inline"><p>' . __( 'Google does not allow Person as the Publisher for articles. Organization will be used instead. You can read more about this <a href="https://developers.google.com/search/docs/data-types/article/?utm_campaign=Rank+Math" target="_blank">here</a>.', 'schema-markup' ) . '</p></div>' : '';
$cmb->add_field([
	'id'      => 'pt_' . $post_type . '_default_article_type',
	'type'    => 'radio_inline',
	'name'    => esc_html__( 'Article Type', 'schema-markup' ),
	'options' => [
		'Article'     => esc_html__( 'Article', 'schema-markup' ),
		'BlogPosting' => esc_html__( 'Blog Post', 'schema-markup' ),
		'NewsArticle' => esc_html__( 'News Article', 'schema-markup' ),
	],
	'default' => $this->do_filter( 'settings/snippet/article_type', 'post' === $post_type ? 'BlogPosting' : 'Article', $post_type ),
	'desc'    => $article_desc,
	'dep'     => $article_dep,
]);

// Enable/Disable Metabox option.
if ( 'attachment' !== $post_type ) {
	$cmb->add_field([
		'id'      => 'pt_' . $post_type . '_add_meta_box',
		'type'    => 'switch',
		'name'    => esc_html__( 'Add SEO Meta Box', 'schema-markup' ),
		'desc'    => esc_html__( 'Add the SEO Meta Box for the editor screen to customize SEO options for posts in this post type.', 'schema-markup' ),
		'default' => 'on',
	]);

	$cmb->add_field([
		'id'      => 'pt_' . $post_type . '_bulk_editing',
		'type'    => 'radio_inline',
		'name'    => esc_html__( 'Bulk Editing', 'schema-markup' ),
		'desc'    => esc_html__( 'Add bulk editing columns to the post listing screen.', 'schema-markup' ),
		'options' => [
			'0'        => esc_html__( 'Disabled', 'schema-markup' ),
			'editing'  => esc_html__( 'Enabled', 'schema-markup' ),
			'readonly' => esc_html__( 'Read Only', 'schema-markup' ),
		],
		'default' => 'editing',
		'dep'     => [ [ 'pt_' . $post_type . '_add_meta_box', 'on' ] ],
	]);
}
