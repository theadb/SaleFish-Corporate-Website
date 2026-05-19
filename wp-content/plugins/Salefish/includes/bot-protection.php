<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Minimum elapsed seconds between form becoming visible and submit.
define( 'SF_MIN_SUBMIT_SECONDS', 3 );

/**
 * True if the email domain has at least one MX record.
 * Fails open on DNS timeout/error so real users are never blocked by a slow DNS.
 */
function salefish_check_mx( string $email ): bool {
	$domain = substr( strrchr( $email, '@' ), 1 );
	if ( ! $domain ) return false;
	try {
		$hosts = [];
		if ( @getmxrr( $domain, $hosts ) ) return true;
		return (bool) @checkdnsrr( $domain, 'MX' );
	} catch ( \Throwable $e ) {
		return true;
	}
}

/**
 * True if the email domain is NOT on the known disposable/throwaway list.
 */
function salefish_check_disposable_email( string $email ): bool {
	static $domains = [
		'mailinator.com', 'guerrillamail.com', 'guerrillamail.net', 'guerrillamail.org',
		'guerrillamail.biz', 'guerrillamail.de', 'guerrillamail.info',
		'throwam.com', 'throwaway.email',
		'yopmail.com', 'yopmail.fr', 'cool.fr.nf', 'jetable.fr.nf', 'nospam.ze.tc',
		'nomail.xl.cx', 'mega.zik.dj', 'speed.1s.fr',
		'trashmail.com', 'trashmail.at', 'trashmail.io', 'trashmail.me',
		'trashmail.net', 'trashmail.org', 'trashmail.xyz',
		'temp-mail.org', 'tempmail.com', 'tempmail.net', 'tempmail.de',
		'tempr.email', 'tempsky.com', 'tempinbox.com', 'tempinbox.co.uk',
		'dispostable.com', 'disposablemail.com', 'disposableinbox.com',
		'maildrop.cc', 'mailnull.com', 'mailnesia.com',
		'spamgourmet.com', 'spamgourmet.net', 'spamgourmet.org',
		'spam4.me', 'binkmail.com', 'bob.email', 'getonemail.com',
		'sharklasers.com', 'guerrillamailblock.com', 'grr.la', 'junk.to',
		'filzmail.com', 'filzmail.de',
		'fakeinbox.com', 'fakeinbox.net',
		'mailexpire.com', 'meltmail.com', 'shieldemail.com',
		'10minutemail.com', '10minutemail.net', '10minutemail.org', '10minutemail.co.za',
		'20minutemail.com', '33mail.com',
		'emailondeck.com', 'discard.email', 'discardmail.com', 'discardmail.de',
		'einrot.com', 'fleckens.hu', 'lacedmail.com',
		'mt2014.com', 'mt2015.com', 'notsharingmy.info',
		'put2.net', 'rcpt.at', 'sofimail.com',
		'spamthisplease.com', 'stuffmail.de', 'supergreatmail.com',
		'spamhere.net', 'spamhere.org', 'spamhereplease.com',
		'trbvn.com', 'txcct.com', 'uroid.com',
		'veryrealemail.com', 'webemail.me',
		'weg-werf-email.de', 'wegwerfadresse.de', 'wegwerfemail.de',
		'wegwerfmail.de', 'wegwerfmail.net', 'wegwerfmail.org',
		'whyspam.me', 'willselfdestruct.com', 'wuzupmail.net',
		'xemaps.com', 'xents.com', 'xmaily.com', 'xoxy.net',
		'yep.it', 'yuurok.com', 'zehnminutenmail.de',
		'zippymail.info', 'zoemail.net', 'zoemail.org',
		'cuvox.de', 'dayrep.com', 'einrot.de',
		'gustr.com', 'jourrapide.com', 'rhyta.com', 'superrito.com', 'teleworm.us',
	];
	$domain = strtolower( substr( strrchr( $email, '@' ), 1 ) );
	return ! in_array( $domain, $domains, true );
}

/**
 * True if at least SF_MIN_SUBMIT_SECONDS have passed since $ts_ms (JS Date.now()).
 * Returns true (allow) when $ts_ms is absent — JS disabled or form not yet stamped.
 */
function salefish_check_submit_time( string $ts_ms ): bool {
	if ( $ts_ms === '' ) return true;
	$ts_s = (int) round( (float) $ts_ms / 1000 );
	if ( $ts_s <= 0 ) return true;
	return ( time() - $ts_s ) >= SF_MIN_SUBMIT_SECONDS;
}

/**
 * Runs all three bot-protection checks and calls wp_send_json_error() on failure.
 * Call this after email format validation but before creating the verification token.
 */
function salefish_validate_bot_protection( string $email, string $ts_ms ): void {
	if ( ! salefish_check_submit_time( $ts_ms ) ) {
		wp_send_json_error( 'Submission too fast. Please try again.' );
	}
	if ( ! salefish_check_disposable_email( $email ) ) {
		wp_send_json_error( 'Please use a work or personal email address.' );
	}
	if ( ! salefish_check_mx( $email ) ) {
		wp_send_json_error( 'That email address appears to be invalid. Please check and try again.' );
	}
}
