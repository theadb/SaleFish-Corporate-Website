<?php
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
    wp_enqueue_style('style-name', get_template_directory_uri() . '/dest/app.css');
    wp_enqueue_script('script-name', get_template_directory_uri() . '/dest/app.js', array(), '1.0.0', true);
    wp_localize_script('script-name', 'salefishAjax', [
        'ajaxurl'      => admin_url('admin-ajax.php'),
        'nonce'        => wp_create_nonce('salefish_nonce'),
        'loadMoreNonce'=> wp_create_nonce('salefish_load_more'),
    ]);
}
add_action('wp_enqueue_scripts', 'kickass_scripts');

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


function limit_text($text, $limit)
{
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos   = array_keys($words);
        $text  = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}


add_filter('use_block_editor_for_post', '__return_false');


function load_more_post()
{
    check_ajax_referer( 'salefish_load_more', 'nonce' );
    $paged    = isset($_POST['paged'])    ? intval($_POST['paged'])                        : 1;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category'])        : 'all';

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
            $cat_slug = $cats ? $cats[0]->category_nicename : '';
            $cat_name = $cats ? $cats[0]->cat_name          : '';
            $thumb    = get_the_post_thumbnail( $id );
            $link     = get_permalink( $id );
            $title    = limit_text( get_the_title(), 14 );
            $date        = get_the_date( 'M j, Y', $id );
            $author      = get_the_author_meta( 'display_name', get_post_field( 'post_author', $id ) );
            $is_featured = has_tag( 'featured', $id );

            $response[] = [
                'id'         => $id,
                'category'   => $cats,
                'cat_slug'   => $cat_slug,
                'cat_name'   => $cat_name,
                'thumb'      => $thumb,
                'link'       => $link,
                'content'    => get_the_content(),
                'title'      => $title,
                'date'       => $date,
                'author'     => $author,
                'is_featured'=> $is_featured,
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

// function load_more_post()
// {
//     $articles = get_posts(
//         array(
//               'post_status' => 'publish',
//               'post_type' => 'post',
//               'posts_per_page' => 9,
//               'paged' => $_POST['paged'],
//               'orderby' => 'date',
//               'order' => 'DESC',
//           )
//     );

//     $ajaxposts = new WP_Query([
//     'posts_per_page' => 9,
//     'paged' => $_POST['paged'],
//   ]);
//     $max_pages = $ajaxposts->max_num_pages;


//     $response = array();

//     foreach ($articles as $article) {
//         $id = $article->ID;
//         $category = get_the_category($id);
//         $thumb = get_the_post_thumbnail($id);
//         $link = get_permalink($id);
//         $title = limit_text($article->post_title, 14);

//         array_push($response, array('id' => $id, 'category' => $category, 'thumb' => $thumb, 'link' => $link, 'title' => $title,));
//     }

//     $result = [
//     'max' => $max_pages,
//     'posts' => $response,
//   ];

//     echo json_encode($result);
//     exit;
// }
// add_action('wp_ajax_load_more_post', 'load_more_post');
// add_action('wp_ajax_nopriv_load_more_post', 'load_more_post');
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
