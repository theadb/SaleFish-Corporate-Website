<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
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

	$emails = [
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

	$nonce       = wp_create_nonce( 'salefish_send_previews' );
	$all_configured = array_sum( $autos ) > 0;
	?>
	<div class="wrap">
		<h1>SaleFish Email Previews</h1>

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
		</div>

		<div style="display:flex;align-items:center;gap:16px;margin-bottom:24px">
			<button id="sf-trigger-test" class="button button-primary" <?php echo $all_configured ? '' : 'disabled title="Configure automation IDs in wp-config.php first"'; ?>>
				Trigger Test Automations for andrewdb@salefish.app
			</button>
			<span id="sf-send-status" style="font-weight:bold"></span>
		</div>

		<?php if ( ! $all_configured ): ?>
		<p style="color:#856404;margin-bottom:24px">
			⚠ Automation IDs not yet set in <code>wp-config.php</code> — the trigger button will activate once all three are filled in.
		</p>
		<?php endif; ?>

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
	</script>
	<?php
}
