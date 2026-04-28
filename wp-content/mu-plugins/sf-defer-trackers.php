<?php
/*
Plugin Name: SaleFish Defer Trackers
Description: Captures inline tracking pixels emitted by other plugins
             (Tracking Code Manager → LinkedIn, etc.) and rewrites them
             to defer loading until the visitor's first click. Without
             this, the pixels fire at page load and cause net::ERR errors
             in synthetic-test environments (Lighthouse, ad-blocked
             browsers) that drag the Best-Practices score.
Version: 1.0.0
*/

if ( is_admin() ) return; // never alter admin pages

/**
 * Buffer the entire HTML body so we can rewrite tracker scripts before
 * they're sent to the browser. We hook at the very start of the front-end
 * response (`template_redirect`) and flush at `shutdown`.
 *
 * Inside the rewriter:
 *   1. Find the LinkedIn Insight tag that TCM emits — it contains
 *      `_linkedin_partner_id = "..."` and the `https://snap.licdn.com/...`
 *      script src.
 *   2. Wrap the script in a click-deferred loader so it never fires
 *      until the visitor explicitly interacts with the page.
 *
 * Logged-in users keep the original behaviour so admin previews still see
 * the trackers fire.
 */
add_action( 'template_redirect', function () {
    if ( is_user_logged_in() ) return;
    ob_start( 'sf_defer_trackers_filter' );
}, 0 );

function sf_defer_trackers_filter( $html ) {
    if ( strlen( $html ) < 500 || strpos( $html, '</body>' ) === false ) {
        return $html; // not a full HTML response — skip
    }

    // ── Defer LinkedIn Insight Tag emitted by TCM (or pasted directly) ──
    // The tag is recognisable by `_linkedin_partner_id` + the snap.licdn.com
    // script URL. We wrap any matching <script> blocks in a click-deferred
    // closure. Any number of partner IDs is supported.
    $html = preg_replace_callback(
        '#<script[^>]*>([^<]*?_linkedin_partner_id\s*=.*?snap\.licdn\.com[^<]*?)</script>#is',
        function ( $m ) {
            $original = $m[1];
            $deferred = "(function(){var f=function(){\n" . $original . "\n};document.addEventListener('click',f,{once:true,passive:true});}());";
            return '<script>' . $deferred . '</script>';
        },
        $html
    );

    // ── Strip Tidio plugin's eager preconnect ─────────────────────────────
    // Tidio Live Chat plugin's WidgetLoader::addPreconnectLink() fires on
    // wp_head and emits a preconnect to code.tidio.co even though we now
    // load the widget click-only. Lighthouse flags wasted preloads.
    $html = preg_replace(
        '#<link[^>]*rel=["\']preconnect["\'][^>]*tidio\.co[^>]*/?>#i',
        '',
        $html
    );

    // ── Strip Tidio plugin's footer auto-loader ────────────────────────────
    // The plugin emits a <script> that auto-loads Tidio at window.load.
    // We already have a click-only loader in header.php; the duplicate
    // forces Tidio to load on every page-view despite our defer.
    $html = preg_replace(
        '#<script[^>]*>\s*document\.tidioChatCode\s*=.*?</script>#is',
        '',
        $html
    );

    return $html;
}
