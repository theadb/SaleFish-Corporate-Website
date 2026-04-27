<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SALEFISH_EMAIL_LOGO', 'https://salefish.app/wp-content/themes/salefish/img/dark_salefish_logo.png' );

/**
 * Collect request context: IP, geo, browser, OS, source page.
 * Returns an array with keys: _ctx_ip, _ctx_location, _ctx_browser, _ctx_os, _ctx_source, _ctx_ua.
 */
function salefish_collect_context(): array {
	// --- Resolve real IP ---
	$ip = '';
	$candidates = [
		$_SERVER['HTTP_CF_CONNECTING_IP'] ?? '',
		$_SERVER['HTTP_X_REAL_IP']        ?? '',
		$_SERVER['HTTP_X_FORWARDED_FOR']  ?? '',
		$_SERVER['REMOTE_ADDR']           ?? '',
	];
	foreach ( $candidates as $candidate ) {
		$candidate = trim( explode( ',', $candidate )[0] );
		if ( $candidate && filter_var( $candidate, FILTER_VALIDATE_IP ) ) {
			$ip = $candidate;
			break;
		}
	}

	// --- Detect browser from User-Agent ---
	$ua      = $_SERVER['HTTP_USER_AGENT'] ?? '';
	$browser = 'Unknown';
	if ( str_contains( $ua, 'Edg/' ) ) {
		$browser = 'Edge';
	} elseif ( str_contains( $ua, 'OPR/' ) ) {
		$browser = 'Opera';
	} elseif ( str_contains( $ua, 'SamsungBrowser' ) ) {
		$browser = 'Samsung Browser';
	} elseif ( str_contains( $ua, 'Chrome/' ) ) {
		$browser = 'Chrome';
	} elseif ( str_contains( $ua, 'Firefox/' ) ) {
		$browser = 'Firefox';
	} elseif ( str_contains( $ua, 'Safari/' ) ) {
		$browser = 'Safari';
	}

	// --- Detect OS from User-Agent ---
	$os = 'Unknown';
	if ( str_contains( $ua, 'Windows NT 10' ) || str_contains( $ua, 'Windows NT 11' ) ) {
		$os = 'Windows 10/11';
	} elseif ( str_contains( $ua, 'iPhone' ) ) {
		$os = 'iOS (iPhone)';
	} elseif ( str_contains( $ua, 'iPad' ) ) {
		$os = 'iOS (iPad)';
	} elseif ( str_contains( $ua, 'Android' ) ) {
		$os = 'Android';
	} elseif ( str_contains( $ua, 'Macintosh' ) ) {
		$os = 'macOS';
	} elseif ( str_contains( $ua, 'Linux' ) ) {
		$os = 'Linux';
	}

	// --- Geo-location via ip-api.com (skip localhost) ---
	$location = '';
	$is_local = in_array( $ip, [ '127.0.0.1', '::1', '' ], true )
		|| str_starts_with( $ip, '192.168.' )
		|| str_starts_with( $ip, '10.' );

	if ( $ip && ! $is_local ) {
		$geo_url  = "http://ip-api.com/json/{$ip}?fields=status,city,regionName,country";
		$geo_resp = wp_remote_get( $geo_url, [ 'timeout' => 5 ] );
		if ( ! is_wp_error( $geo_resp ) ) {
			$geo = json_decode( wp_remote_retrieve_body( $geo_resp ), true );
			if ( isset( $geo['status'] ) && $geo['status'] === 'success' ) {
				$parts = array_filter( [
					$geo['city']       ?? '',
					$geo['regionName'] ?? '',
					$geo['country']    ?? '',
				] );
				$location = implode( ', ', $parts );
			}
		}
	}

	// --- Source page ---
	$source = $_SERVER['HTTP_REFERER'] ?? '';

	return [
		'_ctx_ip'       => $ip,
		'_ctx_location' => $location,
		'_ctx_browser'  => $browser,
		'_ctx_os'       => $os,
		'_ctx_source'   => $source,
		'_ctx_ua'       => $ua,
	];
}

/**
 * Format all form fields + context into a plain-text ActiveCampaign note.
 *
 * @param array  $fields    Merged form fields + _ctx_* context keys.
 * @param string $form_type 'general' | 'agent' | 'partner'
 */
