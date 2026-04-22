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


function salefish_2026_scripts() {
    $theme_version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
        'salefish-2026-style',
        get_template_directory_uri() . '/dest/app.css',
        [],
        $theme_version
    );

    wp_enqueue_script(
        'salefish-2026-app',
        get_template_directory_uri() . '/dest/app.js',
        [ 'jquery' ],
        $theme_version,
        [ 'strategy' => 'defer', 'in_footer' => true ]
    );

    wp_enqueue_script(
        'fancybox-js',
        'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js',
        [ 'jquery' ],
        '3.5.7',
        [ 'in_footer' => true ]
    );

    wp_localize_script( 'salefish-2026-app', 'salefishAjax', [
        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        'nonce'         => wp_create_nonce( 'salefish_nonce' ),
        'loadMoreNonce' => wp_create_nonce( 'salefish_load_more' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'salefish_2026_scripts' );

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

// Remove WordPress version from <meta name="generator">
remove_action('wp_head', 'wp_generator');

// Add hreflang for English (x-default)
function salefish_hreflang() {
    $url = 'https://salefish.app' . esc_attr( $_SERVER['REQUEST_URI'] );
    echo '<link rel="alternate" hreflang="en" href="' . $url . '">' . "\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . $url . '">' . "\n";
}
add_action('wp_head', 'salefish_hreflang');

// Organization structured data (homepage only)
function salefish_organization_schema() {
    if ( ! is_front_page() ) return;
    ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "SaleFish",
    "url": "https://salefish.app",
    "logo": "https://salefish.app/wp-content/themes/salefish-2026/img/dark_salefish_logo.png",
    "telephone": "+18778927741",
    "email": "hello@salefish.app",
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+18778927741",
        "contactType": "customer support"
    },
    "sameAs": [
        "https://www.linkedin.com/company/salefishapp/",
        "https://www.instagram.com/salefishapp/"
    ]
}
</script>
    <?php
}
add_action('wp_head', 'salefish_organization_schema');

// Add loading="lazy" to post thumbnails not in the LCP zone
add_filter('wp_get_attachment_image_attributes', function( $attr, $attachment, $size ) {
    if ( ! isset( $attr['loading'] ) ) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}, 10, 3 );


function load_more_post()
{
    check_ajax_referer( 'salefish_load_more', 'nonce' );
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $query = new WP_Query([
        'post_status' => 'publish',
        'post_type' => 'post',
        'posts_per_page' => 9,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    $response = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $category = get_the_category($id);
            $thumb = get_the_post_thumbnail($id);
            $link = get_permalink($id);
            $title = limit_text(get_the_title(), 14);

            $response[] = [
                'id' => $id,
                'category' => $category,
                'thumb' => $thumb,
                'link' => $link,
                'title' => $title,
                'date' => get_the_date( 'M j, Y', $id ),
            ];
        }
        wp_reset_postdata();
    }

    $result = [
        'max' => $query->max_num_pages,
        'posts' => $response,
    ];

    echo json_encode($result);
    wp_die(); // Better than exit for WordPress AJAX
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
