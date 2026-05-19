<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Thin wrapper for the Plinthra CRM registration API.
 *
 * Requires in wp-config.php:
 *   define( 'SALEFISH_PLINTHRA_API_KEY', 'sfk_...' );
 */
class Salefish_Plinthra {

	const ENDPOINT = 'https://plinthra.salefish.app/api/public/register/e2f38730-9d60-4816-af6a-6592caa401ef';

	/**
	 * Push a verified lead to the Plinthra CRM.
	 * Non-fatal — logs on failure but never throws.
	 */
	public static function register( array $data ): bool {
		$api_key = defined( 'SALEFISH_PLINTHRA_API_KEY' ) ? SALEFISH_PLINTHRA_API_KEY : '';

		if ( ! $api_key ) {
			error_log( '[SaleFish Plinthra] SALEFISH_PLINTHRA_API_KEY not set in wp-config.php.' );
			return false;
		}

		$response = wp_remote_post( self::ENDPOINT, [
			'headers' => [
				'Content-Type' => 'application/json',
				'X-API-Key'    => $api_key,
			],
			'body'    => wp_json_encode( $data ),
			'timeout' => 15,
		] );

		if ( is_wp_error( $response ) ) {
			error_log( '[SaleFish Plinthra] Request error: ' . $response->get_error_message() );
			return false;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		if ( $code >= 400 ) {
			error_log( sprintf( '[SaleFish Plinthra] HTTP %d — %s', $code, $body ) );
			return false;
		}

		error_log( '[SaleFish Plinthra] Lead registered OK — ' . ( $data['email'] ?? '' ) );
		return true;
	}

	/**
	 * Map a referrer URL path to the regtype dropdown value configured in Plinthra.
	 * Falls back to 'Homepage' for the root or any unrecognised path.
	 */
	public static function page_from_url( string $url ): string {
		$path = strtolower( trim( parse_url( $url, PHP_URL_PATH ) ?? '', '/' ) );

		$map = [
			'our-story'  => 'Our Story',
			'awards'     => 'Awards',
			'partners'   => 'Partners',
			'blog'       => 'Blog',
			'contact-us' => 'Contact Us',
		];

		foreach ( $map as $slug => $label ) {
			if ( str_contains( $path, $slug ) ) {
				return $label;
			}
		}

		return 'Homepage';
	}
}
