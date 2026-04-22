<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Disable comments completely and permanently across the entire site.
 *
 * - Removes comment/trackback support from all post types
 * - Filters close all comment and ping forms
 * - Hides existing comment counts and the Comments admin menu
 * - Redirects any direct attempt to access edit-comments.php
 * - One-time DB update: sets comment_status = 'closed' on all published posts
 */

// ── Remove comment support from all post types ────────────────────────────────
add_action( 'init', function() {
	foreach ( get_post_types() as $post_type ) {
		if ( post_type_supports( $post_type, 'comments' ) ) {
			remove_post_type_support( $post_type, 'comments' );
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}
}, 100 );

// ── Close all comment and ping forms on every request ─────────────────────────
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open',    '__return_false', 20, 2 );

// ── Return empty comment threads (suppresses any legacy comments) ─────────────
add_filter( 'comments_array', '__return_empty_array', 10, 2 );

// ── Remove comment-related items from WP admin ────────────────────────────────
add_action( 'admin_menu', function() {
	remove_menu_page( 'edit-comments.php' );
} );

add_action( 'wp_before_admin_bar_render', function() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'comments' );
} );

// ── Redirect any direct visit to the Comments admin page ─────────────────────
add_action( 'admin_init', function() {
	global $pagenow;
	if ( $pagenow === 'edit-comments.php' ) {
		wp_safe_redirect( admin_url() );
		exit;
	}
} );

// ── One-time: close comments on all existing published posts ──────────────────
add_action( 'init', function() {
	if ( get_option( 'salefish_comments_disabled_v1' ) ) return;
	global $wpdb;
	$wpdb->query(
		"UPDATE {$wpdb->posts}
		 SET comment_status = 'closed', ping_status = 'closed'
		 WHERE post_status IN ('publish','private','draft','pending','future')"
	);
	update_option( 'salefish_comments_disabled_v1', 1 );
}, 5 );
