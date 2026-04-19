<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SALEFISH_EMAIL_LOGO', 'data:image/png;base64,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' );

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
  <title>New <?php echo $form_label; ?> — SaleFish</title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background-color:#0f0f0f;">
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
              <h1 style="color:#ffffff;font-size:24px;font-weight:600;margin:0 0 10px;text-align:left;">New <?php echo $form_label; ?></h1>
              <p style="color:#a1a1a1;font-size:16px;line-height:1.6;margin:0 0 30px;">A new contact submitted the <strong style="color:#ffffff;"><?php echo $form_label; ?></strong> form on salefish.app</p>

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
              <p style="color:#666666;font-size:14px;margin:0 0 5px;font-weight:600;">Real Estate Inventory &amp; Sales Powered by SaleFish</p>
              <p style="color:#4a4a4a;font-size:13px;margin:0;">Real Estate Sales Management Platform</p>
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
  <title>Thanks for registering — SaleFish</title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background-color:#0f0f0f;">
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
