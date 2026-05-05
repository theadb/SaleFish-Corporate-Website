<?php
/**
 * Load local secrets (API keys etc.) — file is gitignored, never committed.
 */
$_sf_local_config = get_template_directory() . '/config.local.php';
if ( file_exists( $_sf_local_config ) ) {
    require_once $_sf_local_config;
}
unset( $_sf_local_config );

/**
 * Feature Capital functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _pc
 */


if (! function_exists('_pc_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function _pc_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Feature Capital, use a find and replace
         * to change '_pc' to the name of your theme in all the template files.
         */
        load_theme_textdomain('_pc', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
          'primary' => esc_html__('Primary', '_pc'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
          'search-form',
          'comment-form',
          'comment-list',
          'gallery',
          'caption',
        ));

        /*
         * Enable support for Post Formats.
         * See https://developer.wordpress.org/themes/functionality/post-formats/
         */
        add_theme_support('post-formats', array(
          'aside',
          'image',
          'video',
          'quote',
          'link',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('_pc_custom_background_args', array(
          'default-color' => 'ffffff',
          'default-image' => '',
        )));
    }
endif;
add_action('after_setup_theme', '_pc_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function _pc_content_width()
{
    $GLOBALS['content_width'] = apply_filters('_pc_content_width', 640);
}


add_filter('show_admin_bar', '__return_false');


add_action('after_setup_theme', '_pc_content_width', 0);
add_image_size("hd", 1920, 1080, true);
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function _pc_widgets_init()
{
    register_sidebar(array(
      'name'          => esc_html__('Sidebar', '_pc'),
      'id'            => 'sidebar-1',
      'description'   => esc_html__('Add widgets here.', '_pc'),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', '_pc_widgets_init');


function kickass_scripts()
{
    // jQuery is bundled into app.js via webpack's ProvidePlugin (autoload in
    // webpack.mix.js). No separate CDN script needed — one less network request.
    wp_deregister_script( 'jquery' ); // prevent WP or plugins re-enqueueing the old CDN copy

    wp_enqueue_style('style-name', get_template_directory_uri() . '/dest/app.css', [], filemtime(get_template_directory() . '/dest/app.css'));
    wp_enqueue_script('script-name', get_template_directory_uri() . '/dest/app.js', [], filemtime(get_template_directory() . '/dest/app.js'), true);
    wp_localize_script('script-name', 'salefishAjax', [
        'ajaxurl'        => admin_url('admin-ajax.php'),
        'nonce'          => wp_create_nonce('salefish_nonce'),
        'loadMoreNonce'  => wp_create_nonce('salefish_load_more'),
        'turnstileSitekey' => defined('SALEFISH_CF_TURNSTILE_SITEKEY') ? SALEFISH_CF_TURNSTILE_SITEKEY : '',
    ]);
}
add_action('wp_enqueue_scripts', 'kickass_scripts');

/**
 * Add `defer` to our app.js script tag so the HTML parser never blocks on
 * it. WordPress puts in_footer scripts at end-of-body, which avoids parser
 * blocking on most modern browsers, but Safari's preloader is more
 * conservative and treats footer-positioned scripts as parser-stop points
 * anyway. `defer` makes the script execute after DOMContentLoaded, never
 * blocking parsing or first paint.
 */
add_filter( 'script_loader_tag', function ( $tag, $handle ) {
    if ( $handle === 'script-name' ) {
        // Inject defer if not already present
        if ( strpos( $tag, ' defer' ) === false && strpos( $tag, ' async' ) === false ) {
            $tag = str_replace( '<script ', '<script defer ', $tag );
        }
    }
    return $tag;
}, 10, 2 );

/**
 * NOTE: The Cache-Control: no-store header on this site is set at the
 * server (Apache/cPanel) level — a duplicate header is injected after PHP
 * flushes its response, and PHP-side filters (nocache_headers,
 * header_register_callback, header_remove) cannot override it. Fixing it
 * requires either:
 *   • cPanel access to disable the per-virtualhost mod_security/mod_headers
 *     rule that injects the no-store header, or
 *   • Migrating to a host where Cache-Control isn't forced.
 *
 * Once removed at the server level, the browser will be able to use its
 * bf-cache (instant back/forward) and cache the HTML for the duration we
 * specify in WP-Super-Cache (currently max-age=3, must-revalidate).
 */

// ── Security: HTTP response headers ──────────────────────────────────────────
// Prevent clickjacking, MIME-type sniffing, and tighten referrer disclosure.
// CSP is managed via Cloudflare Transform Rules so it can be tuned without a
// deploy. These headers have no caching interaction that Apache overrides.
add_action( 'send_headers', function () {
    if ( ! is_admin() ) {
        header( 'X-Frame-Options: SAMEORIGIN' );
        header( 'X-Content-Type-Options: nosniff' );
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()' );
    }
} );

// ── Performance: resource hints ───────────────────────────────────────────────
// Tidio + LinkedIn + GTM are all click-deferred now, so eager preconnect /
// dns-prefetch hints would just be wasted DNS chatter on first paint
// (and Lighthouse flags them as "preloaded but not used").
//
// Maps is only embedded on contact pages, so the preconnect is gated on the
// page template. Other pages get nothing.
add_action('wp_head', function() {
    if ( is_page_template( 'page-contact-us.php' ) || is_page_template( 'page-tr-contact.php' ) ) {
        echo '<link rel="preconnect" href="https://maps.googleapis.com" crossorigin>' . "\n";
        echo '<link rel="preconnect" href="https://maps.gstatic.com" crossorigin>' . "\n";
    }
}, 2);

// ── Performance: remove unused WordPress emoji scripts ─────────────────────────
remove_action('wp_head',         'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles',  'print_emoji_styles');
remove_filter('the_content_feed',    'wp_staticize_emoji');
remove_filter('comment_text_rss',    'wp_staticize_emoji');
remove_filter('wp_mail',             'wp_staticize_emoji_for_email');

// ── Performance: remove unused REST API / WP head junk ────────────────────────
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_generator');          // hides WP version from attackers
remove_action('wp_head', 'rsd_link');              // Windows Live Writer RSD — unused
remove_action('wp_head', 'wp_shortlink_wp_head');  // WP shortlink tag — unused

// ── Performance: remove Gutenberg block CSS from front-end ────────────────────
// This theme uses a fully custom template system — no block editor output on
// front-end pages. Removing block library styles saves ~8 KB of inline CSS on
// every page. Blog posts that may contain block markup retain any block-specific
// inline styles they emit directly; those are unaffected by this dequeue.
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');        // core block styles
    wp_dequeue_style('wp-block-library-theme');  // block theme overrides
    wp_dequeue_style('global-styles');           // WP global styles / CSS vars
}, 100);

// ─── SOCIAL / OG META TAGS ────────────────────────────────────────────────────
// Suppress Yoast's duplicate meta description and OG tags — we handle them
// below with richer, page-specific copy (runs at priority 1, before Yoast).
add_filter( 'wpseo_metadesc',  '__return_empty_string' );
add_filter( 'wpseo_og_og_data', function() { return []; } );

function salefish_og_meta() {
    $default_image = 'https://salefish.app/wp-content/themes/salefish/img/salefish_demo_home.png';
    $site_name     = 'SaleFish';
    $keywords      = '';   // per-page keywords populated below

    // ── Homepage ──────────────────────────────────────────────────────────────
    if ( is_front_page() || is_home() ) {
        $title       = 'SaleFish | Real Estate Sales Platform';
        $description = 'The all-in-one real estate sales platform trusted by builders, developers, and sales teams worldwide. Digital worksheets, e-signatures, identity verification, and CRM — all in one place.';
        $keywords    = 'real estate sales platform, pre-construction sales software, digital contracts real estate, CRM for builders, e-signatures real estate, real estate tech stack';
        $url         = home_url( '/' );
        $og_type     = 'website';
        $image       = $default_image;

    // ── Awards page ───────────────────────────────────────────────────────────
    } elseif ( is_page_template( 'page-awards.php' ) ) {
        $title       = 'Awards &amp; Recognition | SaleFish';
        $description = 'SaleFish has earned industry recognition for innovation, cybersecurity, and excellence in real estate sales software — the only platform with a CyberSecure Canada certification.';
        $keywords    = 'real estate software awards, SaleFish recognition, CyberSecure Canada certification, pre-construction sales platform awards, real estate technology';
        $url         = get_permalink();
        $og_type     = 'website';
        $image       = $default_image;

    // ── Partners page ─────────────────────────────────────────────────────────
    } elseif ( is_page_template( 'page-partners.php' ) ) {
        $title       = 'Partner with SaleFish | Real Estate Technology Partners';
        $description = 'Join the SaleFish ecosystem as a technology partner, referral partner, or reseller. Earn recurring revenue while helping builders and sales teams move more real estate, more easily.';
        $keywords    = 'real estate technology partner, SaleFish partner program, real estate software reseller, referral partner real estate, pre-construction sales partner';
        $url         = get_permalink();
        $og_type     = 'website';
        $image       = $default_image;

    // ── Our Story page ────────────────────────────────────────────────────────
    } elseif ( is_page_template( 'page-our-story.php' ) ) {
        $title       = 'Our Story | SaleFish Real Estate Sales Platform';
        $description = '15+ years, 1.5M+ users, 200K+ transactions, $100B+ USD transacted. Discover how SaleFish became the most trusted real estate sales platform for builders and developers worldwide.';
        $keywords    = 'SaleFish company, about SaleFish, real estate sales platform, pre-construction sales software, SaleFish history, real estate technology company';
        $url         = get_permalink();
        $og_type     = 'website';
        $image       = $default_image;

    // ── Contact Us page ───────────────────────────────────────────────────────
    } elseif ( is_page_template( 'page-contact-us.php' ) ) {
        $title       = 'Contact SaleFish | Book a Free Demo';
        $description = 'Book a free demo or contact our team for a no-obligation assessment of your real estate sales process and tech stack. We\'ll show you exactly how SaleFish fits your workflow.';
        $keywords    = 'contact SaleFish, book real estate demo, SaleFish free demo, real estate sales software demo, pre-construction sales platform contact';
        $url         = get_permalink();
        $og_type     = 'website';
        $image       = $default_image;

    // ── Blog / Newsroom pages ─────────────────────────────────────────────────
    } elseif ( is_page_template( 'page-blog.php' ) || is_page_template( 'page-blog-filter.php' ) ) {
        $title       = 'Blog | SaleFish Real Estate Sales Insights';
        $description = 'Success stories, press coverage, industry insights, and videos from the SaleFish team — the real estate sales platform built for builders, developers, and sales teams.';
        $keywords    = 'real estate sales blog, SaleFish blog, pre-construction sales insights, real estate technology news, builder developer resources';
        $url         = get_permalink();
        $og_type     = 'website';
        $image       = $default_image;

    // ── Privacy Policy page ───────────────────────────────────────────────────
    } elseif ( is_page_template( 'page-privacy-policy.php' ) ) {
        $title       = 'Privacy Policy | SaleFish';
        $description = 'Read SaleFish\'s privacy policy to understand how we collect, use, and protect your personal information on our real estate sales platform.';
        $keywords    = 'SaleFish privacy policy, real estate software privacy';
        $url         = get_permalink();
        $og_type     = 'website';
        $image       = $default_image;

    // ── Terms of Use page ─────────────────────────────────────────────────────
    } elseif ( is_page_template( 'page-terms-of-use.php' ) ) {
        $title       = 'Terms of Use | SaleFish';
        $description = 'Review the terms and conditions governing use of the SaleFish real estate sales platform and website.';
        $keywords    = 'SaleFish terms of use, real estate software terms and conditions';
        $url         = get_permalink();
        $og_type     = 'website';
        $image       = $default_image;

    // ── Thank-you / confirmation page (low SEO priority) ─────────────────────
    } elseif ( is_page_template( 'page-thank-you-for-registering.php' ) ) {
        $title       = 'Thank You for Registering | SaleFish';
        $description = 'Thank you for registering with SaleFish. Check your email to confirm your account and get started.';
        $keywords    = '';
        $url         = get_permalink();
        $og_type     = 'website';
        $image       = $default_image;

    // ── Individual blog posts ─────────────────────────────────────────────────
    } elseif ( is_singular( 'post' ) ) {
        $post_id     = get_the_ID();
        $title       = get_the_title( $post_id ) . ' | SaleFish';
        $url         = get_permalink( $post_id );
        $og_type     = 'article';

        $description = get_post_field( 'post_excerpt', $post_id );
        if ( ! $description ) {
            $description = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 30, '...' );
        }
        if ( ! $description ) {
            $description = 'Real estate sales insights, success stories, and platform updates from the SaleFish team.';
        }

        $image = has_post_thumbnail( $post_id )
            ? get_the_post_thumbnail_url( $post_id, 'large' )
            : $default_image;

    // ── Other singular pages (catch-all) ──────────────────────────────────────
    } elseif ( is_singular() ) {
        $post_id     = get_the_ID();
        $title       = get_the_title( $post_id ) . ' | SaleFish';
        $url         = get_permalink( $post_id );
        $og_type     = 'website';

        $description = get_post_field( 'post_excerpt', $post_id );
        if ( ! $description ) {
            $description = 'SaleFish — the all-in-one real estate sales platform for builders, developers, and sales teams worldwide.';
        }

        $image = has_post_thumbnail( $post_id )
            ? get_the_post_thumbnail_url( $post_id, 'large' )
            : $default_image;

    // ── Category / archive pages ──────────────────────────────────────────────
    } else {
        $title       = wp_title( '|', false, 'right' ) . 'SaleFish';
        $description = 'Real estate sales insights, success stories, and platform updates from the SaleFish team.';
        $keywords    = 'real estate sales, SaleFish blog, pre-construction sales';
        global $wp;
        $url         = home_url( add_query_arg( [], $wp->request ) );
        $og_type     = 'website';
        $image       = $default_image;
    }

    // Sanitise
    $title       = esc_attr( wp_strip_all_tags( $title ) );
    $description = esc_attr( wp_trim_words( wp_strip_all_tags( $description ), 35, '...' ) );
    $keywords    = esc_attr( wp_strip_all_tags( $keywords ) );
    $url         = esc_url( $url );
    $image       = esc_url( $image );

    ?>
<!-- SaleFish Social Meta -->
<meta property="og:type"        content="<?php echo $og_type; ?>" />
<meta property="og:title"       content="<?php echo $title; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />
<meta property="og:url"         content="<?php echo $url; ?>" />
<meta property="og:site_name"   content="<?php echo esc_attr( $site_name ); ?>" />
<meta property="og:image"       content="<?php echo $image; ?>" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta name="twitter:card"        content="summary_large_image" />
<meta name="twitter:title"       content="<?php echo $title; ?>" />
<meta name="twitter:description" content="<?php echo $description; ?>" />
<meta name="twitter:image"       content="<?php echo $image; ?>" />
<meta name="description"         content="<?php echo $description; ?>" />
<?php if ( $keywords ) : ?>
<meta name="keywords"            content="<?php echo $keywords; ?>" />
<?php endif; ?>
<!-- End SaleFish Social Meta -->
    <?php
}
add_action( 'wp_head', 'salefish_og_meta', 1 );

/**
 * Enqueue scripts and styles.
 */
// function _pc_scripts()
// {
//     // Enqueue styles
//     wp_enqueue_style('_pc_styles', get_template_directory_uri() . '/dest/app.css');

//     // Enqueue scripts
//     wp_enqueue_script('all', get_template_directory_uri() . '/dest/main.js', array('jquery'), null, true);
// }
// add_action('wp_enqueue_scripts', '_pc_scripts');


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


function sf_post_cta( $cat_slug ) {
    switch ( $cat_slug ) {
        case 'videos':          return 'Watch Video';
        case 'success-stories': return 'See the Results';
        case 'press':           return 'Read It';
        case 'blog':            return 'Dig In';
        default:                return 'Keep Reading';
    }
}

function limit_text($text, $limit)
{
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos   = array_keys($words);
        $text  = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}

function sf_read_time( $content ) {
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $minutes    = max( 1, (int) round( $word_count / 200 ) );
    return $minutes . ' min read';
}

/**
 * Emit a `<link rel="preload" as="image">` for the page's LCP hero image.
 *
 * Looks for an AVIF sibling next to the given PNG/JPG URL, plus any size
 * variant siblings (e.g. `-1280w.avif`) so the browser can pick the right
 * resolution via imagesrcset. Only emits when the AVIF actually exists
 * on disk — never preloads a 404.
 *
 * Centralizing this logic into a reusable function lets us preload the
 * hero on every major page (our-story, partners, awards, etc.) instead
 * of just the homepage. The browser starts downloading the LCP image
 * during the parse-CSS phase, eliminating the parser → body → <img>
 * discovery delay.
 *
 * @param string $url Public URL of the source PNG / JPG image.
 * @param string $sizes_attr Optional `imagesizes` attribute (default 100vw).
 */
function sf_preload_hero_image( $url, $sizes_attr = '100vw' ) {
    if ( empty( $url ) ) return;

    // Resolve URL → disk path so we can probe for AVIF + variants.
    $upload    = wp_get_upload_dir();
    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();
    $abs_path  = '';

    if ( strpos( $url, $upload['baseurl'] ) === 0 ) {
        $abs_path = $upload['basedir'] . substr( $url, strlen( $upload['baseurl'] ) );
    } elseif ( strpos( $url, $theme_uri ) === 0 ) {
        $abs_path = $theme_dir . substr( $url, strlen( $theme_uri ) );
    }

    if ( ! $abs_path || ! preg_match( '/\.(png|jpe?g)$/i', $abs_path ) ) return;

    $avif_path = preg_replace( '/\.(png|jpe?g)$/i', '.avif', $abs_path );
    if ( ! file_exists( $avif_path ) ) return;

    $avif_url  = preg_replace( '/\.(png|jpe?g)$/i', '.avif', $url );
    $base_path = preg_replace( '/\.(png|jpe?g)$/i', '', $abs_path );
    $base_url  = preg_replace( '/\.(png|jpe?g)$/i', '', $url );

    $variants = [];
    foreach ( [ 320, 480, 640, 800, 1024, 1280, 1920 ] as $w ) {
        if ( file_exists( $base_path . '-' . $w . 'w.avif' ) ) {
            $variants[] = esc_url( $base_url . '-' . $w . 'w.avif' ) . ' ' . $w . 'w';
        }
    }

    if ( $variants ) {
        $size   = @getimagesize( $avif_path );
        $full_w = ! empty( $size[0] ) ? $size[0] : 1920;
        $variants[] = esc_url( $avif_url ) . ' ' . $full_w . 'w';
        echo '<link rel="preload" as="image" type="image/avif" imagesrcset="' . implode( ', ', $variants ) . '" imagesizes="' . esc_attr( $sizes_attr ) . '" fetchpriority="high">' . "\n";
    } else {
        echo '<link rel="preload" as="image" type="image/avif" href="' . esc_url( $avif_url ) . '" fetchpriority="high">' . "\n";
    }
}


add_filter('use_block_editor_for_post', '__return_false');


function load_more_post()
{
    // No nonce check on this endpoint — and that's intentional.
    //
    // load_more_post returns the EXACT same blog posts anyone can see by
    // scrolling /blog/. It's a read-only public endpoint with no state
    // mutation, no privilege escalation, and no sensitive data exposure.
    // CSRF protection is meaningless here.
    //
    // Worse, requiring a nonce was actively breaking the Load More button:
    // WordPress nonces expire in 12–24 h. LSCache (and Cloudflare/browser
    // caches) serve cached HTML pages for up to a week. Visitors hitting a
    // cached page > 12 h after it was generated would have a stale nonce
    // embedded in the page → AJAX fires with expired nonce → check_ajax_referer
    // returns false → wp_die(-1) → JS .json() throws → button silently fails.
    //
    // Bypassing the nonce makes the endpoint cache-compatible forever.
    $paged    = isset($_POST['paged'])    ? max( 1, intval($_POST['paged']) )       : 1;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';

    $args = [
        'post_status'    => 'publish',
        'post_type'      => 'post',
        'posts_per_page' => 9,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ( $category && $category !== 'all' ) {
        $args['category_name'] = $category;
    }

    $query = new WP_Query( $args );

    $response = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $id       = get_the_ID();
            $cats     = get_the_category( $id );
            $cat_slug    = $cats ? $cats[0]->category_nicename : '';
            $cat_name    = $cats ? $cats[0]->cat_name          : '';
            $is_video    = sf_cats_include_video( $cats );
            $raw_content = get_the_content();

            // Thumbnail: use featured image; fall back to auto-extracted video thumb
            $thumb = get_the_post_thumbnail( $id );
            if ( empty( $thumb ) && $is_video ) {
                $vthumb_url = sf_video_thumbnail_url( $raw_content );
                if ( $vthumb_url ) {
                    $thumb = '<img src="' . esc_url( $vthumb_url ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy">';
                }
            }

            $link        = get_permalink( $id );
            $title       = limit_text( get_the_title(), 14 );
            $date        = get_the_date( 'M j, Y', $id );
            $author      = get_the_author_meta( 'display_name', get_post_field( 'post_author', $id ) );
            $is_featured = has_tag( 'featured', $id );
            $embed_url   = $is_video ? sf_video_embed_url( $raw_content ) : '';

            $response[] = [
                'id'         => $id,
                'category'   => $cats,
                'cat_slug'   => $cat_slug,
                'cat_name'   => $cat_name,
                'is_video'   => $is_video,
                'thumb'      => $thumb,
                'link'       => $link,
                'content'    => $raw_content,
                'embed_url'  => $embed_url,
                'title'      => $title,
                'date'       => $date,
                'author'     => $author,
                'is_featured'=> $is_featured,
                'read_time'  => sf_read_time( $raw_content ),
            ];
        }
        wp_reset_postdata();
    }

    echo json_encode([
        'max'   => $query->max_num_pages,
        'posts' => $response,
    ]);
    wp_die();
}
add_action('wp_ajax_load_more_post', 'load_more_post');
add_action('wp_ajax_nopriv_load_more_post', 'load_more_post');
// ── WP Admin: replace oEmbed preview for video posts ──────────────────────────
// WordPress auto-embeds YouTube URLs in the classic editor. If YouTube has
// blocked embedding (Content ID claim, Error 153, etc.) the editor shows a
// broken player. Replace it with a clean link notice instead.
add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) {
    if ( ! is_admin() ) return $html;
    $cats = get_the_category( $post_id );
    $is_video_post = false;
    foreach ( $cats as $cat ) {
        if ( $cat->slug === 'videos' ) { $is_video_post = true; break; }
    }
    if ( ! $is_video_post ) return $html;
    return '<div style="padding:14px 16px;background:#f6f7f7;border-left:4px solid #2271b1;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif;font-size:13px;line-height:1.5;border-radius:0 4px 4px 0;">'
         . '<strong>🎬 Video URL:</strong> <a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $url ) . '</a><br>'
         . '<span style="color:#646970;">Preview is disabled in the editor — the video plays via the custom dialog on the front end. '
         . 'If it shows Error 153, the video has a Content ID claim blocking embedding. Check <a href="https://studio.youtube.com" target="_blank" rel="noopener noreferrer">YouTube Studio</a> → Content → the video → See restrictions.</span>'
         . '</div>';
}, 10, 4 );

// ── Video helpers ─────────────────────────────────────────────────────────────

/**
 * Return a normalised embed URL for YouTube or Vimeo.
 * Supports: youtube.com/watch?v=, youtu.be/, youtube.com/shorts/, vimeo.com/ID
 * Falls back to the original URL for any unrecognised input.
 *
 * @param string $url      Source URL (raw post content or single URL)
 * @param bool   $autoplay Whether to autoplay (true for click-triggered modal,
 *                         false for in-page hero where the user hasn't yet
 *                         interacted — modern browsers block autoplay anyway)
 */
/**
 * Return true if ANY of a post's categories has the slug 'videos'.
 * Checking only $cats[0] misses posts that have multiple categories where
 * 'videos' isn't the primary one — use this everywhere instead of
 * $cat_slug === 'videos'.
 *
 * @param array|false $cats get_the_category() result
 */
function sf_cats_include_video( $cats ) {
    if ( ! $cats ) return false;
    foreach ( $cats as $c ) {
        if ( $c->category_nicename === 'videos' ) return true;
    }
    return false;
}

function sf_video_embed_url( $url, $autoplay = true ) {
    $url = trim( strip_tags( $url ) );
    $ap  = $autoplay ? '1' : '0';

    // ── YouTube ──────────────────────────────────────────────────────────────
    // Use youtube-nocookie.com instead of youtube.com — YouTube's privacy-
    // enhanced embed mode that does NOT require third-party cookies. Safari's
    // Intelligent Tracking Prevention (ITP) and cross-site cookie blocking
    // cause the standard youtube.com embed to fail with player error 153 in
    // Safari. The nocookie variant bypasses this entirely while still serving
    // the same video. Same domain works in Chrome too — drop-in upgrade.
    //
    // Params:
    //   autoplay      — 1 in modal (click triggered), 0 on direct page load
    //   rel=0         — only show related videos from the same channel
    //   modestbranding=1 — minimal YouTube branding for cleaner UI
    //   playsinline=1 — required for iOS Safari to play inline rather than
    //                   force-fullscreen, which kills autoplay on mobile
    if ( preg_match(
        '/(?:youtube\.com\/(?:watch\?v=|v\/|embed\/|shorts\/)|youtu\.be\/|youtube-nocookie\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
        $url, $m
    ) ) {
        return 'https://www.youtube-nocookie.com/embed/' . $m[1]
             . '?autoplay=' . $ap
             . '&rel=0&modestbranding=1&playsinline=1';
    }

    // ── Vimeo ─────────────────────────────────────────────────────────────────
    if ( preg_match( '/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m ) ) {
        return 'https://player.vimeo.com/video/' . $m[1]
             . '?autoplay=' . $ap
             . '&dnt=1&playsinline=1';
    }

    return $url;
}

/** Backward-compatible alias */
function sf_youtube_embed_url( $url ) {
    return sf_video_embed_url( $url );
}

/**
 * Return a thumbnail image URL for a YouTube or Vimeo video.
 * YouTube: static CDN (no API call needed).
 * Vimeo:   oEmbed API with 24-hour transient caching.
 * Returns empty string when the URL isn't a recognised video.
 */
function sf_video_thumbnail_url( $url ) {
    $url = trim( strip_tags( $url ) );

    // ── YouTube ──────────────────────────────────────────────────────────────
    if ( preg_match(
        '/(?:youtube\.com\/(?:watch\?v=|v\/|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
        $url, $m
    ) ) {
        return 'https://img.youtube.com/vi/' . $m[1] . '/maxresdefault.jpg';
    }

    // ── Vimeo ─────────────────────────────────────────────────────────────────
    if ( preg_match( '/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m ) ) {
        $video_id  = $m[1];
        $cache_key = 'sf_vimeo_thumb_' . $video_id;
        $cached    = get_transient( $cache_key );
        if ( false !== $cached ) {
            return $cached;
        }
        $response = wp_remote_get(
            'https://vimeo.com/api/oembed.json?url=' . rawurlencode( 'https://vimeo.com/' . $video_id ) . '&width=1280',
            [ 'timeout' => 5 ]
        );
        $thumb = '';
        if ( ! is_wp_error( $response ) ) {
            $data  = json_decode( wp_remote_retrieve_body( $response ), true );
            $thumb = isset( $data['thumbnail_url'] ) ? $data['thumbnail_url'] : '';
        }
        set_transient( $cache_key, $thumb, DAY_IN_SECONDS );
        return $thumb;
    }

    return '';
}

function salefish_blog_redirect() {
    $request_uri = $_SERVER['REQUEST_URI'];
    if ( strpos( $request_uri, '/newsroom' ) === 0 ) {
        $new_uri = '/blog' . substr( $request_uri, strlen( '/newsroom' ) );
        wp_redirect( $new_uri, 301 );
        exit;
    }
}
add_action( 'template_redirect', 'salefish_blog_redirect' );

// Prevent WordPress _wp_old_slug from redirecting /blog to /newsroom/
add_filter( 'redirect_canonical', function( $redirect_url, $requested_url ) {
    if ( strpos( $requested_url, '/blog' ) !== false ) {
        return false;
    }
    return $redirect_url;
}, 1, 2 );

// Suppress wp_old_slug_redirect when visiting /blog
add_filter( 'old_slug_redirect_post_id', function( $id ) {
    if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/blog' ) === 0 ) {
        return 0;
    }
    return $id;
} );

// One-time migration: fix blog page slug, template, and remove stale old slug
add_action( 'init', function() {
    if ( get_option( 'salefish_blog_migration_v2' ) ) {
        return;
    }
    global $wpdb;

    // Update page template from page-newsroom.php to page-blog.php
    $wpdb->update(
        $wpdb->postmeta,
        array( 'meta_value' => 'page-blog.php' ),
        array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-newsroom.php' )
    );

    // Find the page that should be the blog (has template page-blog.php or slug newsroom)
    $blog_page = $wpdb->get_row(
        "SELECT ID, post_name FROM {$wpdb->posts}
         WHERE post_status = 'publish' AND post_type = 'page'
         AND post_name = 'newsroom'
         LIMIT 1"
    );

    if ( $blog_page ) {
        // Fix the slug to 'blog'
        $wpdb->update(
            $wpdb->posts,
            array( 'post_name' => 'blog' ),
            array( 'ID' => $blog_page->ID )
        );

        // Remove the _wp_old_slug = 'blog' meta that causes the redirect loop
        $wpdb->delete(
            $wpdb->postmeta,
            array( 'post_id' => $blog_page->ID, 'meta_key' => '_wp_old_slug', 'meta_value' => 'blog' )
        );

        // Store newsroom as an old slug so /newsroom/ still redirects to /blog/
        $existing = $wpdb->get_var( $wpdb->prepare(
            "SELECT meta_id FROM {$wpdb->postmeta}
             WHERE post_id = %d AND meta_key = '_wp_old_slug' AND meta_value = 'newsroom'",
            $blog_page->ID
        ) );
        if ( ! $existing ) {
            $wpdb->insert(
                $wpdb->postmeta,
                array( 'post_id' => $blog_page->ID, 'meta_key' => '_wp_old_slug', 'meta_value' => 'newsroom' )
            );
        }

        clean_post_cache( $blog_page->ID );
    }

    update_option( 'salefish_blog_migration_v2', 1 );
} );

// Auto-create /blog/success-stories, /blog/press, /blog/videos child pages
add_action( 'init', function() {
    if ( get_option( 'salefish_blog_filter_pages_v1' ) ) {
        return;
    }

    $blog_page = get_page_by_path( 'blog' );
    if ( ! $blog_page ) {
        return; // Blog page not found yet — will retry on next request
    }

    $filter_pages = array(
        'success-stories' => 'Success Stories',
        'press'           => 'Press',
        'videos'          => 'Videos',
    );

    $created = 0;
    foreach ( $filter_pages as $slug => $title ) {
        $existing = get_page_by_path( 'blog/' . $slug );
        if ( $existing ) {
            update_post_meta( $existing->ID, '_wp_page_template', 'page-blog-filter.php' );
            $created++;
            continue;
        }

        $page_id = wp_insert_post( array(
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_parent'  => $blog_page->ID,
        ) );

        if ( $page_id && ! is_wp_error( $page_id ) ) {
            update_post_meta( $page_id, '_wp_page_template', 'page-blog-filter.php' );
            $created++;
        }
    }

    if ( $created === count( $filter_pages ) ) {
        flush_rewrite_rules();
        update_option( 'salefish_blog_filter_pages_v1', 1 );
    }
} );

/**
 * Output a <picture> element with AVIF source + original fallback.
 *
 * Why: large PNG/JPEG hero images are the dominant LCP cost on Safari/iOS.
 * AVIF cuts file size 6-10x with no perceptible quality loss and is
 * supported by every Safari 16+ device (Sept 2022 onwards) and all
 * current Chrome/Firefox builds. The <picture> element gracefully falls
 * back to the original src for any browser that doesn't understand AVIF.
 *
 * Convention: each .png/.jpg has an .avif sibling at the same disk path
 * (generated by tools/convert-images-to-avif.sh). If no AVIF sibling
 * exists, this helper degrades to a plain <img> so it can be used safely
 * on every <img> tag in the codebase.
 *
 * @param string $url   Image URL — may be a full http(s) URL or a path
 *                      relative to the theme. Also accepts ACF return
 *                      values (URL strings) directly.
 * @param array  $args  Optional attributes:
 *                        alt           — alt text (default empty)
 *                        class         — class on the <img>
 *                        loading       — 'lazy' (default) | 'eager'
 *                        fetchpriority — 'high' | 'low' | 'auto'
 *                        decoding      — 'async' (default) | 'sync'
 *                        sizes         — sizes attr for srcset
 *                        width / height — intrinsic dimensions (prevents CLS)
 *                        id            — DOM id
 *                        data          — array of data-* attrs
 *                        attrs         — raw extra attribute string (escape-safe)
 */
function sf_picture( $url, $args = [] ) {
    if ( empty( $url ) ) return;

    $defaults = [
        'alt'           => '',
        'class'         => '',
        'loading'       => 'lazy',
        'fetchpriority' => '',
        'decoding'      => 'async',
        'sizes'         => '',
        'width'         => '',
        'height'        => '',
        'id'            => '',
        'data'          => [],
        'attrs'         => '',
    ];
    $args = array_merge( $defaults, $args );

    // Resolve URL → disk path so we can check for an AVIF sibling.
    $upload_dir = wp_get_upload_dir();
    $theme_uri  = get_template_directory_uri();
    $theme_dir  = get_template_directory();
    $abs_path   = '';

    if ( strpos( $url, $theme_uri ) === 0 ) {
        $abs_path = $theme_dir . substr( $url, strlen( $theme_uri ) );
    } elseif ( strpos( $url, $upload_dir['baseurl'] ) === 0 ) {
        $abs_path = $upload_dir['basedir'] . substr( $url, strlen( $upload_dir['baseurl'] ) );
    }

    $avif_url    = '';
    $avif_srcset = '';
    if ( $abs_path && preg_match( '/\.(png|jpe?g)$/i', $abs_path ) ) {
        $avif_path = preg_replace( '/\.(png|jpe?g)$/i', '.avif', $abs_path );
        if ( $abs_path !== $avif_path && file_exists( $avif_path ) ) {
            $avif_url = preg_replace( '/\.(png|jpe?g)$/i', '.avif', $url );
            // Look for size-variant siblings like `name-480w.avif`. When
            // present, emit a srcset so mobile gets the smaller file.
            $base   = preg_replace( '/\.(png|jpe?g)$/i', '', $abs_path );
            $base_u = preg_replace( '/\.(png|jpe?g)$/i', '', $url );
            $variants = [];
            foreach ( [ 320, 480, 640, 800, 1024, 1280, 1920, 2560 ] as $w ) {
                $variant_path = $base . '-' . $w . 'w.avif';
                if ( file_exists( $variant_path ) ) {
                    $variants[] = esc_url( $base_u . '-' . $w . 'w.avif' ) . ' ' . $w . 'w';
                }
            }
            if ( $variants ) {
                // Add the full-size as the largest fallback so very wide
                // viewports still get the original.
                if ( ! empty( $args['width'] ) ) {
                    $variants[] = esc_url( $avif_url ) . ' ' . intval( $args['width'] ) . 'w';
                }
                $avif_srcset = implode( ', ', $variants );
            }
        }
    }

    // Auto-detect intrinsic width/height when not supplied by the caller.
    // The browser uses these to reserve aspect-ratio space *before* the
    // image loads — eliminating CLS (cumulative layout shift), which is a
    // major mobile PageSpeed metric. We cache results in a transient so
    // we pay the file-stat cost only once per image per day.
    if ( $abs_path && ( ! $args['width'] || ! $args['height'] ) && file_exists( $abs_path ) ) {
        $cache_key = 'sf_imgsize_' . md5( $abs_path . filemtime( $abs_path ) );
        $size = get_transient( $cache_key );
        if ( $size === false ) {
            $size = @getimagesize( $abs_path );
            set_transient( $cache_key, $size ?: [ 0, 0 ], DAY_IN_SECONDS );
        }
        if ( ! empty( $size[0] ) ) {
            if ( ! $args['width'] )  $args['width']  = $size[0];
            if ( ! $args['height'] ) $args['height'] = $size[1];
        }
    }

    // Build the <img> attribute string — used inside <picture> AND as
    // the standalone fallback when no AVIF exists.
    $attr = '';
    if ( $args['class'] )         $attr .= ' class="' . esc_attr( $args['class'] ) . '"';
    if ( $args['id'] )            $attr .= ' id="' . esc_attr( $args['id'] ) . '"';
    if ( $args['alt'] !== false ) $attr .= ' alt="' . esc_attr( $args['alt'] ) . '"';
    if ( $args['loading'] )       $attr .= ' loading="' . esc_attr( $args['loading'] ) . '"';
    if ( $args['decoding'] )      $attr .= ' decoding="' . esc_attr( $args['decoding'] ) . '"';
    if ( $args['fetchpriority'] ) $attr .= ' fetchpriority="' . esc_attr( $args['fetchpriority'] ) . '"';
    if ( $args['width'] )         $attr .= ' width="' . esc_attr( $args['width'] ) . '"';
    if ( $args['height'] )        $attr .= ' height="' . esc_attr( $args['height'] ) . '"';
    if ( $args['sizes'] )         $attr .= ' sizes="' . esc_attr( $args['sizes'] ) . '"';
    foreach ( (array) $args['data'] as $k => $v ) {
        $attr .= ' data-' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
    }
    if ( $args['attrs'] )         $attr .= ' ' . $args['attrs'];

    if ( $avif_url ) {
        echo '<picture>';
        if ( $avif_srcset ) {
            // Default `sizes` for hero-style large images: full-width on
            // mobile (≤768 px), half-width on tablet+. Callers can override
            // via the `sizes` arg.
            $sizes_attr = $args['sizes'] ?: '(max-width: 768px) 100vw, 50vw';
            echo '<source type="image/avif" srcset="' . $avif_srcset . '" sizes="' . esc_attr( $sizes_attr ) . '">';
        } else {
            echo '<source type="image/avif" srcset="' . esc_url( $avif_url ) . '">';
        }
        echo '<img src="' . esc_url( $url ) . '"' . $attr . '>';
        echo '</picture>';
    } else {
        echo '<img src="' . esc_url( $url ) . '"' . $attr . '>';
    }
}
