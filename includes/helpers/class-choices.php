<?php
/**
 * The Choices helpers.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath\Helpers
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\Helpers;

use RANKMATH_SCHEMA\Helper;
use RANKMATH_SCHEMA\Admin\Admin_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Choices class.
 */
trait Choices {

  /**
   * Gets list of overlay images for social thumbnail.
   *
   * @codeCoverageIgnore
   *
   * @param  string $output Output type.
   * @return array
   */
  public static function choices_overlay_images( $output = 'object' ) {
    $uri = rank_math_schema()->plugin_url() . 'assets/admin/img/';
    $dir = rank_math_schema()->plugin_dir() . 'assets/admin/img/';

    $list = apply_filters( 'rank_math/social/overlay_images', array(

      'play' => array(
        'name' => esc_html__( 'Play icon', 'schema-markup' ),
        'url'  => $uri . 'icon-play.png',
        'path' => $dir . 'icon-play.png',
      ),

      'gif'  => array(
        'name' => esc_html__( 'GIF icon', 'schema-markup' ),
        'url'  => $uri . 'icon-gif.png',
        'path' => $dir . 'icon-gif.png',
      ),
    ));

    return 'names' === $output ? wp_list_pluck( $list, 'name' ) : $list;
  }

  /**
   * Get business type as choices.
   *
   * @codeCoverageIgnore
   *
   * @param  bool $none Add none option to list.
   * @return array
   */
  public static function choices_business_types( $none = false ) {
    $data = apply_filters( 'rank_math_schema/json_ld/business_types', array(
      array( 'label' => 'Airport' ),
      array( 'label' => 'Animal Shelter' ),
      array( 'label' => 'Aquarium' ),
      array(
        'label' => 'Automotive Business',
        'child' => array(
          array( 'label' => 'Auto Body Shop' ),
          array( 'label' => 'Auto Dealer' ),
          array( 'label' => 'Auto Parts Store' ),
          array( 'label' => 'Auto Rental' ),
          array( 'label' => 'Auto Repair' ),
          array( 'label' => 'Auto Wash' ),
          array( 'label' => 'Gas Station' ),
          array( 'label' => 'Motorcycle Dealer' ),
          array( 'label' => 'Motorcycle Repair' ),
        ),
      ),
      array( 'label' => 'Beach' ),
      array( 'label' => 'Bus Station' ),
      array( 'label' => 'BusStop' ),
      array( 'label' => 'Campground' ),
      array( 'label' => 'Cemetery' ),
      array( 'label' => 'Child Care' ),
      array( 'label' => 'Corporation' ),
      array( 'label' => 'Crematorium' ),
      array( 'label' => 'Dry Cleaning or Laundry' ),
      array(
        'label' => 'Educational Organization',
        'child' => array(
          array( 'label' => 'College or University' ),
          array( 'label' => 'Elementary School' ),
          array( 'label' => 'High School' ),
          array( 'label' => 'Middle School' ),
          array( 'label' => 'Preschool' ),
          array( 'label' => 'School' ),
        ),
      ),
      array(
        'label' => 'Emergency Service',
        'child' => array(
          array( 'label' => 'Fire Station' ),
          array( 'label' => 'Hospital' ),
          array( 'label' => 'Police Station' ),
        ),
      ),
      array( 'label' => 'Employment Agency' ),
      array(
        'label' => 'Entertainment Business',
        'child' => array(
          array( 'label' => 'Adult Entertainment' ),
          array( 'label' => 'Amusement Park' ),
          array( 'label' => 'Art Gallery' ),
          array( 'label' => 'Casino' ),
          array( 'label' => 'Comedy Club' ),
          array( 'label' => 'Movie Theater' ),
          array( 'label' => 'Night Club' ),
        ),
      ),
      array( 'label' => 'Event Venue' ),
      array(
        'label' => 'Financial Service',
        'child' => array(
          array( 'label' => 'Accounting Service' ),
          array( 'label' => 'Automated Teller' ),
          array( 'label' => 'Bank or Credit Union' ),
          array( 'label' => 'Insurance Agency' ),
        ),
      ),
      array( 'label' => 'Fire Station' ),
      array(
        'label' => 'Food Establishment',
        'child' => array(
          array( 'label' => 'Bakery' ),
          array( 'label' => 'Bar or Pub' ),
          array( 'label' => 'Brewery' ),
          array( 'label' => 'Cafe or Coffee Shop' ),
          array( 'label' => 'Fast Food Restaurant' ),
          array( 'label' => 'Ice Cream Shop' ),
          array( 'label' => 'Restaurant' ),
          array( 'label' => 'Winery' ),
        ),
      ),
      array(
        'label' => 'Government Building',
        'child' => array(
          array( 'label' => 'City Hall' ),
          array( 'label' => 'Courthouse' ),
          array( 'label' => 'Defence Establishment' ),
          array( 'label' => 'Embassy' ),
          array( 'label' => 'Legislative Building' ),
        ),
      ),
      array(
        'label' => 'Government Office',
        'child' => array(
          array( 'label' => 'Post Office' ),
        ),
      ),
      array( 'label' => 'Government Organization' ),
      array(
        'label' => 'Health And Beauty Business',
        'child' => array(
          array( 'label' => 'Beauty Salon' ),
          array( 'label' => 'Day Spa' ),
          array( 'label' => 'Hair Salon' ),
          array( 'label' => 'Health Club' ),
          array( 'label' => 'Nail Salon' ),
          array( 'label' => 'Tattoo Parlor' ),
        ),
      ),
      array(
        'label' => 'Home And Construction Business',
        'child' => array(
          array( 'label' => 'Electrician' ),
          array( 'label' => 'General Contractor' ),
          array( 'label' => 'HVAC Business' ),
          array( 'label' => 'House Painter' ),
          array( 'label' => 'Locksmith' ),
          array( 'label' => 'Moving Company' ),
          array( 'label' => 'Plumber' ),
          array( 'label' => 'Roofing Contractor' ),
        ),
      ),
      array( 'label' => 'Hospital' ),
      array( 'label' => 'Internet Cafe' ),
      array( 'label' => 'Library' ),
      array( 'label' => 'Local Business' ),
      array(
        'label' => 'Lodging Business',
        'child' => array(
          array( 'label' => 'Bed And Breakfast' ),
          array( 'label' => 'Hostel' ),
          array( 'label' => 'Hotel' ),
          array( 'label' => 'Motel' ),
        ),
      ),
      array(
        'label' => 'Medical Organization',
        'child' => array(
          array( 'label' => 'Dentist' ),
          array( 'label' => 'Diagnostic Lab' ),
          array( 'label' => 'Hospital' ),
          array( 'label' => 'Medical Clinic' ),
          array( 'label' => 'Optician' ),
          array( 'label' => 'Pharmacy' ),
          array( 'label' => 'Physician' ),
          array( 'label' => 'Veterinary Care' ),
        ),
      ),
      array( 'label' => 'Movie Theater' ),
      array( 'label' => 'Museum' ),
      array( 'label' => 'Music Venue' ),
      array( 'label' => 'NGO' ),
      array( 'label' => 'Organization' ),
      array( 'label' => 'Park' ),
      array( 'label' => 'Parking Facility' ),
      array( 'label' => 'Performing Arts Theater' ),
      array(
        'label' => 'Performing Group',
        'child' => array(
          array( 'label' => 'Dance Group' ),
          array( 'label' => 'Music Group' ),
          array( 'label' => 'Theater Group' ),
        ),
      ),
      array(
        'label' => 'Place Of Worship',
        'child' => array(
          array( 'label' => 'Buddhist Temple' ),
          array( 'label' => 'Catholic Church' ),
          array( 'label' => 'Church' ),
          array( 'label' => 'Hindu Temple' ),
          array( 'label' => 'Mosque' ),
          array( 'label' => 'Synagogue' ),
        ),
      ),
      array( 'label' => 'Playground' ),
      array( 'label' => 'PoliceStation' ),
      array(
        'label' => 'Professional Service',
        'child' => array(
          array( 'label' => 'Accounting Service' ),
          array( 'label' => 'Legal Service' ),
          array( 'label' => 'Dentist' ),
          array( 'label' => 'Electrician' ),
          array( 'label' => 'General Contractor' ),
          array( 'label' => 'House Painter' ),
          array( 'label' => 'Locksmith' ),
          array( 'label' => 'Notary' ),
          array( 'label' => 'Plumber' ),
          array( 'label' => 'Roofing Contractor' ),
        ),
      ),
      array( 'label' => 'Radio Station' ),
      array( 'label' => 'Real Estate Agent' ),
      array( 'label' => 'Recycling Center' ),
      array(
        'label' => 'Residence',
        'child' => array(
          array( 'label' => 'Apartment Complex' ),
          array( 'label' => 'Gated Residence Community' ),
          array( 'label' => 'Single Family Residence' ),
        ),
      ),
      array( 'label' => 'RV Park' ),
      array( 'label' => 'Self Storage' ),
      array( 'label' => 'Shopping Center' ),
      array(
        'label' => 'Sports Activity Location',
        'child' => array(
          array( 'label' => 'Bowling Alley' ),
          array( 'label' => 'Exercise Gym' ),
          array( 'label' => 'Golf Course' ),
          array( 'label' => 'Health Club' ),
          array( 'label' => 'Public Swimming Pool' ),
          array( 'label' => 'Ski Resort' ),
          array( 'label' => 'Sports Club' ),
          array( 'label' => 'Stadium or Arena' ),
          array( 'label' => 'Tennis Complex' ),
        ),
      ),
      array( 'label' => 'Sports Team' ),
      array( 'label' => 'Stadium Or Arena' ),
      array(
        'label' => 'Store',
        'child' => array(
          array( 'label' => 'Auto Parts Store' ),
          array( 'label' => 'Bike Store' ),
          array( 'label' => 'Book Store' ),
          array( 'label' => 'Clothing Store' ),
          array( 'label' => 'Computer Store' ),
          array( 'label' => 'Convenience Store' ),
          array( 'label' => 'Department Store' ),
          array( 'label' => 'Electronics Store' ),
          array( 'label' => 'Florist' ),
          array( 'label' => 'Furniture Store' ),
          array( 'label' => 'Garden Store' ),
          array( 'label' => 'Grocery Store' ),
          array( 'label' => 'Hardware Store' ),
          array( 'label' => 'Hobby Shop' ),
          array( 'label' => 'HomeGoods Store' ),
          array( 'label' => 'Jewelry Store' ),
          array( 'label' => 'Liquor Store' ),
          array( 'label' => 'Mens Clothing Store' ),
          array( 'label' => 'Mobile Phone Store' ),
          array( 'label' => 'Movie Rental Store' ),
          array( 'label' => 'Music Store' ),
          array( 'label' => 'Office Equipment Store' ),
          array( 'label' => 'Outlet Store' ),
          array( 'label' => 'Pawn Shop' ),
          array( 'label' => 'Pet Store' ),
          array( 'label' => 'Shoe Store' ),
          array( 'label' => 'Sporting Goods Store' ),
          array( 'label' => 'Tire Shop' ),
          array( 'label' => 'Toy Store' ),
          array( 'label' => 'Wholesale Store' ),
        ),
      ),
      array( 'label' => 'Subway Station' ),
      array( 'label' => 'Television Station' ),
      array( 'label' => 'Tourist Information Center' ),
      array( 'label' => 'Train Station' ),
      array( 'label' => 'Travel Agency' ),
      array( 'label' => 'Taxi Stand' ),
      array( 'label' => 'Website' ),
      array( 'label' => 'Graphic Novel' ),
      array( 'label' => 'Zoo' ),
    ) );

    $business = array();
    if ( $none ) {
      $business['off'] = 'None';
    }

    foreach ( $data as $item ) {
      $business[ str_replace( ' ', '', $item['label'] ) ] = $item['label'];

      if ( isset( $item['child'] ) ) {
        foreach ( $item['child'] as $child ) {
          $business[ str_replace( ' ', '', $child['label'] ) ] = '&mdash; ' . $child['label'];
        }
      }
    }

    return $business;
  }

