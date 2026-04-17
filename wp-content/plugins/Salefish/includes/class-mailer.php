<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Configure outbound mail for SaleFish registration emails.
 *
 * On the production server (cPanel / appsfish) PHPMailer's default sendmail
 * path works fine as-is — this hook only sets the correct From identity so
 * cPanel's MTA accepts the message without relaying rejections.
 *
 * If SALEFISH_SMTP_HOST / SALEFISH_SMTP_PASS are defined in wp-config.php
 * those credentials are used instead (useful for switching to an external
 * relay later without touching plugin code).
 */
function salefish_configure_smtp( PHPMailer\PHPMailer\PHPMailer $mailer ): void {
	// Hard SMTP override — only active when credentials are set.
	if ( defined( 'SALEFISH_SMTP_PASS' ) && SALEFISH_SMTP_PASS !== '' ) {
		$mailer->isSMTP();
		$mailer->Host       = defined( 'SALEFISH_SMTP_HOST' )   ? SALEFISH_SMTP_HOST   : 'localhost';
		$mailer->Port       = defined( 'SALEFISH_SMTP_PORT' )   ? SALEFISH_SMTP_PORT   : 465;
		$mailer->SMTPAuth   = true;
		$mailer->Username   = defined( 'SALEFISH_SMTP_USER' )   ? SALEFISH_SMTP_USER   : '';
		$mailer->Password   = SALEFISH_SMTP_PASS;
		$mailer->SMTPSecure = defined( 'SALEFISH_SMTP_SECURE' ) ? SALEFISH_SMTP_SECURE : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
		return;
	}

	// Default: use the server's sendmail binary (works on cPanel out of the box).
	// Force the envelope sender to match the From header so SPF passes.
	$mailer->Sender = 'admin@salefish.app';
}
add_action( 'phpmailer_init', 'salefish_configure_smtp' );

/**
 * Log wp_mail failures to the WordPress debug log.
 */
function salefish_log_mail_failure( WP_Error $error ): void {
	error_log( '[SaleFish] wp_mail failed: ' . $error->get_error_message() );
	$data = $error->get_error_data();
	if ( ! empty( $data['phpmailer_exception_code'] ) ) {
		error_log( '[SaleFish] PHPMailer code: ' . $data['phpmailer_exception_code'] );
	}
}
add_action( 'wp_mail_failed', 'salefish_log_mail_failure' );
