<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/class-email-verify.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/email-templates.php';

function salefish_partner_register() {
	check_ajax_referer( 'salefish_nonce', 'nonce' );

	if ( ! empty( $_POST['sf_hp'] ) ) {
		wp_send_json_success( 'Registered successfully.' );
	}

	$turnstile_token = sanitize_text_field( $_POST['cf-turnstile-response'] ?? '' );
	if ( ! Salefish_Email_Verify::verify_turnstile( $turnstile_token ) ) {
		wp_send_json_error( 'Please complete the security check.' );
	}

	$name       = sanitize_text_field( $_POST['name']       ?? '' );
	$email      = sanitize_email(       $_POST['email']      ?? '' );
	$phone      = sanitize_text_field( $_POST['phone']      ?? '' );
	$company    = sanitize_text_field( $_POST['company']    ?? '' );
	$want_to_do = sanitize_text_field( $_POST['want_to_do'] ?? '' );
	$clients    = sanitize_text_field( $_POST['clients']    ?? '' );

	if ( ! $email || ! is_email( $email ) ) {
		wp_send_json_error( 'Invalid email address.' );
	}

	$token = Salefish_Email_Verify::create( 'partner', [
		'name'       => $name,
		'email'      => $email,
		'phone'      => $phone,
		'company'    => $company,
		'want_to_do' => $want_to_do,
		'clients'    => $clients,
		'_ctx'       => salefish_collect_context(),
	] );

	Salefish_Email_Verify::send_confirmation( $email, $token, 'partner' );

	wp_send_json_success( [ 'email' => $email ] );
}

add_action( 'wp_ajax_partner_register',        'salefish_partner_register' );
add_action( 'wp_ajax_nopriv_partner_register', 'salefish_partner_register' );
