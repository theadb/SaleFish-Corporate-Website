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

add_action( 'template_redirect', [ 'Salefish_Email_Verify', 'handle_verification' ] );

// Daily cron: purge expired sf_reg_* options from wp_options.
add_action( 'salefish_purge_expired_regs', [ 'Salefish_Email_Verify', 'purge_expired' ] );
if ( ! wp_next_scheduled( 'salefish_purge_expired_regs' ) ) {
	wp_schedule_event( time(), 'daily', 'salefish_purge_expired_regs' );
}

if ( is_admin() ) {
	require_once 'admin/email-preview.php';
}
