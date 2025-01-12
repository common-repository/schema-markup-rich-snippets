<?php
/**
 * The social settings.
 *
 * @package    RankMath
 * @subpackage RankMath\Settings
 */

$cmb->add_field([
	'id'   => 'social_url_facebook',
	'type' => 'text',
	'name' => esc_html__( 'Facebook Page URL', 'schema-markup' ),
	'desc' => esc_html__( 'Enter your complete Facebook page URL here. eg:', 'schema-markup' ) .
		'<br><code>' . htmlspecialchars( 'https://www.facebook.com/MyThemeShop/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'facebook_author_urls',
	'type' => 'text',
	'name' => esc_html__( 'Facebook Authorship', 'schema-markup' ),
	'desc' => esc_html__( 'Insert personal Facebook profile URL to show Facebook Authorship when your articles are being shared on Facebook. eg:', 'schema-markup' ) .
		'<br><code>' . htmlspecialchars( 'https://www.facebook.com/zuck' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'facebook_admin_id',
	'type' => 'text',
	'name' => esc_html__( 'Facebook Admin', 'schema-markup' ),
	/* translators: numeric user ID link */
	'desc' => sprintf( esc_html__( 'Enter %s. Use a comma to separate multiple IDs. Alternatively, you can enter an app ID below.', 'schema-markup' ), '<a href="https://findmyfbid.com/?utm_campaign=Rank+Math" target="_blank">numeric user ID</a>' ),
]);

$cmb->add_field([
	'id'   => 'facebook_app_id',
	'type' => 'text',
	'name' => esc_html__( 'Facebook App', 'schema-markup' ),
	/* translators: numeric app ID link */
	'desc' => sprintf( esc_html__( 'Enter %s. Alternatively, you can enter a user ID above.', 'schema-markup' ), '<a href="https://developers.facebook.com/apps?utm_campaign=Rank+Math" target="_blank">numeric app ID</a>' ),
]);

$cmb->add_field([
	'id'         => 'facebook_secret',
	'type'       => 'text',
	'name'       => esc_html__( 'Facebook Secret', 'schema-markup' ),
	/* translators: Learn more link */
	'desc'       => sprintf( esc_html__( 'Enter alphanumeric secret ID. %s.', 'schema-markup' ), '<a href="https://s.rankmath.com/fbapp" target="_blank">Learn more</a>' ),
	'attributes' => [ 'type' => 'password' ],
]);

$cmb->add_field([
	'id'   => 'social_url_twitter',
	'type' => 'text',
	'name' => esc_html__( 'Twitter Profile URL', 'schema-markup' ),
	'desc' => esc_html__( 'Enter your complete Twitter Profile URL here. eg:', 'schema-markup' ) .
		'<br><code>' . htmlspecialchars( 'https://twitter.com/MyThemeShopTeam/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'twitter_author_names',
	'type' => 'text',
	'name' => esc_html__( 'Twitter Username', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter the Twitter username of the author to add <code>twitter:creator</code> tag to posts. eg: <code>MyThemeShopTeam</code>', 'schema-markup' ) ),
]);

$cmb->add_field([
	'id'   => 'social_url_google_places',
	'type' => 'text',
	'name' => esc_html__( 'Google Places', 'schema-markup' ),
	/* translators: How to find it? link */
	'desc' => sprintf( esc_html__( 'Enter full URL of your Google Places listing here. %s', 'schema-markup' ), '<a href="https://developers.google.com/maps/documentation/urls/guide?utm_campaign=Rank+Math" target="_blank">How to find it?</a>' ),
]);

$cmb->add_field([
	'id'   => 'social_url_yelp',
	'type' => 'text',
	'name' => esc_html__( 'Yelp Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your Yelp Listing\'s full URL here. eg:', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://www.yelp.com/biz/the-house-san-francisco' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_foursquare',
	'type' => 'text',
	'name' => esc_html__( 'FourSquare Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your FourSquare Page\'s full URL here.', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://foursquare.com/v/lands-end/49bacd63f964a520b0531fe3' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_flickr',
	'type' => 'text',
	'name' => esc_html__( 'Flickr Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your Flickr Page or Profile URL here. eg:', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://www.flickr.com/photos/albertdros/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_reddit',
	'type' => 'text',
	'name' => esc_html__( 'Reddit Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your domain\'s Reddit URL here. eg:', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://www.reddit.com/domain/rankmath.com/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_linkedin',
	'type' => 'text',
	'name' => esc_html__( 'LinkedIn Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your LinkedIn profile URL (for personal blogs) or your company URL (for business blogs). eg:', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://www.linkedin.com/company/mythemeshop/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_instagram',
	'type' => 'text',
	'name' => esc_html__( 'Instagram Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your Instagram profile URL here. e.g: ', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://www.instagram.com/mkbhd/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_youtube',
	'type' => 'text',
	'name' => esc_html__( 'Youtube Channel URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your YouTube Channel\'s URL here. e.g', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://www.youtube.com/channel/UC2t2B_nKC5jg1Ix5rU0Bz7A' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_pinterest',
	'type' => 'text',
	'name' => esc_html__( 'Pinterest Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your Pinterest Profile URL here. eg:', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://in.pinterest.com/mythemeshop/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_soundcloud',
	'type' => 'text',
	'name' => esc_html__( 'SoundClound Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your SoundCloud URL here. eg:', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://soundcloud.com/mythemeshop' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_tumblr',
	'type' => 'text',
	'name' => esc_html__( 'Tumblr Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( 'Enter your Tumblr URL here. eg:', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://mythemeshop.tumblr.com' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'social_url_myspace',
	'type' => 'text',
	'name' => esc_html__( 'Myspace Page URL', 'schema-markup' ),
	'desc' => wp_kses_post( __( ' Enter your MySpace profile here. e.g: ', 'schema-markup' ) ) .
		'<br><code>' . htmlspecialchars( 'https://myspace.com/katyperry' ) . '</code>',
]);
