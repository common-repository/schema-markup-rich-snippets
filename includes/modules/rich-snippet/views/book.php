<?php
/**
 * Metabox - Book Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

$book_dep = [ [ 'rank_math_rich_snippet', 'book' ] ];

$cmb->add_field([
	'id'      => 'rank_math_snippet_book_editions',
	'type'    => 'group',
	'name'    => esc_html__( 'Book Editions', 'schema-markup' ),
	'desc'    => esc_html__( 'Either a specific edition of the written work, or the volume of the work.', 'schema-markup' ),
	'options' => [
		'closed'        => true,
		'sortable'      => true,
		'add_button'    => esc_html__( 'Add New', 'schema-markup' ),
		'group_title'   => esc_html__( 'Book Edition {#}', 'schema-markup' ),
		'remove_button' => esc_html__( 'Remove', 'schema-markup' ),
	],
	'classes' => 'cmb-group-fix-me nob',
	'dep'     => $book_dep,
	'fields'  => [
		[
			'id'   => 'name',
			'type' => 'text',
			'name' => esc_html__( 'Title', 'schema-markup' ),
			'desc' => __( 'The title of the tome. Use for the title of the tome if it differs from the book.<br>*Optional when tome has the same title as the book.', 'schema-markup' ),
		],

		[
			'id'   => 'book_edition',
			'type' => 'text',
			'name' => esc_html__( 'Edition', 'schema-markup' ),
			'desc' => esc_html__( 'The edition of the book.', 'schema-markup' ),
		],

		[
			'id'   => 'isbn',
			'type' => 'text',
			'name' => esc_html__( 'ISBN', 'schema-markup' ),
			'desc' => esc_html__( 'The ISBN of the print book.', 'schema-markup' ),
		],

		[
			'id'   => 'url',
			'type' => 'text_url',
			'name' => esc_html__( 'Url', 'schema-markup' ),
			'desc' => esc_html__( 'URL specific to this edition if one exists.', 'schema-markup' ),
		],

		[
			'id'   => 'author',
			'type' => 'text',
			'name' => esc_html__( 'Author(s)', 'schema-markup' ),
			'desc' => __( 'The author(s) of the tome. Use if the author(s) of the tome differ from the related book. Provide one Person entity per author.<br>*Optional when the tome has the same set of authors as the book.', 'schema-markup' ),
		],

		[
			'id'   => 'date_published',
			'type' => 'text_date',
			'name' => esc_html__( 'Date Published', 'schema-markup' ),
			'desc' => esc_html__( 'Date of first publication of this tome.', 'schema-markup' ),
		],

		[
			'id'      => 'book_format',
			'type'    => 'radio_inline',
			'name'    => esc_html__( 'Book Format', 'schema-markup' ),
			'desc'    => esc_html__( 'The format of the book.', 'schema-markup' ),
			'options' => [
				'EBook'     => esc_html__( 'EBook', 'schema-markup' ),
				'Hardcover' => esc_html__( 'Hardcover', 'schema-markup' ),
				'Paperback' => esc_html__( 'Paperback', 'schema-markup' ),
				'AudioBook' => esc_html__( 'Audio Book', 'schema-markup' ),
			],
			'default' => 'Hardcover',
		],
	],
]);
