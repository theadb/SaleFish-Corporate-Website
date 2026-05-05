<?php
/**
 * Pre-footer featured posts strip.
 * Shown on every page except the blog listing and category-filter pages.
 * Queries the 3 most-recent posts tagged "featured".
 */

$_sfp_posts = get_posts( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tag'            => 'featured',
] );

if ( empty( $_sfp_posts ) ) return;
?>

<section class="sf-footer-posts" aria-label="Featured blog posts">
    <div class="max_wrapper">
        <div class="sf-footer-posts__header" data-reveal>
            <p class="blog-section-label">Featured Blog Posts</p>
            <a class="sf-footer-posts__all sf-cta-link" href="/blog">View All Articles</a>
        </div>
        <div class="blog-sticky__grid">
            <?php foreach ( $_sfp_posts as $i => $_sfp ) :
                $id       = $_sfp->ID;
                $cats     = get_the_category( $id );
                $cat_slug = $cats ? $cats[0]->category_nicename : '';
                $cat_name = $cats ? $cats[0]->cat_name          : '';
                $is_video = sf_cats_include_video( $cats );
                $embed    = $is_video ? sf_video_embed_url( $_sfp->post_content ) : '';
                $thumb    = get_the_post_thumbnail( $id, 'thumbnail' );
                if ( empty( $thumb ) && $is_video ) {
                    $vthumb = sf_video_thumbnail_url( $_sfp->post_content );
                    if ( $vthumb ) {
                        $thumb = '<img src="' . esc_url( $vthumb ) . '" alt="' . esc_attr( get_the_title( $id ) ) . '" loading="lazy">';
                    }
                }
                $link   = $is_video ? esc_url( $embed ) : esc_url( get_permalink( $id ) );
                $date   = get_the_date( 'M j, Y', $id );
                $author = get_the_author_meta( 'display_name', $_sfp->post_author );
            ?>
            <a href="<?php echo $link; ?>"
               class="blog-sticky__card"
               data-reveal
               data-reveal-delay="<?php echo ( ( $i + 1 ) * 100 ); ?>"
               <?php echo $is_video && $embed ? 'data-video-url="' . esc_attr( $embed ) . '"' : ''; ?>>
                <?php if ( $thumb ) : ?>
                <div class="blog-sticky__card-image"><?php echo $thumb; ?></div>
                <?php endif; ?>
                <div class="blog-sticky__card-body">
                    <div class="blog-card__badges">
                        <?php if ( $cat_name ) : ?>
                        <span class="sf-badge sf-badge--<?php echo esc_attr( $cat_slug ); ?>"><?php echo esc_html( $cat_name ); ?></span>
                        <?php endif; ?>
                        <span class="sf-badge sf-badge--featured">Featured</span>
                    </div>
                    <p class="blog-sticky__card-meta"><?php echo esc_html( $date ); ?> &middot; <?php echo esc_html( $author ); ?> &middot; <?php echo esc_html( sf_read_time( $_sfp->post_content ) ); ?></p>
                    <h3 class="blog-sticky__card-title"><?php echo esc_html( get_the_title( $id ) ); ?></h3>
                    <span class="blog-sticky__card-link">Dig In</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div><!-- .max_wrapper -->
</section>
