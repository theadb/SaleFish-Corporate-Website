<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Salefish_Email_Verify {

	const OPTION_PREFIX = 'sf_reg_';
	const TTL           = 172800; // 48 hours

	/**
	 * Store a pending registration directly in wp_options (bypasses object cache /
	 * WP Super Cache) and return the verification token.
	 */
	public static function create( string $type, array $fields ): string {
		$token = bin2hex( random_bytes( 32 ) );
		update_option(
			self::OPTION_PREFIX . $token,
			[
				'type'    => $type,
				'fields'  => $fields,
				'expires' => time() + self::TTL,
			],
			false  // autoload = false — keeps wp_options lean
		);
		return $token;
	}

	/**
	 * Retrieve a pending registration by token. Returns false if expired/invalid.
	 */
	public static function get( string $token ) {
		$data = get_option( self::OPTION_PREFIX . $token, false );
		if ( ! $data ) {
			return false;
		}
		// Manual expiry check — options never expire on their own.
		if ( isset( $data['expires'] ) && time() > $data['expires'] ) {
			self::delete( $token );
			return false;
		}
		return $data;
	}

	/**
	 * Delete a pending registration (after successful verification).
	 */
	public static function delete( string $token ): void {
		delete_option( self::OPTION_PREFIX . $token );
	}

	/**
	 * Purge all sf_reg_* options whose expiry timestamp has passed.
	 * Hooked to a daily WP-Cron event registered below.
	 */
	public static function purge_expired(): void {
		global $wpdb;
		$rows = $wpdb->get_results(
			"SELECT option_name, option_value FROM {$wpdb->options}
			 WHERE option_name LIKE 'sf\_reg\_%'",
			ARRAY_A
		);
		foreach ( $rows as $row ) {
			$data = maybe_unserialize( $row['option_value'] );
			if ( ! is_array( $data ) || ( isset( $data['expires'] ) && time() > $data['expires'] ) ) {
				delete_option( $row['option_name'] );
			}
		}
	}

	/**
	 * Send the confirmation email to the registrant.
	 */
	public static function send_confirmation( string $email, string $token, string $type ): bool {
		// Use the thank-you page as the base URL so the query string is on a
		// dedicated page — this bypasses WP Super Cache's mod_rewrite rules which
		// only serve cached files for requests with an empty query string.
		$verify_url = add_query_arg( 'salefish_verify', $token, home_url( '/thank-you-for-registering/' ) );

		$subject = 'Confirm your SaleFish registration';

		$logo = defined( 'SALEFISH_EMAIL_LOGO' ) ? SALEFISH_EMAIL_LOGO : '';

		$body  = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>';
		$body .= '<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;background-color:#0f0f0f;">';
		$body .= '<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f0f0f;padding:40px 20px;">';
		$body .= '<tr><td align="center">';
		$body .= '<table width="600" cellpadding="0" cellspacing="0" style="background-color:#1a1a1a;border-radius:8px;overflow:hidden;max-width:600px;">';

		if ( $logo ) {
			$body .= '<tr><td style="padding:40px 40px 30px;text-align:center;">';
			$body .= '<img src="' . esc_attr( $logo ) . '" alt="SaleFish" style="height:40px;width:auto;">';
			$body .= '</td></tr>';
		}

		$body .= '<tr><td style="padding:0 40px 40px;">';
		$body .= '<h1 style="color:#ffffff;font-size:24px;font-weight:600;margin:0 0 16px;">One more step, you\'re almost in.</h1>';
		$body .= '<p style="color:#a1a1a1;font-size:16px;line-height:1.6;margin:0 0 8px;">Thanks for registering with SaleFish.</p>';
		$body .= '<p style="color:#a1a1a1;font-size:16px;line-height:1.6;margin:0 0 30px;">Click the button below to confirm your email address and complete your registration.</p>';
		$body .= '<table cellpadding="0" cellspacing="0" style="margin:0 0 30px;">';
		$body .= '<tr><td style="background-color:#7c3aed;border-radius:6px;">';
		$body .= '<a href="' . esc_url( $verify_url ) . '" style="display:inline-block;padding:14px 28px;color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;letter-spacing:0.3px;">Confirm My Registration</a>';
		$body .= '</td></tr></table>';
		$body .= '<p style="color:#555555;font-size:13px;line-height:1.6;margin:0;">This link expires in 48 hours. If you didn\'t register at salefish.app, you can safely ignore this email.</p>';
		$body .= '</td></tr>';

		$body .= '<tr><td style="padding:30px 40px;border-top:1px solid #2a2a2a;text-align:center;">';
		$body .= '<p style="color:#666666;font-size:14px;margin:0;font-weight:600;">Real Estate Inventory &amp; Sales Powered by SaleFish</p>';
		$body .= '</td></tr>';

		$body .= '</table></td></tr></table></body></html>';

		add_filter( 'wp_mail_content_type', fn() => 'text/html' );
		$sent = wp_mail( $email, $subject, $body, [ 'From: SaleFish <hello@salefish.app>' ] );
		remove_all_filters( 'wp_mail_content_type' );

		return $sent;
	}

	/**
	 * Verify a Cloudflare Turnstile token.
	 * Returns true if valid, or if SALEFISH_CF_TURNSTILE_SECRET is not configured.
	 */
	public static function verify_turnstile( string $token ): bool {
		if ( ! defined( 'SALEFISH_CF_TURNSTILE_SECRET' ) || ! SALEFISH_CF_TURNSTILE_SECRET ) {
			return true;
		}

		$response = wp_remote_post( 'https://challenges.cloudflare.com/turnstile/v0/siteverify', [
			'body' => [
				'secret'   => SALEFISH_CF_TURNSTILE_SECRET,
				'response' => $token,
				'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
			],
			'timeout' => 10,
		] );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		return ! empty( $body['success'] );
	}

	/**
	 * Hook: process email verification link clicks.
	 * Attached to template_redirect in salefish.php.
	 */
	public static function handle_verification(): void {
		if ( empty( $_GET['salefish_verify'] ) ) {
			return;
		}

		$token   = sanitize_text_field( wp_unslash( $_GET['salefish_verify'] ) );
		$pending = self::get( $token );

		if ( ! $pending ) {
			error_log( '[SaleFish] Verification failed — token not found: ' . substr( $token, 0, 8 ) . '...' );
			wp_die(
				'<p style="font-family:sans-serif;font-size:16px;color:#333;">This verification link has expired or is invalid. Please <a href="' . esc_url( home_url() ) . '">register again</a>.</p>',
				'Link Expired',
				[ 'response' => 410 ]
			);
		}

		// Complete registration BEFORE deleting the token so a failure lets the user retry.
		try {
			salefish_complete_registration( $pending['type'], $pending['fields'] );
			self::delete( $token );
		} catch ( \Throwable $e ) {
			error_log( '[SaleFish] Registration completion error: ' . $e->getMessage() );
			wp_die(
				'<p style="font-family:sans-serif;font-size:16px;color:#333;">Something went wrong completing your registration. Please <a href="' . esc_url( home_url() ) . '">try clicking the link again</a> or contact us at <a href="mailto:hello@salefish.app">hello@salefish.app</a>.</p>',
				'Registration Error',
				[ 'response' => 500 ]
			);
		}

		wp_redirect( home_url( '/thank-you-for-registering/' ) );
		exit;
	}
}