function salefish_format_ac_note( array $fields, string $form_type ): string {
	$title = 'REGISTRATION';

	$skip_keys = [ 'action', 'nonce', '_ctx_ua' ];

	$ctx_label_map = [
		'_ctx_ip'       => 'IP Address',
		'_ctx_location' => 'Location',
		'_ctx_browser'  => 'Browser',
		'_ctx_os'       => 'OS',
		'_ctx_source'   => 'Source Page',
		'_ctx_section'  => 'CTA Section',
	];

	$lines      = [];
	$intel_rows = [];

	foreach ( $fields as $key => $val ) {
		if ( in_array( $key, $skip_keys, true ) || (string) $val === '' ) continue;

		if ( str_starts_with( $key, '_ctx_' ) ) {
			if ( isset( $ctx_label_map[ $key ] ) ) {
				$intel_rows[] = $ctx_label_map[ $key ] . ': ' . $val;
			}
		} else {
			$label   = ucwords( str_replace( '_', ' ', $key ) );
			$lines[] = $label . ': ' . $val;
		}
	}

	$tz   = new DateTimeZone( 'America/Toronto' );
	$now  = ( new DateTime( 'now', $tz ) )->format( 'Y-m-d H:i:s T' );

	$note  = $title . "\n";
	$note .= str_repeat( '-', 40 ) . "\n";
	$note .= implode( "\n", $lines );

	if ( ! empty( $intel_rows ) ) {
		$note .= "\n\n--- Lead Intel ---\n";
		$note .= implode( "\n", $intel_rows ) . "\n";
		$note .= 'Submitted: ' . $now;
	}

	return $note;
}

/**
 * Build the admin notification email HTML (Plinthra dark style).
 *
 * @param array  $fields    All sanitized form fields.
 * @param string $form_type 'general' | 'agent' | 'partner'
 */
