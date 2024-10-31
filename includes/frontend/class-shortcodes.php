<?php
/**
 * The Shortcodes of the plugin.
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\Frontend
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Frontend;

use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Traits\Hooker;
use RANKMATH_SCHEMA\Traits\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes class.
 */
class Shortcodes {

	use Hooker, Shortcode;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->action( 'init', 'init' );
	}

	/**
	 * Initialize.
	 */
	public function init() {

		// Review shortcode.
		$this->add_shortcode( 'rank_math_contact_info', 'contact_info' );
	}

	/**
	 * Contact info shortcode, displays nicely formatted contact informations.
	 *
	 * @param  array $args Optional. Shortcode arguments - currently only 'show'
	 *                     parameter, which is a comma-separated list of elements to show.
	 * @return string Shortcode output.
	 */
	public function contact_info( $args ) {
		$args = shortcode_atts(
			[
				'show'  => 'all',
				'class' => '',
			],
			$args,
			'contact-info'
		);

		$allowed = [ 'address', 'hours', 'phone', 'social', 'map' ];
		if ( 'person' === Helper::get_settings( 'titles.knowledgegraph_type' ) ) {
			$allowed = [ 'address' ];
		}

		if ( ! empty( $args['show'] ) && 'all' !== $args['show'] ) {
			$allowed = array_intersect( array_map( 'trim', explode( ',', $args['show'] ) ), $allowed );
		}

		wp_enqueue_style( 'rank-math-contact-info', rank_math_schema()->assets() . 'css/rank-math-contact-info.css', null, rank_math_schema()->version );

		ob_start();
		echo '<div class="' . $this->get_contact_classes( $allowed, $args['class'] ) . '">';

		foreach ( $allowed as $element ) {
			$method = 'display_' . $element;
			if ( method_exists( $this, $method ) ) {
				echo '<div class="rank-math-contact-section rank-math-contact-' . $element . '">';
				$this->$method();
				echo '</div>';
			}
		}

		echo '</div>';
		echo '<div class="clear"></div>';

		return ob_get_clean();
	}

	/**
	 * Get contact info container classes.
	 *
	 * @param  array $allowed     Allowed elements.
	 * @param  array $extra_class Shortcode arguments.
	 * @return string
	 */
	private function get_contact_classes( $allowed, $extra_class ) {
		$classes = [ 'rank-math-contact-info', $extra_class ];
		foreach ( $allowed as $elem ) {
			$classes[] = sanitize_html_class( 'show-' . $elem );
		}
		if ( count( $allowed ) === 1 ) {
			$classes[] = sanitize_html_class( 'show-' . $elem . '-only' );
		}

		return join( ' ', array_filter( $classes ) );
	}

	/**
	 * Output address.
	 */
	private function display_address() {
		$address = Helper::get_settings( 'titles.local_address' );
		if ( false === $address ) {
			return;
		}

		$format = nl2br( Helper::get_settings( 'titles.local_address_format' ) );
		/**
		 * Allow developer to change the address part format.
		 *
		 * @param string $parts_format String format  how to output address part.
		 */
		$parts_format = $this->do_filter( 'shortcode/contact/address_parts_format', '<span class="contact-address-%1$s">%2$s</span>' );

		$hash = [
			'streetAddress'   => 'address',
			'addressLocality' => 'locality',
			'addressRegion'   => 'region',
			'postalCode'      => 'postalcode',
			'addressCountry'  => 'country',
		];
		?>
		<label><?php esc_html_e( 'Address:', 'schema-markup' ); ?></label>
		<address>
			<?php
			foreach ( $hash as $key => $tag ) {
				$value = '';
				if ( isset( $address[ $key ] ) && ! empty( $address[ $key ] ) ) {
					$value = sprintf( $parts_format, $tag, $address[ $key ] );
				}

				$format = str_replace( "{{$tag}}", $value, $format );
			}

			echo $format;
			?>
		</address>
		<?php
	}

	/**
	 * Output opening hours.
	 */
	private function display_hours() {
		$hours = Helper::get_settings( 'titles.opening_hours' );
		if ( ! isset( $hours[0]['time'] ) ) {
			return;
		}

		$combined = $this->get_hours_combined( $hours );
		$format   = Helper::get_settings( 'titles.opening_hours_format' );
		?>
		<label><?php esc_html_e( 'Hours:', 'schema-markup' ); ?></label>
		<div class="rank-math-contact-hours-details">
			<?php
			foreach ( $combined as $time => $days ) {
				if ( $format ) {
					$hours = explode( '-', $time );
					$time  = date( 'g:i a', strtotime( $hours[0] ) ) . '-' . date( 'g:i a', strtotime( $hours[1] ) );
				}
				$time = str_replace( '-', ' &ndash; ', $time );

				printf(
					'<div class="rank-math-opening-hours"><span class="rank-math-opening-days">%1$s</span><span class="rank-math-opening-time">%2$s</span></div>',
					join( ', ', $days ), $time
				);
			}
			?>
		</div>
		<?php
	}

	/**
	 * Combine hours in an hour
	 *
	 * @param  array $hours Hours to combine.
	 * @return array
	 */
	private function get_hours_combined( $hours ) {
		$combined = [];

		foreach ( $hours as $hour ) {
			if ( empty( $hour['time'] ) ) {
				continue;
			}

			$combined[ trim( $hour['time'] ) ][] = $hour['day'];
		}

		return $combined;
	}

	/**
	 * Output phone numbers.
	 */
	private function display_phone() {
		$phones = Helper::get_settings( 'titles.phone_numbers' );
		if ( ! isset( $phones[0]['number'] ) ) {
			return;
		}

		foreach ( $phones as $phone ) :
			$number = esc_html( $phone['number'] );
			?>
			<div class="rank-math-phone-number type-<?php echo sanitize_html_class( $phone['type'] ); ?>">
				<label><?php echo ucwords( $phone['type'] ); ?>:</label> <span><?php echo isset( $phone['number'] ) ? '<a href="tel://' . $number . '">' . $number . '</a>' : ''; ?></span>
			</div>
			<?php
		endforeach;
	}

	/**
	 * Output social identities.
	 */
	private function display_social() {
		$networks = [
			'facebook'      => 'Facebook',
			'twitter'       => 'Twitter',
			'google_places' => 'Google Places',
			'yelp'          => 'Yelp',
			'foursquare'    => 'FourSquare',
			'flickr'        => 'Flickr',
			'reddit'        => 'Reddit',
			'linkedin'      => 'LinkedIn',
			'instagram'     => 'Instagram',
			'youtube'       => 'YouTube',
			'pinterest'     => 'Pinterest',
			'soundcloud'    => 'SoundClound',
			'tumblr'        => 'Tumblr',
			'myspace'       => 'MySpace',
		];
		?>
		<div class="rank-math-social-networks">
			<?php
			foreach ( $networks as $id => $label ) :
				if ( $url = Helper::get_settings( 'titles.social_url_' . $id ) ) : // phpcs:ignore
					?>
					<a class="social-item type-<?php echo $id; ?>" href="<?php echo esc_url( $url ); ?>"><?php echo $label; ?></a>
					<?php
				endif;
			endforeach;
			?>
		</div>
		<?php
	}

	/**
	 * Output google map.
	 */
	private function display_map() {
		$address = Helper::get_settings( 'titles.local_address' );
		if ( false === $address ) {
			return;
		}

		/**
		 * Filter address for Google Map in contact shortcode
		 *
		 * @param string $address
		 */
		$address = $this->do_filter( 'shortcode/contact/map_address', implode( ' ', $address ) );
		$address = $this->do_filter( 'shortcode/contact/map_iframe_src', 'http://maps.google.com/maps?q=' . urlencode( $address ) . '&z=15&output=embed&key=' . urlencode( Helper::get_settings( 'titles.maps_api_key' ) ) );
		?>
		<iframe src="<?php echo esc_url( $address ); ?>"></iframe>
		<?php
	}
}
