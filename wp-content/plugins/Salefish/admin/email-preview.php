<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/class-email-verify.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/email-templates.php';

// ── Admin menu ────────────────────────────────────────────────────────────────

function salefish_email_preview_menu() {
	add_submenu_page(
		'tools.php',
		'SaleFish Email Previews',
		'Email Previews',
		'manage_options',
		'salefish-email-preview',
		'salefish_email_preview_page'
	);
}
add_action( 'admin_menu', 'salefish_email_preview_menu' );

// ── AJAX: trigger AC automations for a test contact ───────────────────────────

function salefish_trigger_preview_automations() {
	check_ajax_referer( 'salefish_send_previews', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

	$ac = new Salefish_ActiveCampaign();

	$contact_id = $ac->upsert_contact( [
		'email'      => 'andrewdb@salefish.app',
		'first_name' => 'Andrew',
		'last_name'  => 'Blair',
		'phone'      => '416-555-0100',
	] );

	if ( ! $contact_id ) {
		wp_send_json_error( 'Could not create/find test contact in ActiveCampaign.' );
	}

	$ac->subscribe_to_list( $contact_id );

	$results = [];
	$autos   = [
		'general' => defined( 'SALEFISH_AC_AUTO_GENERAL' ) ? (int) SALEFISH_AC_AUTO_GENERAL : 0,
		'agent'   => defined( 'SALEFISH_AC_AUTO_AGENT' )   ? (int) SALEFISH_AC_AUTO_AGENT   : 0,
		'partner' => defined( 'SALEFISH_AC_AUTO_PARTNER' ) ? (int) SALEFISH_AC_AUTO_PARTNER  : 0,
	];

	foreach ( $autos as $type => $id ) {
		if ( $id === 0 ) {
			$results[ $type ] = 'not_configured';
		} else {
			$results[ $type ] = $ac->add_to_automation( $contact_id, $id ) ? 'triggered' : 'failed';
		}
	}

	wp_send_json_success( [ 'contact_id' => $contact_id, 'automations' => $results ] );
}
add_action( 'wp_ajax_salefish_trigger_preview_automations', 'salefish_trigger_preview_automations' );

// ── AJAX: SMTP diagnostic — tests each layer and captures PHPMailer debug ────

function salefish_mail_diagnostic() {
	check_ajax_referer( 'salefish_send_previews', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

	$log = [];

	// Layer 1 — TCP connectivity to SendGrid port 587
	$log[] = '=== Layer 1: TCP connectivity to smtp.sendgrid.net:587 ===';
	$errno = 0; $errstr = '';
	$socket = @fsockopen( 'smtp.sendgrid.net', 587, $errno, $errstr, 8 );
	if ( $socket ) {
		fclose( $socket );
		$log[] = '✓ TCP connection SUCCEEDED';
	} else {
		$log[] = "✗ TCP connection FAILED — errno={$errno} errstr={$errstr}";
		$log[] = 'DIAGNOSIS: Server firewall is blocking outbound port 587. SMTP cannot work.';
	}

	// Layer 2 — PHP extensions
	$log[] = '';
	$log[] = '=== Layer 2: PHP extensions ===';
	$log[] = 'OpenSSL: ' . ( extension_loaded( 'openssl' ) ? '✓ loaded' : '✗ MISSING — TLS impossible' );
	$log[] = 'OpenSSL version: ' . ( defined( 'OPENSSL_VERSION_TEXT' ) ? OPENSSL_VERSION_TEXT : 'unknown' );

	// Layer 3 — SMTP constants
	$log[] = '';
	$log[] = '=== Layer 3: SMTP configuration in wp-config.php ===';
	$log[] = 'SALEFISH_SMTP_HOST:   ' . ( defined( 'SALEFISH_SMTP_HOST'   ) ? SALEFISH_SMTP_HOST   : 'NOT DEFINED' );
	$log[] = 'SALEFISH_SMTP_PORT:   ' . ( defined( 'SALEFISH_SMTP_PORT'   ) ? SALEFISH_SMTP_PORT   : 'NOT DEFINED' );
	$log[] = 'SALEFISH_SMTP_USER:   ' . ( defined( 'SALEFISH_SMTP_USER'   ) ? SALEFISH_SMTP_USER   : 'NOT DEFINED' );
	$log[] = 'SALEFISH_SMTP_PASS:   ' . ( defined( 'SALEFISH_SMTP_PASS'   ) ? '(set, length=' . strlen( SALEFISH_SMTP_PASS ) . ')' : 'NOT DEFINED' );
	$log[] = 'SALEFISH_SMTP_SECURE: ' . ( defined( 'SALEFISH_SMTP_SECURE' ) ? SALEFISH_SMTP_SECURE : 'NOT DEFINED' );

	// Layer 4 — wp_mail() with full PHPMailer debug capture
	$log[] = '';
	$log[] = '=== Layer 4: wp_mail() test (sending to andrewdavidblair@gmail.com) ===';

	$debug_lines = [];
	$debug_hook  = function ( PHPMailer\PHPMailer\PHPMailer $mailer ) use ( &$debug_lines ) {
		$mailer->SMTPDebug   = 3; // 3 = full client+server transcript
		$mailer->Debugoutput = function ( string $msg, int $level ) use ( &$debug_lines ) {
			$debug_lines[] = trim( $msg );
		};
	};
	add_action( 'phpmailer_init', $debug_hook, 20 );

	$mail_error = null;
	$fail_hook  = function ( WP_Error $err ) use ( &$mail_error ) {
		$mail_error = $err->get_error_message();
		$data       = $err->get_error_data();
		if ( ! empty( $data['phpmailer_exception_code'] ) ) {
			$mail_error .= ' (code ' . $data['phpmailer_exception_code'] . ')';
		}
	};
	add_action( 'wp_mail_failed', $fail_hook );

	$sent = wp_mail(
		'andrewdavidblair@gmail.com',
		'SaleFish SMTP Diagnostic — ' . date( 'H:i:s' ),
		'<p>This is a diagnostic email from the SaleFish admin panel. If you received it, SMTP is working.</p>',
		[ 'Content-Type: text/html; charset=UTF-8', 'From: SaleFish <hello@salefish.app>' ]
	);

	remove_action( 'phpmailer_init', $debug_hook, 20 );
	remove_action( 'wp_mail_failed', $fail_hook );

	$log[] = 'wp_mail() returned: ' . ( $sent ? '✓ TRUE — accepted by transport' : '✗ FALSE — transport rejected it' );
	if ( $mail_error ) {
		$log[] = 'wp_mail_failed error: ' . $mail_error;
	}
	if ( $debug_lines ) {
		$log[] = '';
		$log[] = '--- PHPMailer SMTP transcript ---';
		foreach ( $debug_lines as $line ) {
			$log[] = $line;
		}
	} else {
		$log[] = '(No PHPMailer debug output captured — transport may not be SMTP)';
	}

	wp_send_json_success( [ 'log' => $log ] );
}
add_action( 'wp_ajax_salefish_mail_diagnostic', 'salefish_mail_diagnostic' );

// ── AJAX: send all 7 test emails directly via wp_mail() ──────────────────────

function salefish_send_test_emails() {
	check_ajax_referer( 'salefish_send_previews', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

	// Send to real Gmail inbox so we bypass any salefish.app mailbox issues
	$test_email = 'andrewdavidblair@gmail.com';
	$test_first = 'Andrew';

	$sample_general = [
		'name'    => 'Andrew Blair',
		'email'   => $test_email,
		'phone'   => '416-555-0100',
		'company' => 'Acme Realty',
		'title'   => 'Sales Director',
		'demo'    => 'Yes',
	];
	$sample_agent = array_merge( $sample_general, [
		'brokerage'           => 'Acme Realty',
		'geo_expertise'       => 'Greater Toronto Area',
		'property_expertise'  => 'Condos',
	] );
	$sample_partner = array_merge( $sample_general, [
		'want_to_do' => 'Refer builders, brokers, or developers',
		'clients'    => '4–10',
	] );

	$results = [];

	// 1. Verification email
	$token = Salefish_Email_Verify::create( 'general', $sample_general );
	$results['verification'] = Salefish_Email_Verify::send_confirmation( $test_email, $token, 'general' ) ? 'sent' : 'failed';

	// 2–4. Admin notification emails (redirected to Gmail for testing)
	$notif_headers = [
		'Content-Type: text/html; charset=UTF-8',
		'From: SaleFish <hello@salefish.app>',
	];
	$results['notification_general'] = wp_mail( $test_email, '[TEST] New Registration — SaleFish (General)', salefish_notification_email_html( $sample_general, 'general' ), $notif_headers ) ? 'sent' : 'failed';
	$results['notification_agent']   = wp_mail( $test_email, '[TEST] New Registration — SaleFish (Agent)',   salefish_notification_email_html( $sample_agent,   'agent'   ), $notif_headers ) ? 'sent' : 'failed';
	$results['notification_partner'] = wp_mail( $test_email, '[TEST] New Registration — SaleFish (Partner)', salefish_notification_email_html( $sample_partner, 'partner' ), $notif_headers ) ? 'sent' : 'failed';

	// 5–7. Autoresponder emails (→ Gmail)
	$results['autoresponder_general'] = salefish_send_autoresponder( $test_email, $test_first, 'general' ) ? 'sent' : 'failed';
	$results['autoresponder_agent']   = salefish_send_autoresponder( $test_email, $test_first, 'agent'   ) ? 'sent' : 'failed';
	$results['autoresponder_partner'] = salefish_send_autoresponder( $test_email, $test_first, 'partner' ) ? 'sent' : 'failed';

	wp_send_json_success( $results );
}
add_action( 'wp_ajax_salefish_send_test_emails', 'salefish_send_test_emails' );

// ── Admin page render ─────────────────────────────────────────────────────────

function salefish_email_preview_page() {
	$sample = [
		'name'    => 'Jane Smith',
		'email'   => 'andrewdb@salefish.app',
		'phone'   => '416-555-0100',
		'company' => 'Acme Realty',
	];

	$autos = [
		'general' => defined( 'SALEFISH_AC_AUTO_GENERAL' ) ? (int) SALEFISH_AC_AUTO_GENERAL : 0,
		'agent'   => defined( 'SALEFISH_AC_AUTO_AGENT' )   ? (int) SALEFISH_AC_AUTO_AGENT   : 0,
		'partner' => defined( 'SALEFISH_AC_AUTO_PARTNER' ) ? (int) SALEFISH_AC_AUTO_PARTNER  : 0,
	];

	// Verification email uses a placeholder URL for preview
	$preview_verify_url = home_url( '/thank-you-for-registering/?salefish_verify=PREVIEW_TOKEN_ONLY' );

	$emails = [
		'verification'          => [
			'label'    => 'Verification — Confirm Registration (to registrant)',
			'html'     => Salefish_Email_Verify::build_confirmation_html( $preview_verify_url ),
			'auto_key' => null,
		],
		'general_autoresponder' => [
			'label'    => 'General — Autoresponder (to registrant)',
			'html'     => salefish_autoresponder_email_html( 'Jane', 'general' ),
			'auto_key' => 'general',
		],
		'agent_autoresponder'   => [
			'label'    => 'Agent — Autoresponder (to registrant)',
			'html'     => salefish_autoresponder_email_html( 'Jane', 'agent' ),
			'auto_key' => 'agent',
		],
		'partner_autoresponder' => [
			'label'    => 'Partner — Autoresponder (to registrant)',
			'html'     => salefish_autoresponder_email_html( 'Jane', 'partner' ),
			'auto_key' => 'partner',
		],
		'general_notification'  => [
			'label'    => 'General — Admin Notification (to hello@salefish.app)',
			'html'     => salefish_notification_email_html( $sample, 'general' ),
			'auto_key' => null,
		],
		'agent_notification'    => [
			'label'    => 'Agent — Admin Notification (to hello@salefish.app)',
			'html'     => salefish_notification_email_html( array_merge( $sample, [ 'brokerage' => 'Acme Realty', 'geo_expertise' => 'Greater Toronto', 'property_expertise' => 'Condos' ] ), 'agent' ),
			'auto_key' => null,
		],
		'partner_notification'  => [
			'label'    => 'Partner — Admin Notification (to hello@salefish.app)',
			'html'     => salefish_notification_email_html( array_merge( $sample, [ 'want_to_do' => 'Refer builders, brokers, or developers', 'clients' => '4–10' ] ), 'partner' ),
			'auto_key' => null,
		],
	];

	$nonce          = wp_create_nonce( 'salefish_send_previews' );
	$all_configured = array_sum( $autos ) > 0;
	?>
	<div class="wrap">
		<h1>SaleFish Email Previews</h1>

		<!-- SMTP Diagnostic panel -->
		<div style="background:#e3f2fd;border:1px solid #2196f3;border-radius:4px;padding:16px 20px;margin-bottom:12px;max-width:800px">
			<strong>Step 1 — Run SMTP Diagnostic (start here if emails aren't arriving):</strong>
			<p style="margin:8px 0 12px;color:#333;font-size:13px;">
				Sends ONE email to <code>andrewdavidblair@gmail.com</code> and captures the full PHPMailer SMTP transcript.
				Reveals exactly which layer is failing: TCP, TLS, auth, or delivery.
			</p>
			<div style="display:flex;align-items:center;gap:16px;">
				<button id="sf-diagnostic" class="button button-primary">Run SMTP Diagnostic</button>
				<span id="sf-diag-status" style="font-weight:bold;font-size:13px;"></span>
			</div>
			<pre id="sf-diag-log" style="display:none;margin-top:12px;font-size:11px;line-height:1.5;background:#0a0a0a;color:#e0e0e0;padding:14px;border-radius:4px;overflow-x:auto;white-space:pre-wrap;"></pre>
		</div>

		<!-- Test send panel -->
		<div style="background:#e8f5e9;border:1px solid #4caf50;border-radius:4px;padding:16px 20px;margin-bottom:16px;max-width:800px">
			<strong>Step 2 — Send all 7 test emails via wp_mail():</strong>
			<p style="margin:8px 0 12px;color:#333;font-size:13px;">
				Sends verification + 3 autoresponders + 3 admin notifications to <code>andrewdavidblair@gmail.com</code>.
				No ActiveCampaign involvement — run the diagnostic first to confirm SMTP is working.
			</p>
			<div style="display:flex;align-items:center;gap:16px;">
				<button id="sf-send-emails" class="button button-primary">Send All 7 Test Emails Now</button>
				<span id="sf-email-status" style="font-weight:bold;font-size:13px;"></span>
			</div>
			<div id="sf-email-results" style="display:none;margin-top:12px;font-family:monospace;font-size:12px;background:#fff;padding:10px;border-radius:4px;border:1px solid #ccc;"></div>
		</div>

		<!-- AC automation panel -->
		<div style="background:#fff3cd;border:1px solid #ffc107;border-radius:4px;padding:16px 20px;margin-bottom:24px;max-width:800px">
			<strong>Setup required — create 3 automations in ActiveCampaign:</strong>
			<ol style="margin:10px 0 0 20px;line-height:1.8">
				<li>Go to <strong>ActiveCampaign → Automations → New Automation → Start from scratch</strong></li>
				<li><strong>Trigger:</strong> "Subscribes to a list" → Website Registrations (List 4)</li>
				<li>Add condition: "Tag is" → one of <code>website-registration</code>, <code>agent-registration</code>, or <code>partner-registration</code></li>
				<li>Add step: <strong>"Send an email"</strong> — paste the HTML from the preview below into a new email template</li>
				<li>Add step: <strong>"Send notification email"</strong> → to <code>hello@salefish.app</code> — paste the notification HTML</li>
				<li>Save and activate the automation. <strong>Copy its ID from the URL</strong> (<code>/automations/<strong>42</strong>/edit</code>)</li>
				<li>Paste each ID into <code>wp-config.php</code>: <code>SALEFISH_AC_AUTO_GENERAL</code>, <code>SALEFISH_AC_AUTO_AGENT</code>, <code>SALEFISH_AC_AUTO_PARTNER</code></li>
			</ol>
			<div style="display:flex;align-items:center;gap:16px;margin-top:16px">
				<button id="sf-trigger-test" class="button button-secondary" <?php echo $all_configured ? '' : 'disabled title="Configure automation IDs in wp-config.php first"'; ?>>
					Trigger Test Automations for andrewdb@salefish.app
				</button>
				<span id="sf-send-status" style="font-weight:bold"></span>
			</div>
			<?php if ( ! $all_configured ): ?>
			<p style="color:#856404;margin:10px 0 0">
				⚠ Automation IDs not yet set in <code>wp-config.php</code> — the trigger button will activate once all three are filled in.
			</p>
			<?php endif; ?>
		</div>

		<nav style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px">
			<?php foreach ( $emails as $key => $e ): ?>
				<a href="#<?php echo esc_attr( $key ); ?>"><button class="button"><?php echo esc_html( $e['label'] ); ?></button></a>
			<?php endforeach; ?>
		</nav>

		<?php foreach ( $emails as $key => $e ): ?>
		<div id="<?php echo esc_attr( $key ); ?>" style="margin-bottom:48px">
			<h2 style="border-bottom:1px solid #ccc;padding-bottom:6px"><?php echo esc_html( $e['label'] ); ?></h2>
			<?php if ( $e['auto_key'] ): $id = $autos[ $e['auto_key'] ]; ?>
			<p style="color:<?php echo $id ? 'green' : '#856404'; ?>;margin:0 0 10px">
				<?php echo $id ? "✓ Automation ID: {$id}" : '⚠ Automation ID not yet set — add SALEFISH_AC_AUTO_' . strtoupper( $e['auto_key'] ) . ' to wp-config.php'; ?>
			</p>
			<?php endif; ?>
			<details style="margin-bottom:10px">
				<summary style="cursor:pointer;color:#2271b1">Copy HTML for ActiveCampaign email editor</summary>
				<textarea style="width:100%;height:200px;font-family:monospace;font-size:12px;margin-top:8px" readonly><?php echo esc_textarea( $e['html'] ); ?></textarea>
			</details>
			<iframe srcdoc="<?php echo esc_attr( $e['html'] ); ?>"
				style="width:100%;height:700px;border:1px solid #ccc;border-radius:4px;background:#fff"
				title="<?php echo esc_attr( $e['label'] ); ?>">
			</iframe>
		</div>
		<?php endforeach; ?>
	</div>

	<script>
	// SMTP Diagnostic
	document.getElementById('sf-diagnostic').addEventListener('click', function() {
		var btn    = this;
		var status = document.getElementById('sf-diag-status');
		var log    = document.getElementById('sf-diag-log');
		btn.disabled = true;
		status.style.color = '#333';
		status.textContent = 'Running diagnostic…';
		log.style.display = 'none';

		var fd = new FormData();
		fd.append('action', 'salefish_mail_diagnostic');
		fd.append('nonce', '<?php echo esc_js( $nonce ); ?>');
		fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', { method: 'POST', body: fd })
			.then(function(r) { return r.text(); })
			.then(function(raw) {
				log.style.display = 'block';
				try {
					var res = JSON.parse(raw);
					if (res.success && res.data && res.data.log) {
						log.textContent = res.data.log.join('\n');
						var hasError = res.data.log.some(function(l){ return l.indexOf('✗') !== -1 || l.indexOf('FAILED') !== -1; });
						status.style.color = hasError ? 'orange' : 'green';
						status.textContent = hasError ? '⚠ Issues found — see transcript' : '✓ Diagnostic complete';
					} else {
						log.textContent = 'Unexpected response:\n' + JSON.stringify(res, null, 2);
						status.style.color = 'red';
						status.textContent = 'Error — see log';
					}
				} catch(e) {
					log.textContent = 'Raw response (not JSON):\n' + raw;
					status.style.color = 'red';
					status.textContent = 'PHP error — see raw response above';
				}
				btn.disabled = false;
			})
			.catch(function(err) {
				status.style.color = 'red';
				status.textContent = 'Network error: ' + err.message;
				btn.disabled = false;
			});
	});

	// Trigger AC automations
	document.getElementById('sf-trigger-test').addEventListener('click', function() {
		var btn = this, status = document.getElementById('sf-send-status');
		btn.disabled = true;
		status.textContent = 'Triggering automations in ActiveCampaign…';
		var fd = new FormData();
		fd.append('action', 'salefish_trigger_preview_automations');
		fd.append('nonce', '<?php echo esc_js( $nonce ); ?>');
		fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', { method: 'POST', body: fd })
			.then(r => r.json())
			.then(function(res) {
				if (res.success) {
					var d = res.data.automations;
					var lines = Object.entries(d).map(([k,v]) => k + ': ' + v).join(' | ');
					status.style.color = Object.values(d).every(v => v === 'triggered') ? 'green' : 'orange';
					status.textContent = '✓ Contact ID ' + res.data.contact_id + ' — ' + lines;
				} else {
					status.style.color = 'red';
					status.textContent = 'Error: ' + (res.data || 'unknown');
				}
				btn.disabled = false;
			});
	});

	// Send test emails via wp_mail()
	document.getElementById('sf-send-emails').addEventListener('click', function() {
		var btn    = this;
		var status = document.getElementById('sf-email-status');
		var results = document.getElementById('sf-email-results');
		btn.disabled = true;
		status.style.color = '#333';
		status.textContent = 'Sending 7 emails…';
		results.style.display = 'none';

		var fd = new FormData();
		fd.append('action', 'salefish_send_test_emails');
		fd.append('nonce', '<?php echo esc_js( $nonce ); ?>');
		fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', { method: 'POST', body: fd })
			.then(r => r.json())
			.then(function(res) {
				if (res.success) {
					var d = res.data;
					var allSent = Object.values(d).every(v => v === 'sent');
					status.style.color = allSent ? 'green' : 'orange';
					status.textContent = allSent ? '✓ All 7 emails sent!' : '⚠ Some emails failed — see results';
					results.style.display = 'block';
					results.innerHTML = Object.entries(d)
						.map(([k, v]) => '<span style="color:' + (v === 'sent' ? 'green' : 'red') + '">' + (v === 'sent' ? '✓' : '✗') + '</span> ' + k + ': <strong>' + v + '</strong>')
						.join('<br>');
				} else {
					status.style.color = 'red';
					status.textContent = 'Error: ' + (res.data || 'unknown');
				}
				btn.disabled = false;
			})
			.catch(function(err) {
				status.style.color = 'red';
				status.textContent = 'Network error — check browser console';
				btn.disabled = false;
			});
	});
	</script>
	<?php
}