function salefish_notification_email_html( array $fields, string $form_type ): string {
	$name    = esc_html( $fields['name']    ?? '' );
	$email   = esc_html( $fields['email']   ?? '' );
	$phone   = esc_html( $fields['phone']   ?? '' );
	$company = esc_html( $fields['company'] ?? '' );
	$date    = ( new DateTime( 'now', new DateTimeZone('America/Toronto') ) )->format( 'l, F j, Y \a\t g:i A T' );

	$form_label = 'Registration';

	// Separate _ctx_* fields from regular extra fields
	$ctx_label_map = [
		'_ctx_ip'       => 'IP Address',
		'_ctx_location' => 'Location',
		'_ctx_browser'  => 'Browser',
		'_ctx_os'       => 'Operating System',
		'_ctx_source'   => 'Source Page',
		'_ctx_section'  => 'CTA Section',
	];
	$ctx = [];
	foreach ( $fields as $key => $val ) {
		if ( str_starts_with( $key, '_ctx_' ) && $key !== '_ctx_ua' && (string) $val !== '' ) {
			$ctx[ $key ] = $val;
		}
	}

	// Build extra field rows, skipping core fields and _ctx_ fields already handled
	$skip       = [ 'name', 'email', 'phone', 'company', 'action', 'nonce' ];
	$extra_rows = '';
	foreach ( $fields as $key => $val ) {
		if ( in_array( $key, $skip, true ) || str_starts_with( $key, '_ctx_' ) || $val === '' ) continue;
		$label       = ucwords( str_replace( '_', ' ', $key ) );
		$extra_rows .= sprintf(
			'<tr><td style="color:#a1a1a1;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;width:40%%;">%s</td><td style="color:#ffffff;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;">%s</td></tr>',
			esc_html( $label ),
			esc_html( (string) $val )
		);
	}

	$phone_row   = $phone   ? '<tr><td style="color:#a1a1a1;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;width:40%;">Phone</td><td style="color:#ffffff;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;"><a href="tel:' . $phone . '" style="color:#a78bfa;text-decoration:none;">' . $phone . '</a></td></tr>' : '';
	$company_row = $company ? '<tr><td style="color:#a1a1a1;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;width:40%;">Company</td><td style="color:#ffffff;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;">' . $company . '</td></tr>' : '';

	// Build Lead Intel section HTML
	$intel_section = '';
	if ( ! empty( $ctx ) ) {
		$intel_rows_html = '';
		foreach ( $ctx as $ctx_key => $ctx_val ) {
			if ( ! isset( $ctx_label_map[ $ctx_key ] ) ) continue;
			$intel_rows_html .= sprintf(
				'<tr><td style="color:#a1a1a1;font-size:12px;padding:5px 0;width:40%%;">%s</td><td style="color:#a78bfa;font-size:12px;font-family:\'Courier New\',monospace;padding:5px 0;">%s</td></tr>',
				esc_html( $ctx_label_map[ $ctx_key ] ),
				esc_html( (string) $ctx_val )
			);
		}
		if ( $intel_rows_html ) {
			$intel_section  = '<table width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;">';
			$intel_section .= '<tr><td style="background-color:#12082a;border-radius:8px;padding:20px 24px;">';
			$intel_section .= '<div style="color:#7c3aed;font-size:10px;letter-spacing:2px;font-weight:700;text-transform:uppercase;margin-bottom:14px;">LEAD INTEL</div>';
			$intel_section .= '<table width="100%" cellpadding="0" cellspacing="0">' . $intel_rows_html . '</table>';
			$intel_section .= '</td></tr></table>';
		}
	}

	ob_start();
	?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="color-scheme" content="dark">
  <meta name="supported-color-schemes" content="dark">
  <title>New <?php echo $form_label; ?> — SaleFish</title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background-color:#0f0f0f;color-scheme:dark;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f0f0f;padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color:#1a1a1a;border-radius:8px;overflow:hidden;max-width:600px;">
          <!-- Header -->
          <tr>
            <td style="padding:40px 40px 30px;text-align:center;">
              <img src="<?php echo SALEFISH_EMAIL_LOGO; ?>" alt="SaleFish" style="height:40px;width:auto;margin-bottom:10px;">
            </td>
          </tr>
          <!-- Body -->
          <tr>
            <td style="padding:0 40px 40px;">
              <h1 style="color:#ffffff;font-size:28px;font-weight:700;line-height:1.2;margin:0 0 20px;text-align:left;">New <?php echo $form_label; ?></h1>
              <p style="color:#a1a1a1;font-size:16px;line-height:1.7;margin:0 0 30px;">A new contact submitted the <strong style="color:#ffffff;"><?php echo $form_label; ?></strong> form on salefish.app</p>

              <!-- Contact card -->
              <table width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                <tr>
                  <td style="background-color:#0f0f0f;border-radius:8px;padding:24px;">
                    <div style="font-size:28px;font-weight:700;color:#ffffff;margin-bottom:4px;"><?php echo $name; ?></div>
                    <div style="font-size:14px;color:#7c3aed;margin-bottom:20px;"><?php echo $form_label; ?></div>
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td style="color:#a1a1a1;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;width:40%;">Email</td>
                        <td style="color:#ffffff;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;">
                          <a href="mailto:<?php echo $email; ?>" style="color:#a78bfa;text-decoration:none;"><?php echo $email; ?></a>
                        </td>
                      </tr>
                      <?php echo $phone_row; ?>
                      <?php echo $company_row; ?>
                      <?php echo $extra_rows; ?>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- Timestamp -->
              <table width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                <tr>
                  <td style="background-color:#2a1f3d;border-left:4px solid #7c3aed;border-radius:4px;padding:15px;">
                    <p style="color:#a78bfa;font-size:14px;margin:0;">Submitted on <?php echo $date; ?></p>
                  </td>
                </tr>
              </table>

              <?php echo $intel_section; ?>

              <p style="color:#666666;font-size:14px;line-height:1.6;margin:30px 0 0;">This contact has been added to ActiveCampaign &ldquo;Website Registrations&rdquo; list.</p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="padding:30px 40px;border-top:1px solid #2a2a2a;text-align:center;">
              <p style="color:#666666;font-size:14px;margin:0 0 8px;font-weight:600;">Real Estate Inventory &amp; Sales Powered by SaleFish</p>
              <p style="color:#4a4a4a;font-size:13px;margin:0;">
                <a href="https://salefish.app/privacy-policy" style="color:#4a4a4a;text-decoration:underline;">Privacy Policy</a>
                &nbsp;&middot;&nbsp;
                <a href="https://salefish.app/terms-of-use" style="color:#4a4a4a;text-decoration:underline;">Terms of Use</a>
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
	<?php
	return ob_get_clean();
}

/**
 * Build the autoresponder email HTML sent to the registrant.
 *
 * @param string $first_name
 * @param string $form_type 'general' | 'agent' | 'partner'
 */
