<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/class-email-verify.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/email-templates.php';

function salefish_mailchimp_register() {
	check_ajax_referer( 'salefish_nonce', 'nonce' );

	if ( ! empty( $_POST['sf_hp'] ) ) {
		wp_send_json_success( 'Registered successfully.' );
	}

	$name    = sanitize_text_field( $_POST['name']    ?? '' );
	$email   = sanitize_email(       $_POST['email']   ?? '' );
	$phone   = sanitize_text_field( $_POST['phone']   ?? '' );
	$company = sanitize_text_field( $_POST['company'] ?? '' );

	if ( ! $email || ! is_email( $email ) ) {
		wp_send_json_error( 'Invalid email address.' );
	}

	$token = Salefish_Email_Verify::create( 'general', [
		'name'    => $name,
		'email'   => $email,
		'phone'   => $phone,
		'company' => $company,
		'_ctx'    => salefish_collect_context(),
	] );

	Salefish_Email_Verify::send_confirmation( $email, $token, 'general' );

	wp_send_json_success( 'Registered successfully.' );
}

add_action( 'wp_ajax_mailchimp_register',        'salefish_mailchimp_register' );
add_action( 'wp_ajax_nopriv_mailchimp_register', 'salefish_mailchimp_register' );
