<?php
/**
 * The JobPosting Class
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

use RANKMATH_SCHEMA\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * JobPosting class.
 */
class JobPosting implements Snippet {

	/**
	 * Job Posting rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$entity = [
			'@context'       => 'https://schema.org',
			'@type'          => 'JobPosting',
			'title'          => $jsonld->parts['title'],
			'description'    => $jsonld->parts['desc'] ? $jsonld->parts['desc'] : get_the_content(),
			'identifier'     => [
				'@type' => 'PropertyValue',
				'name'  => '',
				'value' => '',
			],
			'datePosted'     => $this->get_posted_date( $jsonld->parts['published'] ),
			'validThrough'   => '',
			'employmentType' => Helper::get_post_meta( 'snippet_jobposting_employment_type' ),
			'jobLocation'    => [ '@type' => 'Place' ],
			'baseSalary'     => [
				'@type'    => 'MonetaryAmount',
				'currency' => Helper::get_post_meta( 'snippet_jobposting_currency' ),
				'value'    => [
					'@type'    => 'QuantitativeValue',
					'value'    => absint( Helper::get_post_meta( 'snippet_jobposting_salary' ) ),
					'unitText' => Helper::get_post_meta( 'snippet_jobposting_payroll' ),
				],
			],
		];

		$jsonld->add_prop( 'thumbnail', $entity );
		$jsonld->set_address( 'jobposting', $entity['jobLocation'] );

		// Publisher.
		if ( $organization = Helper::get_post_meta( 'snippet_jobposting_organization' ) ) { // phpcs:ignore
			$entity['hiringOrganization'] = [
				'@type'  => 'Organization',
				'name'   => $organization,
				'sameAs' => Helper::get_post_meta( 'snippet_jobposting_url' ),
				'logo'   => Helper::get_post_meta( 'snippet_jobposting_logo' ),
			];
		} elseif ( isset( $data['Organization'] ) ) {
			$jsonld->set_publisher( $entity['hiringOrganization'], $data['Organization'] );
		}

		$this->is_expired_unpublish( $jsonld, $entity );

		return $entity;
	}

	/**
	 * Get posted date.
	 *
	 * @param  string $default Default value.
	 * @return string
	 */
	private function get_posted_date( $default = '' ) {
		$posted = $default;
		if ( $start_date = Helper::get_post_meta( 'snippet_jobposting_startdate' ) ) { // phpcs:ignore
			$posted = str_replace( ' ', 'T', date( 'Y-m-d H:i', $start_date ) );
		}

		return $posted;
	}

	/**
	 * Unpublish job posting when expired.
	 *
	 * @param JsonLD $jsonld JsonLD Instance.
	 * @param array  $entity Array of json-ld entity.
	 */
	private function is_expired_unpublish( $jsonld, &$entity ) {
		$end_date = Helper::get_post_meta( 'snippet_jobposting_expirydate' );
		if ( empty( $end_date ) ) {
			return;
		}

		$entity['validThrough'] = str_replace( ' ', 'T', date( 'Y-m-d H:i', $end_date ) );
		if ( date_create( 'now' )->getTimestamp() < $end_date ) {
			return;
		}

		if ( ! Helper::get_post_meta( 'snippet_jobposting_unpublish' ) ) {
			return;
		}

		wp_update_post([
			'ID'          => $jsonld->post_id,
			'post_status' => 'draft',
		]);
	}
}
