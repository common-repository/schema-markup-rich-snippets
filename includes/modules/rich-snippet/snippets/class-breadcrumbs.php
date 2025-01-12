<?php
/**
 * The Breadcrumbs Class
 *
 * @since      1.0.0
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RANKMATH_SCHEMA\RichSnippet;

defined( 'ABSPATH' ) || exit;

/**
 * Breadcrumbs class.
 */
class Breadcrumbs implements Snippet {

	/**
	 * Generate breadcrumbs JSON-LD.
	 *
	 * @link https://schema.org/BreadcrumbList
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$breadcrumbs = rank_math_schema()->breadcrumbs;
		$crumbs      = $breadcrumbs ? $breadcrumbs->get_crumbs() : false;
		if ( empty( $crumbs ) ) {
			return $data;
		}

		$entity = [
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => [],
		];

		foreach ( $crumbs as $index => $crumb ) {
			if ( ! empty( $crumb['hide_in_schema'] ) ) {
				continue;
			}

			$entity['itemListElement'][] = [
				'@type'    => 'ListItem',
				'position' => $index + 1,
				'item'     => [
					'@id'  => $crumb[1],
					'name' => $crumb[0],
				],
			];
		}

		$data['BreadcrumbList'] = apply_filters( 'rank_math/snippet/breadcrumb', $entity );

		return $data;
	}
}
