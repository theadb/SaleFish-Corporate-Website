<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Salefish_Email_Verify {

	const TRANSIENT_PREFIX = 'salefish_pending_';
	const TTL              = 172800; // 48 hours

	/**
	 * Store a pending registration and return the verification token.
	 */
	public static function create( string $type, array $fields ): string {
		$token = bin2hex( random_bytes( 32 ) );
		set_transient(
			self::TRANSIENT_PREFIX . $token,
			[ 'type' => $type, 'fields' => $fields ],
			self::TTL
		);
		return $token;
	}

	/**
	 * Retrieve a pending registration by token. Returns false if expired/invalid.
	 */
	public static function get( string $token ) {
		return get_transient( self::TRANSIENT_PREFIX . $token );
	}

	/**
	 * Delete a pending registration (after successful verification).
	 */
	public static function delete( string $token ): void {
		delete_transient( self::TRANSIENT_PREFIX . $token );
	}

	/**
	 * Send the confirmation email to the registrant.
	 */
	public static function send_confirmation( string $email, string $token, string $type ): bool {
		$verify_url = add_query_arg( 'salefish_verify', $token, home_url( '/' ) );

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
			wp_die(
				'<p style="font-family:sans-serif;font-size:16px;color:#333;">This verification link has expired or is invalid. Please <a href="' . esc_url( home_url() ) . '">register again</a>.</p>',
				'Link Expired',
				[ 'response' => 410 ]
			);
		}

		self::delete( $token );
		salefish_complete_registration( $pending['type'], $pending['fields'] );

		wp_redirect( add_query_arg( 'salefish_verified', '1', home_url( '/' ) ) );
		exit;
	}
}
