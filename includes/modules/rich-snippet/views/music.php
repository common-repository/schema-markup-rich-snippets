<?php
/**
 * Metabox - Music Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

$music = [ [ 'rank_math_rich_snippet', 'music' ] ];

$cmb->add_field([
	'id'      => 'rank_math_snippet_music_type',
	'type'    => 'radio_inline',
	'name'    => esc_html__( 'Type', 'schema-markup' ),
	'options' => [
		'MusicGroup' => esc_html__( 'MusicGroup', 'schema-markup' ),
		'MusicAlbum' => esc_html__( 'MusicAlbum', 'schema-markup' ),
	],
	'classes' => 'cmb-row-33 nob',
	'default' => 'MusicGroup',
	'dep'     => $music,
]);