  /**
   * Gets rich snippet types as choices.
   *
   * @codeCoverageIgnore
   *
   * @param  bool $none Add none option to the list.
   * @return array
   */
  public static function choices_rich_snippet_types( $none = false ) {
    $types = array(
      'article'    => esc_html__( 'Article', 'schema-markup' ),
      'book'       => esc_html__( 'Book', 'schema-markup' ),
      'course'     => esc_html__( 'Course', 'schema-markup' ),
      'event'      => esc_html__( 'Event', 'schema-markup' ),
      'jobposting' => esc_html__( 'Job Posting', 'schema-markup' ),
      'music'      => esc_html__( 'Music', 'schema-markup' ),
      'product'    => esc_html__( 'Product', 'schema-markup' ),
      'recipe'     => esc_html__( 'Recipe', 'schema-markup' ),
      'restaurant' => esc_html__( 'Restaurant', 'schema-markup' ),
      'video'      => esc_html__( 'Video', 'schema-markup' ),
      'person'     => esc_html__( 'Person', 'schema-markup' ),
      'review'     => esc_html__( 'Review', 'schema-markup' ),
      'service'    => esc_html__( 'Service', 'schema-markup' ),
      'software'   => esc_html__( 'Software Application', 'schema-markup' ),
    );

    if ( is_string( $none ) ) {
      $types = array( 'off' => $none ) + $types;
    }

    return apply_filters( 'rank_math/settings/snippet/types', $types );
  }

