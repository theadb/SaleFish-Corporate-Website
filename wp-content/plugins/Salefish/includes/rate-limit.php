<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function salefish_rate_limit_identity(): string {
	// Use REMOTE_ADDR only. The site does not have Cloudflare proxy active
	// (grey cloud), so HTTP_CF_CONNECTING_IP is never set by Cloudflare.
	// Trusting that header without Cloudflare in front would allow any client
	// to spoof their IP and bypass rate limits entirely.
	$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
	return hash( 'sha256', $ip . '|' . wp_salt( 'nonce' ) );
}

function salefish_rate_limit_key( string $bucket ): string {
	return 'sf_rl_' . md5( $bucket . '|' . salefish_rate_limit_identity() );
}

function salefish_rate_limit_exceeded( string $bucket, int $limit, int $window ): bool {
	// Use wp_options (non-autoload) rather than transients. LiteSpeed Cache's
	// object cache can cause transients to not persist between requests on this
	// server — the same reason the email-verify system uses wp_options directly.
	$key  = salefish_rate_limit_key( $bucket );
	$now  = time();
	$row  = get_option( $key );

	if ( ! $row || ! is_array( $row ) || (int) $row['exp'] <= $now ) {
		update_option( $key, [ 'n' => 1, 'exp' => $now + $window ], false );
		return false;
	}

	if ( (int) $row['n'] >= $limit ) {
		return true;
	}

	$row['n']++;
	update_option( $key, $row, false );
	return false;
}

function salefish_enforce_rate_limit( string $bucket, int $limit = 5, int $window = 600 ): void {
	if ( salefish_rate_limit_exceeded( $bucket, $limit, $window ) ) {
		wp_send_json_error( [ 'message' => 'Too many attempts. Please try again in a few minutes.' ], 429 );
	}
}

function salefish_rate_limit_purge_expired(): void {
	global $wpdb;
	$rows = $wpdb->get_results(
		"SELECT option_name, option_value FROM {$wpdb->options}
		 WHERE option_name LIKE 'sf\_rl\_%'",
		ARRAY_A
	);
	$now = time();
	foreach ( $rows as $row ) {
		$data = maybe_unserialize( $row['option_value'] );
		if ( ! is_array( $data ) || ! isset( $data['exp'] ) || (int) $data['exp'] <= $now ) {
			delete_option( $row['option_name'] );
		}
	}
}
