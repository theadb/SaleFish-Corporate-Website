<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/email-templates.php';

function salefish_agents_register() {
	check_ajax_referer( 'salefish_nonce', 'nonce' );

	if ( ! empty( $_POST['sf_hp'] ) ) {
		wp_send_json_success( 'Registered successfully.' );
	}

	$name             = sanitize_text_field( $_POST['name']              ?? '' );
	$email            = sanitize_email(       $_POST['email']             ?? '' );
	$phone            = sanitize_text_field( $_POST['phone']             ?? '' );
	$brokerage        = sanitize_text_field( $_POST['brokerage']         ?? '' );
	$website_url      = esc_url_raw(          $_POST['website_url']       ?? '' );
	$geo_expertise    = sanitize_text_field( $_POST['geo_expertise']     ?? '' );
	$property_exp     = sanitize_text_field( $_POST['property_expertise'] ?? '' );
	$howhear          = sanitize_text_field( $_POST['howhear']           ?? '' );
	$see_projects     = sanitize_text_field( $_POST['see_projects']      ?? '' );
	$see_feature      = sanitize_text_field( $_POST['see_feature']       ?? '' );

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
		$ac->add_tag( $contact_id, 'agent-registration' );
		$ac->set_field( $contact_id, 1, 'Real Estate Agent' );
		if ( $brokerage ) {
			$ac->set_field( $contact_id, 2, $brokerage );
		}
		// Triggers AC autoresponder automation once SALEFISH_AC_AUTO_AGENT is set in wp-config.php
		$auto_id = defined( 'SALEFISH_AC_AUTO_AGENT' ) ? (int) SALEFISH_AC_AUTO_AGENT : 0;
		$ac->add_to_automation( $contact_id, $auto_id );
		$note = salefish_format_ac_note(
			array_merge(
				[
					'name'               => $name,
					'email'              => $email,
					'phone'              => $phone,
					'company'            => $brokerage,
					'website'            => $website_url,
					'geographic_expertise' => $geo_expertise,
					'property_expertise' => $property_exp,
					'how_did_you_hear'   => $howhear,
					'projects_to_see'    => $see_projects,
					'features_wanted'    => $see_feature,
				],
				$ctx
			),
			'agent'
		);
		$ac->add_note( $contact_id, $note );
	}

	salefish_send_notification(
		array_merge(
			[
				'name'               => $name,
				'email'              => $email,
				'phone'              => $phone,
				'company'            => $brokerage,
				'website'            => $website_url,
				'geographic_expertise' => $geo_expertise,
				'property_expertise' => $property_exp,
				'how_did_you_hear'   => $howhear,
				'projects_to_see'    => $see_projects,
				'features_wanted'    => $see_feature,
			],
			$ctx
		),
		'agent'
	);

	wp_send_json_success( 'Registered successfully.' );
}

add_action( 'wp_ajax_agents_register',        'salefish_agents_register' );
add_action( 'wp_ajax_nopriv_agents_register', 'salefish_agents_register' );
