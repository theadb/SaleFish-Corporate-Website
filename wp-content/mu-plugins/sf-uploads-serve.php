<?php
/**
 * Plugin Name: SF Uploads Direct Serve
 * Description: Serves wp-content/uploads files directly before WordPress's
 *              redirect_canonical() fires. On LiteSpeed shared hosting the
 *              REQUEST_FILENAME !-f rewrite condition is broken, so any file
 *              that isn't registered in the WP media library gets 301-redirected
 *              to the homepage. This hook intercepts at init priority 1 —
 *              before wp-includes sets up query vars — and outputs the file
 *              directly when it physically exists on disk.
 *
 *              Only AVIF/WebP files are handled here; JPG/PNG/GIF are
 *              registered in the media library and route normally.
 */

add_action( 'init', function () {

    $uri  = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
    $path = parse_url( $uri, PHP_URL_PATH );

    // Only intercept wp-content/uploads/ paths ending in image/font extensions
    // that WordPress doesn't register (primarily .avif, .webp variants).
    if ( ! preg_match( '#^/wp-content/uploads/.+\.(avif|webp)$#i', $path ) ) {
        return;
    }

    $abs = ABSPATH . ltrim( $path, '/' );

    if ( ! file_exists( $abs ) || ! is_file( $abs ) ) {
        return; // Let WordPress handle the 404 normally.
    }

    $ext  = strtolower( pathinfo( $abs, PATHINFO_EXTENSION ) );
    $mime = ( $ext === 'avif' ) ? 'image/avif' : 'image/webp';

    http_response_code( 200 );
    header( 'Content-Type: ' . $mime );
    header( 'Content-Length: ' . filesize( $abs ) );
    header( 'Cache-Control: public, max-age=31536000, immutable' );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'Vary: Accept' );
    readfile( $abs );
    exit;

}, 1 );
