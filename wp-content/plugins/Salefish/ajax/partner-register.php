<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/email-templates.php';

function salefish_partner_register() {
	check_ajax_referer( 'salefish_nonce', 'nonce' );

	$name       = sanitize_text_field( $_POST['name']        ?? '' );
	$email      = sanitize_email(       $_POST['email']       ?? '' );
	$phone      = sanitize_text_field( $_POST['phone']       ?? '' );
	$company    = sanitize_text_field( $_POST['company']     ?? '' );
	$want_to_do = sanitize_text_field( $_POST['want_to_do']  ?? '' );
	$clients    = sanitize_text_field( $_POST['clients']     ?? '' );

	if ( ! $email || ! is_email( $email ) ) {
		wp_send_json_error( 'Invalid email address.' );
	}

	$ctx = salefish_collect_context();

	$parts      = explode( ' ', $name, 2 );
	$first_name = $parts[0] ?? '';
	$last_name  = $parts[1] ?? '';

	$ac = new Salefish_ActiveCampaign();

	$contact_id = $ac->upsert_contact( [
		'email'      => $email,
		'first_name' => $first_name,
		'last_name'  => $last_name,
		'phone'      => $phone,
	] );

	if ( $contact_id ) {
		$ac->subscribe_to_list( $contact_id );
		$ac->add_tag( $contact_id, 'partner-registration' );
		if ( $want_to_do ) {
			$ac->set_field( $contact_id, 1, $want_to_do );
		}
		if ( $company ) {
			$ac->set_field( $contact_id, 2, $company );
		}
		// Triggers AC autoresponder automation once SALEFISH_AC_AUTO_PARTNER is set in wp-config.php
		$auto_id = defined( 'SALEFISH_AC_AUTO_PARTNER' ) ? (int) SALEFISH_AC_AUTO_PARTNER : 0;
		$ac->add_to_automation( $contact_id, $auto_id );
		$note = salefish_format_ac_note(
			array_merge(
				[
					'name'       => $name,
					'email'      => $email,
					'phone'      => $phone,
					'company'    => $company,
					'want_to_do' => $want_to_do,
					'clients'    => $clients,
				],
				$ctx
			),
			'partner'
		);
		$ac->add_note( $contact_id, $note );
	}

	salefish_send_notification(
		array_merge(
			[
				'name'       => $name,
				'email'      => $email,
				'phone'      => $phone,
				'company'    => $company,
				'want_to_do' => $want_to_do,
				'clients'    => $clients,
			],
			$ctx
		),
		'partner'
	);

	wp_send_json_success( 'Registered successfully.' );
}

add_action( 'wp_ajax_partner_register',        'salefish_partner_register' );
add_action( 'wp_ajax_nopriv_partner_register', 'salefish_partner_register' );