function salefish_autoresponder_email_html( string $first_name, string $form_type ): string {
	$first_name = esc_html( $first_name );

	$copy = [
		'general' => [
			'headline' => "Thanks for registering, {$first_name}.",
			'body'     => "We've received your details and will be in touch shortly. In the meantime, feel free to explore what SaleFish can do for your sales team.",
			'cta'      => 'Explore SaleFish',
			'url'      => 'https://salefish.app',
		],
		'agent' => [
			'headline' => "Welcome to the SaleFish network, {$first_name}.",
			'body'     => "We've received your agent registration. A member of our team will reach out soon to walk you through the platform and the projects available in your market.",
			'cta'      => 'Learn More',
			'url'      => 'https://salefish.app',
		],
		'partner' => [
			'headline' => "Let's build something together, {$first_name}.",
			'body'     => "We've received your partner inquiry. Our partnerships team will review your submission and reach out within one business day.",
			'cta'      => 'Learn About Partnerships',
			'url'      => 'https://salefish.app/partners',
		],
	];

	$c = $copy[ $form_type ] ?? $copy['general'];

	ob_start();
	?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="color-scheme" content="dark">
  <meta name="supported-color-schemes" content="dark">
  <title>Thanks for registering — SaleFish</title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background-color:#0f0f0f;color-scheme:dark;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f0f0f;padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color:#1a1a1a;border-radius:8px;overflow:hidden;max-width:600px;">
          <!-- Header -->
          <tr>
            <td style="padding:40px 40px 30px;text-align:center;">
              <img src="<?php echo SALEFISH_EMAIL_LOGO; ?>" alt="SaleFish" style="height:40px;width:auto;margin-bottom:10px;">
            </td>
          </tr>
          <!-- Body -->
          <tr>
            <td style="padding:0 40px 40px;">
              <h1 style="color:#ffffff;font-size:28px;font-weight:700;margin:0 0 20px;line-height:1.2;"><?php echo $c['headline']; ?></h1>
              <p style="color:#a1a1a1;font-size:16px;line-height:1.7;margin:0 0 30px;"><?php echo $c['body']; ?></p>

              <!-- CTA button -->
              <table cellpadding="0" cellspacing="0" style="margin:0 0 30px;">
                <tr>
                  <td style="background-color:#7c3aed;border-radius:6px;">
                    <a href="<?php echo esc_attr( $c['url'] ); ?>" style="display:inline-block;padding:14px 28px;color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;letter-spacing:0.3px;"><?php echo esc_html( $c['cta'] ); ?></a>
                  </td>
                </tr>
              </table>

              <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
                <tr><td style="border-top:1px solid #2a2a2a;"></td></tr>
              </table>

              <p style="color:#555555;font-size:13px;line-height:1.6;margin:0;">You're receiving this because you registered at <a href="https://salefish.app" style="color:#a78bfa;text-decoration:none;">salefish.app</a>. If this wasn't you, you can safely ignore this email.</p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="padding:30px 40px;border-top:1px solid #2a2a2a;text-align:center;">
              <p style="color:#666666;font-size:14px;margin:0 0 8px;font-weight:600;">Real Estate Inventory &amp; Sales Powered by SaleFish</p>
              <p style="color:#4a4a4a;font-size:13px;margin:0;">
                <a href="https://salefish.app/privacy-policy" style="color:#4a4a4a;text-decoration:underline;">Privacy Policy</a>
                &nbsp;&middot;&nbsp;
                <a href="https://salefish.app/terms-of-use" style="color:#4a4a4a;text-decoration:underline;">Terms of Use</a>
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
	<?php
	return ob_get_clean();
}

/**
 * Send the admin notification email to hello@salefish.app.
 */
function salefish_send_notification( array $fields, string $form_type ): bool {
	$to      = 'hello@salefish.app';
	$subject = 'New Registration — SaleFish';
	$html    = salefish_notification_email_html( $fields, $form_type );
	$headers = [
		'Content-Type: text/html; charset=UTF-8',
		'From: SaleFish <hello@salefish.app>',
	];
	$sent = wp_mail( $to, $subject, $html, $headers );
	if ( $sent ) {
		error_log( "[SaleFish] Notification email sent OK: {$subject} → {$to}" );
	}
	return $sent;
}

/**
 * Complete an email-verified registration: add to AC and send internal notification.
 * Called by Salefish_Email_Verify::handle_verification() after the user clicks the link.
 */
