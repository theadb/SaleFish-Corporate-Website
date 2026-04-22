<?php
/*
Plugin Name: Salefish
Description: Site specific code changes for Salefish
 */


require_once 'includes/class-mailer.php';
require_once 'includes/class-email-verify.php';
require_once 'includes/disable-comments.php';
require_once 'ajax/mailchimp-register.php';
require_once 'ajax/agents-register.php';
require_once 'ajax/partner-register.php';

// ── Email verification ────────────────────────────────────────────────────────
// Run at init priority 1 — before WP Super Cache's PHP caching layer can serve
// a cached page and before template_redirect (which is too late in some setups).
add_action( 'init', function() {
	if ( empty( $_GET['salefish_verify'] ) ) return;

	// Prevent this response from being written to any page cache.
	if ( ! defined( 'DONOTCACHEPAGE' ) )   define( 'DONOTCACHEPAGE',   true );
	if ( ! defined( 'DONOTCACHEOBJECT' ) ) define( 'DONOTCACHEOBJECT', true );
	nocache_headers();

	Salefish_Email_Verify::handle_verification();
}, 1 );

// Belt-and-suspenders: also handle on template_redirect for any edge cases.
add_action( 'template_redirect', [ 'Salefish_Email_Verify', 'handle_verification' ] );

// Tell WP Super Cache not to cache or serve cached responses for verification URLs.
add_filter( 'wpsc_is_cacheable', function( $cacheable ) {
	return ! empty( $_GET['salefish_verify'] ) ? false : $cacheable;
} );

// One-time: create the thank-you page if it doesn't exist yet.
add_action( 'init', function() {
	if ( get_option( 'salefish_thankyou_page_created_v1' ) ) return;
	$existing = get_page_by_path( 'thank-you-for-registering' );
	if ( ! $existing ) {
		wp_insert_post( [
			'post_title'   => 'Thank You for Registering',
			'post_name'    => 'thank-you-for-registering',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
			'meta_input'   => [ '_wp_page_template' => 'page-thank-you-for-registering.php' ],
		] );
	}
	update_option( 'salefish_thankyou_page_created_v1', 1 );
}, 10 );

// Daily cron: purge expired sf_reg_* options from wp_options.
add_action( 'salefish_purge_expired_regs', [ 'Salefish_Email_Verify', 'purge_expired' ] );
if ( ! wp_next_scheduled( 'salefish_purge_expired_regs' ) ) {
	wp_schedule_event( time(), 'daily', 'salefish_purge_expired_regs' );
}

if ( is_admin() ) {
	require_once 'admin/email-preview.php';
}