  /**
   * Get Post type icons.
   *
   * @codeCoverageIgnore
   *
   * @return array
   */
  public static function choices_post_type_icons() {
    /**
     * Allow developer to change post types icons.
     *
     * @param array $icons Array of available icons.
     */
    return apply_filters( 'rank_math/post_type_icons', array(
      'default'    => 'dashicons dashicons-admin-post',
      'post'       => 'dashicons dashicons-admin-post',
      'page'       => 'dashicons dashicons-admin-page',
      'attachment' => 'dashicons dashicons-admin-media',
      'product'    => 'fa fa-shopping-cart',
    ));
  }

  /**
   * Get Taxonomy icons.
   *
   * @codeCoverageIgnore
   *
   * @return array
   */
  public static function choices_taxonomy_icons() {
    /**
     * Allow developer to change taxonomies icons.
     *
     * @param array $icons Array of available icons.
     */
    return apply_filters( 'rank_math/taxonomy_icons', array(
      'default'     => 'dashicons dashicons-tag',
      'category'    => 'dashicons dashicons-category',
      'post_tag'    => 'dashicons dashicons-tag',
      'product_cat' => 'dashicons dashicons-category',
      'product_tag' => 'dashicons dashicons-tag',
      'post_format' => 'dashicons dashicons-format-image',
    ));
  }
}
