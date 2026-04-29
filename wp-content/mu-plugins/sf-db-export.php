<?php
/**
 * Plugin Name: SF Database Export
 * Description: Secure on-demand database export, served directly from the init hook
 *              to avoid WP REST API content-negotiation (406) issues on LiteSpeed.
 *              Endpoint: /sf-db-export?key=<KEY>
 *              Returns a gzip-compressed SQL dump as a file download.
 *
 * Note: This server strips QUERY_STRING before PHP runs, so all query
 * parameters are parsed from $_SERVER['REQUEST_URI'] directly.
 */

define( 'SF_DB_EXPORT_KEY', '7cad673ab0190edb4ad37d8e7f531b0d6bd6ebf2e73d615807a754ba1e19555c' );

/**
 * Parse a query parameter from REQUEST_URI (server strips QUERY_STRING).
 */
function sf_get_param( string $name ): string {
    $uri    = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
    $qs_pos = strpos( $uri, '?' );
    if ( $qs_pos === false ) return '';
    $qs = substr( $uri, $qs_pos + 1 );
    parse_str( $qs, $params );
    return isset( $params[ $name ] ) ? (string) $params[ $name ] : '';
}

add_action( 'init', function () {

    $uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';

    // Only handle requests to /sf-db-export
    if ( strpos( $uri, '/sf-db-export' ) === false ) return;

    $key = sf_get_param( 'key' );
    if ( ! hash_equals( SF_DB_EXPORT_KEY, $key ) ) {
        http_response_code( 403 );
        header( 'Content-Type: text/plain' );
        exit( 'Forbidden' );
    }

    global $wpdb;

    // ── Build SQL dump ────────────────────────────────────────────────────────
    $tables = $wpdb->get_col( 'SHOW TABLES' );
    $sql    = "-- SaleFish DB export — " . gmdate( 'Y-m-d H:i:s' ) . " UTC\n";
    $sql   .= "-- Tables: " . count( $tables ) . "\n\n";
    $sql   .= "SET FOREIGN_KEY_CHECKS=0;\n";
    $sql   .= "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n";
    $sql   .= "SET NAMES utf8mb4;\n\n";

    foreach ( $tables as $table ) {

        $create = $wpdb->get_row( "SHOW CREATE TABLE `$table`", ARRAY_N );
        $sql   .= "DROP TABLE IF EXISTS `$table`;\n";
        $sql   .= $create[1] . ";\n\n";

        $offset = 0;
        while ( true ) {
            $rows = $wpdb->get_results(
                $wpdb->prepare( "SELECT * FROM `$table` LIMIT %d OFFSET %d", 500, $offset ),
                ARRAY_A
            );
            if ( empty( $rows ) ) break;

            $cols   = '`' . implode( '`, `', array_keys( $rows[0] ) ) . '`';
            $values = [];
            foreach ( $rows as $row ) {
                $escaped = array_map( function ( $v ) use ( $wpdb ) {
                    return is_null( $v ) ? 'NULL' : "'" . $wpdb->_real_escape( $v ) . "'";
                }, array_values( $row ) );
                $values[] = '(' . implode( ', ', $escaped ) . ')';
            }
            $sql .= "INSERT INTO `$table` ($cols) VALUES\n"
                  . implode( ",\n", $values ) . ";\n";

            $offset += 500;
            if ( count( $rows ) < 500 ) break;
        }
        $sql .= "\n";
    }

    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

    $gz = gzencode( $sql, 6 );

    while ( ob_get_level() ) ob_end_clean();
    http_response_code( 200 );
    header( 'Content-Type: application/octet-stream' );
    header( 'Content-Disposition: attachment; filename="salefish-db-' . gmdate( 'Y-m-d' ) . '.sql.gz"' );
    header( 'Content-Length: ' . strlen( $gz ) );
    header( 'Cache-Control: no-store, no-cache' );
    header( 'X-Robots-Tag: noindex' );
    echo $gz;
    exit;

}, 1 );
