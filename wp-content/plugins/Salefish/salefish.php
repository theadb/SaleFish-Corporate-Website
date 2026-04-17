<?php
/*
Plugin Name: Salefish
Description: Site specific code changes for Salefish
 */


require_once 'includes/class-mailer.php';
require_once 'ajax/mailchimp-register.php';
require_once 'ajax/agents-register.php';
require_once 'ajax/partner-register.php';

if ( is_admin() ) {
	require_once 'admin/email-preview.php';
}