function salefish_complete_registration( string $type, array $f ): void {
	require_once plugin_dir_path( __FILE__ ) . 'class-activecampaign.php';

	$parts      = explode( ' ', $f['name'] ?? '', 2 );
	$first_name = $parts[0] ?? '';
	$last_name  = $parts[1] ?? '';

	$ac         = new Salefish_ActiveCampaign();
	$contact_id = $ac->upsert_contact( [
		'email'      => $f['email']  ?? '',
		'first_name' => $first_name,
		'last_name'  => $last_name,
		'phone'      => $f['phone']  ?? '',
	] );

	if ( ! $contact_id ) {
		return;
	}

	$ac->subscribe_to_list( $contact_id );

	if ( $type === 'agent' ) {
		$ac->add_tag( $contact_id, 'agent-registration' );
		$ac->set_field( $contact_id, 1, 'Real Estate Agent' );
		if ( ! empty( $f['brokerage'] ) ) {
			$ac->set_field( $contact_id, 2, $f['brokerage'] );
		}
		$auto_id = defined( 'SALEFISH_AC_AUTO_AGENT' ) ? (int) SALEFISH_AC_AUTO_AGENT : 0;
		$ac->add_to_automation( $contact_id, $auto_id );
		$note_data = array_merge( [
			'name'    => $f['name']        ?? '',
			'email'   => $f['email']       ?? '',
			'phone'   => $f['phone']       ?? '',
			'company' => $f['brokerage']   ?? '',
			'website' => $f['website_url'] ?? '',
		], $f['_ctx'] ?? [] );
		$ac->add_note( $contact_id, salefish_format_ac_note( $note_data, 'agent' ) );
		salefish_send_notification( $note_data, 'agent' );
		salefish_send_autoresponder( $f['email'] ?? '', $first_name, 'agent' );

	} elseif ( $type === 'partner' ) {
		$ac->add_tag( $contact_id, 'partner-registration' );
		if ( ! empty( $f['want_to_do'] ) ) {
			$ac->set_field( $contact_id, 1, $f['want_to_do'] );
		}
		if ( ! empty( $f['company'] ) ) {
			$ac->set_field( $contact_id, 2, $f['company'] );
		}
		$auto_id = defined( 'SALEFISH_AC_AUTO_PARTNER' ) ? (int) SALEFISH_AC_AUTO_PARTNER : 0;
		$ac->add_to_automation( $contact_id, $auto_id );
		$note_data = array_merge( [
			'name'       => $f['name']       ?? '',
			'email'      => $f['email']      ?? '',
			'phone'      => $f['phone']      ?? '',
			'company'    => $f['company']    ?? '',
			'want_to_do' => $f['want_to_do'] ?? '',
			'clients'    => $f['clients']    ?? '',
		], $f['_ctx'] ?? [] );
		$ac->add_note( $contact_id, salefish_format_ac_note( $note_data, 'partner' ) );
		salefish_send_notification( $note_data, 'partner' );
		salefish_send_autoresponder( $f['email'] ?? '', $first_name, 'partner' );

	} elseif ( $type === 'general' ) {
		$ac->add_tag( $contact_id, 'website-registration' );
		if ( ! empty( $f['company'] ) ) {
			$ac->set_field( $contact_id, 2, $f['company'] );
		}
		$auto_id = defined( 'SALEFISH_AC_AUTO_GENERAL' ) ? (int) SALEFISH_AC_AUTO_GENERAL : 0;
		$ac->add_to_automation( $contact_id, $auto_id );
		$note_data = array_merge( [
			'name'    => $f['name']    ?? '',
			'email'   => $f['email']   ?? '',
			'phone'   => $f['phone']   ?? '',
			'company' => $f['company'] ?? '',
		], $f['_ctx'] ?? [] );
		$ac->add_note( $contact_id, salefish_format_ac_note( $note_data, 'general' ) );
		salefish_send_notification( $note_data, 'general' );
		salefish_send_autoresponder( $f['email'] ?? '', $first_name, 'general' );
	}
}

/**
 * Send the autoresponder to the registrant.
 */
function salefish_send_autoresponder( string $to_email, string $first_name, string $form_type ): bool {
	$subject = 'We received your registration — SaleFish';
	$html    = salefish_autoresponder_email_html( $first_name, $form_type );
	$headers = [
		'Content-Type: text/html; charset=UTF-8',
		'From: SaleFish <hello@salefish.app>',
		'Reply-To: hello@salefish.app',
	];
	$sent = wp_mail( $to_email, $subject, $html, $headers );
	if ( $sent ) {
		error_log( "[SaleFish] Autoresponder sent OK → {$to_email}" );
	}
	return $sent;
}
