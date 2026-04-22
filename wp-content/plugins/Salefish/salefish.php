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

if ( is_admin() ) {
	require_once 'admin/email-preview.php';
}
