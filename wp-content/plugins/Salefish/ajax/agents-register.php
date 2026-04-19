<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/class-email-verify.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/email-templates.php';

function salefish_agents_register() {
	check_ajax_referer( 'salefish_nonce', 'nonce' );

	if ( ! empty( $_POST['sf_hp'] ) ) {
		wp_send_json_success( 'Registered successfully.' );
	}

	$name          = sanitize_text_field( $_POST['name']               ?? '' );
	$email         = sanitize_email(       $_POST['email']              ?? '' );
	$phone         = sanitize_text_field( $_POST['phone']              ?? '' );
	$brokerage     = sanitize_text_field( $_POST['brokerage']          ?? '' );
	$website_url   = esc_url_raw(          $_POST['website_url']        ?? '' );
	$geo_expertise = sanitize_text_field( $_POST['geo_expertise']      ?? '' );
	$property_exp  = sanitize_text_field( $_POST['property_expertise'] ?? '' );
	$howhear       = sanitize_text_field( $_POST['howhear']            ?? '' );
	$see_projects  = sanitize_text_field( $_POST['see_projects']       ?? '' );
	$see_feature   = sanitize_text_field( $_POST['see_feature']        ?? '' );

	if ( ! $email || ! is_email( $email ) ) {
		wp_send_json_error( 'Invalid email address.' );
	}

	$token = Salefish_Email_Verify::create( 'agent', [
		'name'                 => $name,
		'email'                => $email,
		'phone'                => $phone,
		'brokerage'            => $brokerage,
		'website_url'          => $website_url,
		'geo_expertise'        => $geo_expertise,
		'property_expertise'   => $property_exp,
		'howhear'              => $howhear,
		'see_projects'         => $see_projects,
		'see_feature'          => $see_feature,
		'_ctx'                 => salefish_collect_context(),
	] );

	Salefish_Email_Verify::send_confirmation( $email, $token, 'agent' );

	wp_send_json_success( 'Registered successfully.' );
}

add_action( 'wp_ajax_agents_register',        'salefish_agents_register' );
add_action( 'wp_ajax_nopriv_agents_register', 'salefish_agents_register' );
