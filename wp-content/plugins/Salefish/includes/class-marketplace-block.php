<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Block all marketplace-related pages and posts.
 * Redirects to homepage with 301 so they are effectively inaccessible.
 */
function salefish_block_marketplace(): void {
	// Block the marketplace CPT single pages
	if ( is_singular( 'marketplace' ) ) {
		wp_redirect( home_url( '/' ), 301 );
		exit;
	}

	// Block the marketplace CPT archive
	if ( is_post_type_archive( 'marketplace' ) ) {
		wp_redirect( home_url( '/' ), 301 );
		exit;
	}

	// Block any page/post whose slug contains "marketplace"
	$queried = get_queried_object();
	if ( $queried instanceof WP_Post && str_contains( $queried->post_name, 'marketplace' ) ) {
		wp_redirect( home_url( '/' ), 301 );
		exit;
	}

	// Block pages assigned the marketplace page templates
	if ( is_page() ) {
		$template = get_page_template_slug();
		if ( str_contains( (string) $template, 'marketplace' ) ) {
			wp_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'salefish_block_marketplace' );

/**
 * Exclude marketplace posts from all public queries (search, archives, feeds).
 */
function salefish_exclude_marketplace_from_queries( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	$post_types = (array) $query->get( 'post_type' );
	if ( ! empty( $post_types ) && in_array( 'marketplace', $post_types, true ) ) {
		$query->set( 'post__in', [ 0 ] ); // return nothing
	}
}
add_action( 'pre_get_posts', 'salefish_exclude_marketplace_from_queries' );
