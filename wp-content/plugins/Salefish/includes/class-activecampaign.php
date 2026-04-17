<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Thin wrapper around the ActiveCampaign v3 REST API.
 *
 * Credentials are read from wp-config.php constants:
 *   define( 'SALEFISH_AC_KEY', 'your-api-key' );
 *   define( 'SALEFISH_AC_URL', 'https://youracccount.api-us1.com' );
 */
class Salefish_ActiveCampaign {

	const LIST_ID = 4; // "Website Registrations"

	private string $api_key;
	private string $api_url;

	public function __construct() {
		$this->api_key = defined( 'SALEFISH_AC_KEY' ) ? SALEFISH_AC_KEY : '';
		$this->api_url = defined( 'SALEFISH_AC_URL' ) ? rtrim( SALEFISH_AC_URL, '/' ) . '/api/3' : '';
	}

	/**
	 * Create or update a contact. Returns the AC contact ID string, or false.
	 *
	 * @param array{email:string, first_name:string, last_name:string, phone:string} $data
	 */
	public function upsert_contact( array $data ): string|false {
		$payload = [
			'contact' => array_filter( [
				'email'     => $data['email']      ?? '',
				'firstName' => $data['first_name'] ?? '',
				'lastName'  => $data['last_name']  ?? '',
				'phone'     => $data['phone']       ?? '',
			] ),
		];

		$response = $this->request( 'POST', '/contact/sync', $payload );
		if ( is_wp_error( $response ) ) {
			error_log( 'SaleFish AC upsert_contact: ' . $response->get_error_message() );
			return false;
		}

		return $response['contact']['id'] ?? false;
	}

	/**
	 * Subscribe contact to LIST_ID.
	 */
	public function subscribe_to_list( string $contact_id ): bool {
		$payload = [
			'contactList' => [
				'list'    => self::LIST_ID,
				'contact' => $contact_id,
				'status'  => 1,
			],
		];

		$response = $this->request( 'POST', '/contactLists', $payload );
		if ( is_wp_error( $response ) ) {
			error_log( 'SaleFish AC subscribe_to_list: ' . $response->get_error_message() );
			return false;
		}
		return true;
	}

	/**
	 * Enrol a contact into an automation by ID.
	 * Returns false if the automation ID is 0 (not yet configured).
	 */
	public function add_to_automation( string $contact_id, int $automation_id ): bool {
		if ( $automation_id === 0 ) return false;

		$response = $this->request( 'POST', '/contactAutomations', [
			'contactAutomation' => [
				'contact'    => $contact_id,
				'automation' => $automation_id,
			],
		] );

		if ( is_wp_error( $response ) ) {
			error_log( 'SaleFish AC add_to_automation: ' . $response->get_error_message() );
			return false;
		}
		return true;
	}

	/**
	 * Add a named tag to a contact. Creates the tag in AC if it doesn't exist yet.
	 */
	public function add_tag( string $contact_id, string $tag_name ): bool {
		$tag_id = $this->get_or_create_tag( $tag_name );
		if ( ! $tag_id ) return false;

		$response = $this->request( 'POST', '/contactTags', [
			'contactTag' => [
				'contact' => $contact_id,
				'tag'     => $tag_id,
			],
		] );

		if ( is_wp_error( $response ) ) {
			error_log( 'SaleFish AC add_tag: ' . $response->get_error_message() );
			return false;
		}
		return true;
	}

	/**
	 * Set a custom field value on a contact.
	 *   Field 1 = SaleFish User Group
	 *   Field 2 = Company Name
	 */
	public function set_field( string $contact_id, int $field_id, string $value ): bool {
		$response = $this->request( 'POST', '/fieldValues', [
			'fieldValue' => [
				'contact' => $contact_id,
				'field'   => $field_id,
				'value'   => $value,
			],
		] );

		if ( is_wp_error( $response ) ) {
			error_log( 'SaleFish AC set_field: ' . $response->get_error_message() );
			return false;
		}
		return true;
	}

	// -------------------------------------------------------------------------
	// Private helpers
	// -------------------------------------------------------------------------

	private function get_or_create_tag( string $tag_name ): string|false {
		$search = $this->request( 'GET', '/tags?search=' . urlencode( $tag_name ) );
		if ( ! is_wp_error( $search ) && ! empty( $search['tags'] ) ) {
			foreach ( $search['tags'] as $t ) {
				if ( strtolower( $t['tag'] ) === strtolower( $tag_name ) ) {
					return $t['id'];
				}
			}
		}

		$create = $this->request( 'POST', '/tags', [
			'tag' => [ 'tag' => $tag_name, 'tagType' => 'contact' ],
		] );

		if ( is_wp_error( $create ) ) {
			error_log( 'SaleFish AC get_or_create_tag: ' . $create->get_error_message() );
			return false;
		}
		return $create['tag']['id'] ?? false;
	}

	private function request( string $method, string $endpoint, array $body = [] ): array|WP_Error {
		if ( empty( $this->api_key ) || empty( $this->api_url ) ) {
			return new WP_Error( 'ac_not_configured', 'ActiveCampaign credentials not set in wp-config.php.' );
		}

		$args = [
			'method'  => $method,
			'headers' => [
				'Api-Token'    => $this->api_key,
				'Content-Type' => 'application/json',
			],
			'timeout' => 15,
		];

		if ( $method !== 'GET' && ! empty( $body ) ) {
			$args['body'] = wp_json_encode( $body );
		}

		$response = wp_remote_request( $this->api_url . $endpoint, $args );

		if ( is_wp_error( $response ) ) return $response;

		$code       = wp_remote_retrieve_response_code( $response );
		$parsed     = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( $code >= 400 ) {
			$msg = $parsed['message'] ?? ( 'AC API HTTP ' . $code );
			return new WP_Error( 'ac_api_error', $msg );
		}

		return $parsed ?: [];
	}
}
