<?php
/**
 * The Rich Snippet Shortcode
 *
 * @since      1.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Traits\Hooker;
use RANKMATH_SCHEMA\Traits\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Snippet_Shortcode class.
 */
class Snippet_Shortcode {

	use Hooker, Shortcode;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->add_shortcode( 'rank_math_rich_snippet', 'rich_snippet' );

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'rank-math/rich-snippet',
			[
				'render_callback' => [ $this, 'rich_snippet' ],
				'attributes'      => [
					'id' => [
						'default' => '',
						'type'    => 'integer',
					],
				],
			]
		);
	}

	/**
	 * Rich Snippet shortcode.
	 *
	 * @param  array $atts Optional. Shortcode arguments - currently only 'show'
	 *                     parameter, which is a comma-separated list of elements to show.
	 *
	 * @return string Shortcode output.
	 */
	public function rich_snippet( $atts ) {
		$atts = shortcode_atts(
			[ 'id' => get_the_ID() ],
			$atts,
			'rank_math_rich_snippet'
		);

		$post = get_post( $atts['id'] );
		if ( empty( $post ) ) {
			return esc_html__( 'Post ID does not exists or was deleted.', 'schema-markup' );
		}

		return $this->do_filter( 'snippet/html', $this->get_snippet_content( $post ) );
	}

	/**
	 * Get Snippet content.
	 *
	 * @param WP_Post $post Post Object.
	 *
	 * @return string Shortcode output.
	 */
	public function get_snippet_content( $post ) {
		$schema = Helper::get_post_meta( 'rich_snippet', $post->ID );
		if ( ! $this->get_fields( $schema ) ) {
			return __( 'Snippet not selected.', 'schema-markup' );
		}

		wp_enqueue_style( 'rank-math-review-snippet', rank_math_schema()->assets() . 'css/rank-math-snippet.css', null, rank_math_schema()->version );

		// Title.
		$title = Helper::get_post_meta( 'snippet_name', $post->ID );
		$title = $title ? $title : Helper::replace_vars( '%title%', $post );

		// Description.
		$excerpt = Helper::replace_vars( '%excerpt%', $post );
		$desc    = Helper::get_post_meta( 'snippet_desc', $post->ID );
		$desc    = $desc ? $desc : ( $excerpt ? $excerpt : Helper::get_post_meta( 'description', $post->ID ) );

		// Image.
		$image = Helper::get_thumbnail_with_fallback( $post->ID, 'medium' );

		ob_start();
		?>
			<div id="rank-math-rich-snippet-wrapper">

				<h5 class="rank-math-title"><?php echo $title; ?></h5>

				<?php if ( ! empty( $image ) ) { ?>
					<div class="rank-math-review-image">
						<img src="<?php echo esc_url( $image[0] ); ?>" />
					</div>
				<?php } ?>

				<div class="rank-math-review-data">
					<p><?php echo $desc; ?></p>
					<?php
					foreach ( $this->get_fields( $schema ) as $id => $field ) {
						$this->get_field_content( $id, $field, $post );
					}
					?>
				</div>

			</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get Field Content.
	 *
	 * @param string  $id    Field ID.
	 * @param string  $field Field Name.
	 * @param WP_Post $post  Post Object.
	 */
	public function get_field_content( $id, $field, $post ) {
		if ( 'is_rating' === $id ) {
			$this->show_ratings( $post->ID, $field );
			return;
		}

		if ( ! $value = Helper::get_post_meta( "snippet_{$id}", $post->ID ) ) { // phpcs:ignore
			return;
		}
		?>
		<p>
			<strong><?php echo $field; ?>: </strong>
			<?php
			if ( in_array( $id, [ 'recipe_instructions', 'book_editions' ], true ) ) {
				$perform = "get_{$id}";
				$this->$perform( $value );
				return;
			}

			echo is_array( $value ) ? implode( ', ', $value ) : esc_html( $value );
			?>
		</p>
		<?php
	}

	/**
	 * Get Recipe Instructions.
	 *
	 * @param array $value Recipe instructions.
	 */
	public function get_recipe_instructions( $value ) {
		foreach ( $value as $key => $data ) {
			echo '<p><strong>' . $data['name'] . ': </strong>' . $data['text'] . '</p>';
		}
	}

	/**
	 * Get Book Editions.
	 *
	 * @param array $value Book editions.
	 */
	public function get_book_editions( $value ) {
		$hash = [
			'book_edition'   => __( 'Edition', 'schema-markup' ),
			'name'           => __( 'Name', 'schema-markup' ),
			'author'         => __( 'Author', 'schema-markup' ),
			'isbn'           => __( 'ISBN', 'schema-markup' ),
			'date_published' => __( 'Date Published', 'schema-markup' ),
			'book_format'    => __( 'Format', 'schema-markup' ),
		];
		foreach ( $value as $data ) {
			echo '<p>';
			foreach ( $hash as $id => $field ) {
				echo isset( $data[ $id ] ) ? "<strong>{$field} : </strong> {$data[ $id ]} <br />" : '';
			}
			echo '</p>';
		}

	}

	/**
	 * Display nicely formatted reviews.
	 *
	 * @param int   $post_id The Post ID.
	 * @param array $field   Array of review value and count field.
	 */
	public function show_ratings( $post_id, $field ) {
		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', null, rank_math_schema()->version );
		$rating = isset( $field['value'] ) ? Helper::get_post_meta( "snippet_{$field['value']}", $post_id ) : '';
		$count  = isset( $field['count'] ) ? Helper::get_post_meta( $field['count'], $post_id ) : '';
		?>
		<div class="rank-math-total-wrapper">

			<strong><?php echo $this->do_filter( 'review/text', esc_html__( 'Editor\'s Rating:', 'schema-markup' ) ); ?></strong><br />

			<span class="rank-math-total"><?php echo $rating; ?></span>

			<div class="rank-math-review-star">

				<div class="rank-math-review-result-wrapper">

					<?php echo \str_repeat( '<i class="fa fa-star"></i>', 5 ); ?>

					<div class="rank-math-review-result" style="width:<?php echo ( $rating * 20 ); ?>%;">
						<?php echo \str_repeat( '<i class="fa fa-star"></i>', 5 ); ?>
					</div>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Contact info shortcode, displays nicely formatted contact informations.
	 *
	 * @param string $type Snippet type.
	 *
	 * @return array Array of snippet fields.
	 */
	public function get_fields( $type ) {
		$fields = [
			'course'     => [
				'course_provider_type' => esc_html__( 'Course Provider', 'schema-markup' ),
				'course_provider'      => esc_html__( 'Course Provider Name', 'schema-markup' ),
				'course_provider_url'  => esc_html__( 'Course Provider URL', 'schema-markup' ),
			],
			'event'      => [
				'url'                            => esc_html__( 'URL', 'schema-markup' ),
				'event_type'                     => esc_html__( 'Event Type', 'schema-markup' ),
				'event_venue'                    => esc_html__( 'Venue Name', 'schema-markup' ),
				'event_venue_url'                => esc_html__( 'Venue URL', 'schema-markup' ),
				'event_address'                  => esc_html__( 'Address', 'schema-markup' ),
				'event_performer_type'           => esc_html__( 'Performer', 'schema-markup' ),
				'event_performer'                => esc_html__( 'Performer Name', 'schema-markup' ),
				'event_performer_url'            => esc_html__( 'Performer URL', 'schema-markup' ),
				'event_status'                   => esc_html__( 'Event Status', 'schema-markup' ),
				'event_startdate_date'           => esc_html__( 'Start Date', 'schema-markup' ),
				'event__enddate'                 => esc_html__( 'End Date', 'schema-markup' ),
				'event_ticketurl'                => esc_html__( 'Ticket URL', 'schema-markup' ),
				'event_price'                    => esc_html__( 'Entry Price', 'schema-markup' ),
				'event_currency'                 => esc_html__( 'Currency', 'schema-markup' ),
				'event_availability'             => esc_html__( 'Availability', 'schema-markup' ),
				'event_availability_starts_date' => esc_html__( 'Availability Starts', 'schema-markup' ),
				'event_inventory'                => esc_html__( 'Stock Inventory', 'schema-markup' ),
			],
			'jobposting' => [
				'jobposting_salary'          => esc_html__( 'Salary', 'schema-markup' ),
				'jobposting_currency'        => esc_html__( 'Salary Currency', 'schema-markup' ),
				'jobposting_payroll'         => esc_html__( 'Payroll', 'schema-markup' ),
				'jobposting_startdate'       => esc_html__( 'Date Posted', 'schema-markup' ),
				'jobposting_expirydate'      => esc_html__( 'Expiry Posted', 'schema-markup' ),
				'jobposting_unpublish'       => esc_html__( 'Unpublish when expired', 'schema-markup' ),
				'jobposting_employment_type' => esc_html__( 'Employment Type ', 'schema-markup' ),
				'jobposting_organization'    => esc_html__( 'Hiring Organization ', 'schema-markup' ),
				'jobposting_id'              => esc_html__( 'Posting ID', 'schema-markup' ),
				'jobposting_url'             => esc_html__( 'Organization URL', 'schema-markup' ),
				'jobposting_logo'            => esc_html__( 'Organization Logo', 'schema-markup' ),
				'jobposting_address'         => esc_html__( 'Location', 'schema-markup' ),
			],
			'music'      => [
				'url'        => esc_html__( 'URL', 'schema-markup' ),
				'music_type' => esc_html__( 'Type', 'schema-markup' ),
			],
			'product'    => [
				'product_sku'         => esc_html__( 'Product SKU', 'schema-markup' ),
				'product_brand'       => esc_html__( 'Product Brand', 'schema-markup' ),
				'product_currency'    => esc_html__( 'Product Currency', 'schema-markup' ),
				'product_price'       => esc_html__( 'Product Price', 'schema-markup' ),
				'product_price_valid' => esc_html__( 'Price Valid Until', 'schema-markup' ),
				'product_instock'     => esc_html__( 'Product In-Stock', 'schema-markup' ),
			],
			'recipe'     => [
				'recipe_type'                => esc_html__( 'Type', 'schema-markup' ),
				'recipe_cuisine'             => esc_html__( 'Cuisine', 'schema-markup' ),
				'recipe_keywords'            => esc_html__( 'Keywords', 'schema-markup' ),
				'recipe_yield'               => esc_html__( 'Recipe Yield', 'schema-markup' ),
				'recipe_calories'            => esc_html__( 'Calories', 'schema-markup' ),
				'recipe_preptime'            => esc_html__( 'Preparation Time', 'schema-markup' ),
				'recipe_cooktime'            => esc_html__( 'Cooking Time', 'schema-markup' ),
				'recipe_totaltime'           => esc_html__( 'Total Time', 'schema-markup' ),
				'recipe_rating'              => esc_html__( 'Rating', 'schema-markup' ),
				'recipe_rating_min'          => esc_html__( 'Rating Minimum', 'schema-markup' ),
				'recipe_rating_max'          => esc_html__( 'Rating Maximum', 'schema-markup' ),
				'recipe_video'               => esc_html__( 'Recipe Video', 'schema-markup' ),
				'recipe_video_thumbnail'     => esc_html__( 'Recipe Video Thumbnail', 'schema-markup' ),
				'recipe_video_name'          => esc_html__( 'Recipe Video Name', 'schema-markup' ),
				'recipe_video_date'          => esc_html__( 'Video Upload Date', 'schema-markup' ),
				'recipe_video_description'   => esc_html__( 'Recipe Video Description', 'schema-markup' ),
				'recipe_ingredients'         => esc_html__( 'Recipe Ingredients', 'schema-markup' ),
				'recipe_instruction_name'    => esc_html__( 'Recipe Instruction Name', 'schema-markup' ),
				'recipe_single_instructions' => esc_html__( 'Recipe Instructions', 'schema-markup' ),
				'recipe_instructions'        => esc_html__( 'Recipe Instructions', 'schema-markup' ),
			],
			'restaurant' => [
				'local_address'             => esc_html__( 'Address', 'schema-markup' ),
				'local_geo'                 => esc_html__( 'Geo Coordinates', 'schema-markup' ),
				'local_phone'               => esc_html__( 'Phone Number', 'schema-markup' ),
				'local_price_range'         => esc_html__( 'Price Range', 'schema-markup' ),
				'local_opens'               => esc_html__( 'Opening Time', 'schema-markup' ),
				'local_closes'              => esc_html__( 'Closing Time', 'schema-markup' ),
				'local_opendays'            => esc_html__( 'Open Days', 'schema-markup' ),
				'restaurant_serves_cuisine' => esc_html__( 'Serves Cuisine', 'schema-markup' ),
				'restaurant_menu'           => esc_html__( 'Menu URL', 'schema-markup' ),
			],
			'video'      => [
				'video_url'       => esc_html__( 'Content URL', 'schema-markup' ),
				'video_embed_url' => esc_html__( 'Embed URL', 'schema-markup' ),
				'video_duration'  => esc_html__( 'Duration', 'schema-markup' ),
				'video_views'     => esc_html__( 'Views', 'schema-markup' ),
			],
			'person'     => [
				'person_email'     => esc_html__( 'Email', 'schema-markup' ),
				'person_address'   => esc_html__( 'Address', 'schema-markup' ),
				'person_gender'    => esc_html__( 'Gender', 'schema-markup' ),
				'person_job_title' => esc_html__( 'Job Title', 'schema-markup' ),
			],
			'review'     => [
				'is_rating' => [
					'value' => 'review_rating_value',
				],
			],
			'service'    => [
				'service_type'           => esc_html__( 'Service Type', 'schema-markup' ),
				'service_price'          => esc_html__( 'Price', 'schema-markup' ),
				'service_price_currency' => esc_html__( 'Currency', 'schema-markup' ),
				'is_rating'              => [
					'value' => 'service_rating_value',
					'count' => 'service_rating_count',
				],
			],
			'software'   => [
				'software_price'                => esc_html__( 'Price', 'schema-markup' ),
				'software_price_currency'       => esc_html__( 'Price Currency', 'schema-markup' ),
				'software_operating_system'     => esc_html__( 'Operating System', 'schema-markup' ),
				'software_application_category' => esc_html__( 'Application Category', 'schema-markup' ),
				'is_rating'                     => [
					'value' => 'software_rating_value',
					'count' => 'software_rating_count',
				],
			],
			'book'       => [
				'url'           => esc_html__( 'URL', 'schema-markup' ),
				'author'        => esc_html__( 'Author', 'schema-markup' ),
				'book_editions' => esc_html__( 'Book Editions', 'schema-markup' ),
			],
		];

		return isset( $fields[ $type ] ) ? apply_filters( 'rank_math/snippet/fields', $fields[ $type ] ) : false;
	}
}
