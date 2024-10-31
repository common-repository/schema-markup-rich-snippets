<?php
/**
 * Metabox - Article Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

use RANKMATH_SCHEMA\Helper;

$article_dep = [ [ 'rank_math_rich_snippet', 'article' ] ];
/* translators: Google article snippet doc link */
$article_desc = 'person' === Helper::get_settings( 'titles.knowledgegraph_type' ) ? '<div class="notice notice-warning inline"><p>' . __( 'Google does not allow Person as the Publisher for articles. Organization will be used instead. You can read more about this <a href="https://developers.google.com/search/docs/data-types/article/?utm_campaign=Rank+Math" target="_blank">here</a>.', 'schema-markup' ) . '</p></div>' : '';

$cmb->add_field([
	'id'      => 'rank_math_snippet_article_type',
	'type'    => 'radio_inline',
	'name'    => esc_html__( 'Article Type', 'schema-markup' ),
	'options' => [
		'Article'     => esc_html__( 'Article', 'schema-markup' ),
		'BlogPosting' => esc_html__( 'Blog Post', 'schema-markup' ),
		'NewsArticle' => esc_html__( 'News Article', 'schema-markup' ),
	],
	'default' => Helper::get_settings( "titles.pt_{$post_type}_default_article_type" ),
	'classes' => 'nob',
	'desc'    => $article_desc,
	'dep'     => $article_dep,
]);
