<?php
/**
 * Metabox - Event Rich Snippet
 *
 * @package    RANKMATH_SCHEMA
 * @subpackage RANKMATH_SCHEMA\RichSnippet
 */

$event = [ [ 'rank_math_rich_snippet', 'event' ] ];

$cmb->add_field([
	'id'      => 'rank_math_snippet_event_type',
	'type'    => 'select',
	'name'    => esc_html__( 'Event Type', 'schema-markup' ),
	'desc'    => esc_html__( 'Type of the event.', 'schema-markup' ),
	'options' => [
		'Event'            => esc_html__( 'Event', 'schema-markup' ),
		'BusinessEvent'    => esc_html__( 'Business Event', 'schema-markup' ),
		'ChildrensEvent'   => esc_html__( 'Childrens Event', 'schema-markup' ),
		'ComedyEvent'      => esc_html__( 'Comedy Event', 'schema-markup' ),
		'DanceEvent'       => esc_html__( 'Dance Event', 'schema-markup' ),
		'DeliveryEvent'    => esc_html__( 'Delivery Event', 'schema-markup' ),
		'EducationEvent'   => esc_html__( 'Education Event', 'schema-markup' ),
		'ExhibitionEvent'  => esc_html__( 'Exhibition Event', 'schema-markup' ),
		'Festival'         => esc_html__( 'Festival', 'schema-markup' ),
		'FoodEvent'        => esc_html__( 'Food Event', 'schema-markup' ),
		'LiteraryEvent'    => esc_html__( 'Literary Event', 'schema-markup' ),
		'MusicEvent'       => esc_html__( 'Music Event', 'schema-markup' ),
		'PublicationEvent' => esc_html__( 'Publication Event', 'schema-markup' ),
		'SaleEvent'        => esc_html__( 'Sale Event', 'schema-markup' ),
		'ScreeningEvent'   => esc_html__( 'Screening Event', 'schema-markup' ),
		'SocialEvent'      => esc_html__( 'Social Event', 'schema-markup' ),
		'SportsEvent'      => esc_html__( 'Sports Event', 'schema-markup' ),
		'TheaterEvent'     => esc_html__( 'Theater Event', 'schema-markup' ),
		'VisualArtsEvent'  => esc_html__( 'Visual Arts Event', 'schema-markup' ),
	],
	'default' => 'Event',
	'classes' => 'cmb-row-33',
	'dep'     => $event,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_event_venue',
	'type'    => 'text',
	'name'    => esc_html__( 'Venue Name', 'schema-markup' ),
	'desc'    => esc_html__( 'The venue name.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $event,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_event_venue_url',
	'type'    => 'text_url',
	'name'    => esc_html__( 'Venue URL', 'schema-markup' ),
	'desc'    => esc_html__( 'Website URL of the venue', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $event,
]);

$cmb->add_field([
	'id'   => 'rank_math_snippet_event_address',
	'type' => 'address',
	'name' => esc_html__( 'Address', 'schema-markup' ),
	'dep'  => $event,
]);

$cmb->add_field([
	'id'   => 'rank_math_snippet_event_performer',
	'type' => 'text',
	'name' => esc_html__( 'Performer', 'schema-markup' ),
	'desc' => esc_html__( 'A performer at the event', 'schema-markup' ),
	'dep'  => $event,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_event_status',
	'type'    => 'select',
	'name'    => esc_html__( 'Event Status', 'schema-markup' ),
	'desc'    => esc_html__( 'Current status of the event (optional)', 'schema-markup' ),
	'options' => [
		''                 => esc_html__( 'None', 'schema-markup' ),
		'EventScheduled'   => esc_html__( 'Scheduled', 'schema-markup' ),
		'EventCancelled'   => esc_html__( 'Cancelled', 'schema-markup' ),
		'EventPostponed'   => esc_html__( 'Postponed', 'schema-markup' ),
		'EventRescheduled' => esc_html__( 'Rescheduled', 'schema-markup' ),
	],
	'classes' => 'cmb-row-33',
	'dep'     => $event,
]);

$cmb->add_field([
	'id'          => 'rank_math_snippet_event_startdate',
	'type'        => 'text_datetime_timestamp',
	'date_format' => 'Y-m-d',
	'name'        => esc_html__( 'Start Date', 'schema-markup' ),
	'desc'        => esc_html__( 'Date and time of the event.', 'schema-markup' ),
	'classes'     => 'cmb-row-33',
	'dep'         => $event,
]);

$cmb->add_field([
	'id'          => 'rank_math_snippet_event_enddate',
	'type'        => 'text_datetime_timestamp',
	'date_format' => 'Y-m-d',
	'name'        => esc_html__( 'End Date', 'schema-markup' ),
	'desc'        => esc_html__( 'End date and time of the event.', 'schema-markup' ),
	'classes'     => 'cmb-row-33',
	'dep'         => $event,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_event_ticketurl',
	'type'    => 'text',
	'name'    => esc_html__( 'Ticket URL', 'schema-markup' ),
	'desc'    => esc_html__( 'A URL where visitors can purchase tickets for the event.', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $event,
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_event_price',
	'type'       => 'text',
	'name'       => esc_html__( 'Entry Price', 'schema-markup' ),
	'desc'       => esc_html__( 'Entry price of the event (optional)', 'schema-markup' ),
	'classes'    => 'cmb-row-33',
	'dep'        => $event,
	'attributes' => [ 'type' => 'number' ],
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_event_currency',
	'type'    => 'text',
	'name'    => esc_html__( 'Currency', 'schema-markup' ),
	'desc'    => esc_html__( 'ISO 4217 Currency Code', 'schema-markup' ),
	'classes' => 'cmb-row-33',
	'dep'     => $event,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_event_availability',
	'type'    => 'select',
	'name'    => esc_html__( 'Availability', 'schema-markup' ),
	'desc'    => esc_html__( 'Offer availability', 'schema-markup' ),
	'options' => [
		''         => esc_html__( 'None', 'schema-markup' ),
		'InStock'  => esc_html__( 'In Stock', 'schema-markup' ),
		'SoldOut'  => esc_html__( 'Sold Out', 'schema-markup' ),
		'PreOrder' => esc_html__( 'Preorder', 'schema-markup' ),
	],
	'classes' => 'cmb-row-33 nob',
	'dep'     => $event,
]);

$cmb->add_field([
	'id'          => 'rank_math_snippet_event_availability_starts',
	'type'        => 'text_datetime_timestamp',
	'date_format' => 'Y-m-d',
	'name'        => esc_html__( 'Availability Starts', 'schema-markup' ),
	'desc'        => esc_html__( 'Date and time when offer is made available. (optional)', 'schema-markup' ),
	'classes'     => 'cmb-row-33 nob',
	'dep'         => $event,
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_event_inventory',
	'type'       => 'text',
	'name'       => esc_html__( 'Stock Inventory', 'schema-markup' ),
	'desc'       => esc_html__( 'Number of tickets (optional)', 'schema-markup' ),
	'classes'    => 'cmb-row-33 nob',
	'dep'        => $event,
	'attributes' => [ 'type' => 'number' ],
]);
