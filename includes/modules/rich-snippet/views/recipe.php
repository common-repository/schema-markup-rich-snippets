<?php
/**
 * Metabox - Recipe Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

$recipe = [ [ 'rank_math_rich_snippet', 'recipe' ] ];

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_type',
	'type'    => 'text',
	'name'    => esc_html__( 'Type', 'schema-markup' ),
	'desc'    => esc_html__( 'Type of dish, for example "appetizer", or "dessert".', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_cuisine',
	'type'    => 'text',
	'name'    => esc_html__( 'Cuisine', 'schema-markup' ),
	'desc'    => esc_html__( 'The cuisine of the recipe (for example, French or Ethiopian).', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_keywords',
	'type'    => 'text',
	'name'    => esc_html__( 'Keywords', 'schema-markup' ),
	'desc'    => esc_html__( 'Other terms for your recipe such as the season, the holiday, or other descriptors. Separate multiple entries with commas.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_yield',
	'type'    => 'text',
	'name'    => esc_html__( 'Recipe Yield', 'schema-markup' ),
	'desc'    => esc_html__( 'Quantity produced by the recipe, for example "4 servings"', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_calories',
	'type'    => 'text',
	'name'    => esc_html__( 'Calories', 'schema-markup' ),
	'desc'    => esc_html__( 'The number of calories in the recipe.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_preptime',
	'type'    => 'text',
	'name'    => esc_html__( 'Preparation Time', 'schema-markup' ),
	'desc'    => esc_html__( 'Example: 1H30M', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_cooktime',
	'type'    => 'text',
	'name'    => esc_html__( 'Cooking Time', 'schema-markup' ),
	'desc'    => esc_html__( 'Example: 1H30M', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_totaltime',
	'type'    => 'text',
	'name'    => esc_html__( 'Total Time', 'schema-markup' ),
	'desc'    => esc_html__( 'Example: 1H30M', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_rating',
	'type'    => 'text',
	'name'    => esc_html__( 'Rating', 'schema-markup' ),
	'desc'    => esc_html__( 'Rating score of the recipe.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_rating_min',
	'type'    => 'text',
	'name'    => esc_html__( 'Rating Minimum', 'schema-markup' ),
	'desc'    => esc_html__( 'Rating minimum score of the recipe.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_rating_max',
	'type'    => 'text',
	'name'    => esc_html__( 'Rating Maximum', 'schema-markup' ),
	'desc'    => esc_html__( 'Rating maximum score of the recipe.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_video',
	'type'    => 'text_url',
	'name'    => esc_html__( 'Recipe Video', 'schema-markup' ),
	'desc'    => esc_html__( 'A recipe video URL.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_video_thumbnail',
	'type'    => 'text_url',
	'name'    => esc_html__( 'Recipe Video Thumbnail', 'schema-markup' ),
	'desc'    => esc_html__( 'A recipe video thumbnail URL.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_video_name',
	'type'    => 'text',
	'name'    => esc_html__( 'Recipe Video Name', 'schema-markup' ),
	'desc'    => esc_html__( 'A recipe video Name.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_video_date',
	'type'    => 'text_date',
	'name'    => esc_html__( 'Video Upload Date', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_recipe_video_description',
	'type'       => 'textarea',
	'name'       => esc_html__( 'Recipe Video Description', 'schema-markup' ),
	'desc'       => esc_html__( 'A recipe video Description.', 'schema-markup' ),
	'classes'    => 'cmb-row-50',
	'attributes' => [
		'rows'            => 4,
		'data-autoresize' => true,
	],
	'dep'        => $recipe,
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_recipe_ingredients',
	'type'       => 'textarea',
	'name'       => esc_html__( 'Recipe Ingredients', 'schema-markup' ),
	'desc'       => esc_html__( 'Recipe ingredients, add one item per line', 'schema-markup' ),
	'attributes' => [
		'rows'            => 4,
		'data-autoresize' => true,
	],
	'classes'    => 'cmb-row-50',
	'dep'        => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_instruction_type',
	'type'    => 'radio_inline',
	'name'    => esc_html__( 'Instruction Type', 'schema-markup' ),
	'options' => [
		'SingleField'  => esc_html__( 'Single Field', 'schema-markup' ),
		'HowToStep'    => esc_html__( 'How To Step', 'schema-markup' ),
		'HowToSection' => esc_html__( 'How To Section', 'schema-markup' ),
	],
	'classes' => 'recipe-instruction-type',
	'default' => 'SingleField',
	'dep'     => $recipe,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_instruction_name',
	'type'    => 'text',
	'name'    => esc_html__( 'Recipe Instruction Name', 'schema-markup' ),
	'desc'    => esc_html__( 'Instruction name of the recipe.', 'schema-markup' ),
	'classes' => 'nob',
	'dep'     => [
		'relation' => 'and',
		[ 'rank_math_rich_snippet', 'recipe' ],
		[ 'rank_math_snippet_recipe_instruction_type', 'HowToStep' ],
	],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_recipe_single_instructions',
	'type'       => 'textarea',
	'name'       => esc_html__( 'Recipe Instructions', 'schema-markup' ),
	'attributes' => [
		'rows'            => 4,
		'data-autoresize' => true,
	],
	'classes'    => 'nob',
	'dep'        => [
		'relation' => 'and',
		[ 'rank_math_rich_snippet', 'recipe' ],
		[ 'rank_math_snippet_recipe_instruction_type', 'HowToStep,SingleField' ],
	],
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_recipe_instructions',
	'type'    => 'group',
	'name'    => esc_html__( 'Recipe Instructions', 'schema-markup' ),
	'desc'    => esc_html__( 'Steps to take, add one instruction per line', 'schema-markup' ),
	'options' => [
		'closed'        => true,
		'sortable'      => true,
		'add_button'    => esc_html__( 'Add New Instruction', 'schema-markup' ),
		'group_title'   => esc_html__( 'Instruction {#}', 'schema-markup' ),
		'remove_button' => esc_html__( 'Remove', 'schema-markup' ),
	],
	'classes' => 'cmb-group-fix-me nob',
	'dep'     => [
		'relation' => 'and',
		[ 'rank_math_rich_snippet', 'recipe' ],
		[ 'rank_math_snippet_recipe_instruction_type', 'HowToSection' ],
	],
	'fields'  => [
		[
			'id'   => 'name',
			'type' => 'text',
			'name' => esc_html__( 'Name', 'schema-markup' ),
			'desc' => esc_html__( 'Instruction name of the recipe.', 'schema-markup' ),
		],
		[
			'id'         => 'text',
			'type'       => 'textarea',
			'name'       => esc_html__( 'Text', 'schema-markup' ),
			'attributes' => [
				'rows'            => 4,
				'data-autoresize' => true,
			],
			'desc'       => esc_html__( 'Steps to take, add one instruction per line', 'schema-markup' ),
		],
	],
]);
