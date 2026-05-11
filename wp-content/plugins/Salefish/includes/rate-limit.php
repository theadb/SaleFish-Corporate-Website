<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function salefish_rate_limit_identity(): string {
	$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

	if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) && filter_var( $_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP ) ) {
		$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
	}

	return hash( 'sha256', $ip . '|' . wp_salt( 'nonce' ) );
}

function salefish_rate_limit_key( string $bucket ): string {
	return 'sf_rate_' . md5( $bucket . '|' . salefish_rate_limit_identity() );
}

function salefish_rate_limit_exceeded( string $bucket, int $limit, int $window ): bool {
	$key   = salefish_rate_limit_key( $bucket );
	$count = (int) get_transient( $key );

	if ( $count >= $limit ) {
		return true;
	}

	set_transient( $key, $count + 1, $window );
	return false;
}

function salefish_enforce_rate_limit( string $bucket, int $limit = 5, int $window = 600 ): void {
	if ( salefish_rate_limit_exceeded( $bucket, $limit, $window ) ) {
		wp_send_json_error( [ 'message' => 'Too many attempts. Please try again in a few minutes.' ], 429 );
	}
}
