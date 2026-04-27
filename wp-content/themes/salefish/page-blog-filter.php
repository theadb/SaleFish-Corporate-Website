<?php
/**
 * Template Name: Blog Category Filter Page
 * Shows posts filtered by a single category, matching the blog page style.
 * The category is determined by the page's slug.
 */

$page_slug    = get_post_field( 'post_name', get_the_ID() );
$category_map = array(
    'success-stories' => array( 'label' => 'Success Stories', 'filter' => 'success-stories' ),
    'press'           => array( 'label' => 'Press',           'filter' => 'press'           ),
    'videos'          => array( 'label' => 'Videos',          'filter' => 'videos'          ),
);
$cat_info   = isset( $category_map[ $page_slug ] ) ? $category_map[ $page_slug ] : array( 'label' => 'All Articles', 'filter' => 'all' );
$cat_filter = $cat_info['filter'];
$cat_label  = $cat_info['label'];

$args = array(
    'post_status'    => 'publish',
    'post_type'      => 'post',
    'posts_per_page' => 9,
    'paged'          => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
);
if ( $cat_filter !== 'all' ) {
    $args['category_name'] = $cat_filter;
}
$articles = get_posts( $args );

get_header();
?>

<main class="blog">

    <!-- HERO -->
    <section class="blog-hero sf-section">
        <div class="sf-container">
            <div class="blog-hero__inner">
                <span class="blog-hero__eyebrow" data-aos="fade-up" data-aos-delay="100">The SaleFish Blog</span>
                <h1 data-aos="fade-up" data-aos-delay="250"><?php echo esc_html( $cat_label ); ?></h1>
            </div>
        </div>
    </section>
    <!-- END HERO -->

    <!-- FILTER + ARTICLES -->
    <section class="blog-articles sf-section" id="articles">
        <div class="sf-container">

            <div class="blog-filter">
                <a class="blog-filter__btn<?php echo $cat_filter === 'all' ? ' active' : ''; ?>" href="/blog">All Articles</a>
                <a class="blog-filter__btn<?php echo $cat_filter === 'success-stories' ? ' active' : ''; ?>" href="/blog/success-stories">Success Stories</a>
                <a class="blog-filter__btn<?php echo $cat_filter === 'press' ? ' active' : ''; ?>" href="/blog/press">Press</a>
                <a class="blog-filter__btn<?php echo $cat_filter === 'videos' ? ' active' : ''; ?>" href="/blog/videos">Videos</a>
            </div>

            <div class="blog-grid">
                <?php $card_i = 0; foreach ( $articles as $article ) :
                    $id       = $article->ID;
                    $title    = get_the_title( $id );
                    $thumb    = get_the_post_thumbnail( $id, 'medium_large' );
                    $date     = get_the_date( 'M j, Y', $id );
                    $link     = get_permalink( $id );
                    $cats     = get_the_category( $id );
                    $cat_name = $cats ? $cats[0]->cat_name : '';
                    $cat_slug = $cats ? $cats[0]->category_nicename : '';
                    $content  = $article->post_content;
                    $author   = get_the_author_meta( 'display_name', $article->post_author );
                    $featured = has_tag( 'featured', $id );
                    $is_video = $cat_slug === 'videos';
                    $embed    = $is_video ? sf_video_embed_url( $content ) : '';
                    if ( empty( $thumb ) && $is_video ) {
                        $vthumb = sf_video_thumbnail_url( $content );
                        if ( $vthumb ) $thumb = '<img src="' . esc_url( $vthumb ) . '" alt="' . esc_attr( $title ) . '" loading="lazy">';
                    }
                ?>
                <a href="<?php echo $is_video ? esc_url( $embed ) : esc_url( $link ); ?>"
                   class="sf-card blog-card blog-card-animate"
                   style="animation-delay: <?php echo ( $card_i * 0.07 ); ?>s"
                   data-category="<?php echo esc_attr( $cat_slug ); ?>"
                   <?php echo $is_video ? 'data-video-url="' . esc_attr( $embed ) . '"' : ''; ?>>
                    <?php if ( $thumb ) : ?>
                    <div class="blog-card__image"><?php echo $thumb; ?></div>
                    <?php endif; ?>
                    <div class="blog-card__body">
                        <?php if ( $featured || $cat_name ) : ?>
                        <div class="blog-card__badges">
                            <?php if ( $cat_name ) : ?><span class="sf-badge sf-badge--<?php echo esc_attr( $cat_slug ); ?>"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
                            <?php if ( $featured ) : ?><span class="sf-badge sf-badge--featured">Featured</span><?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <span class="blog-card__date">Published: <?php echo esc_html( $date ); ?></span>
                        <span class="blog-card__author">By <?php echo esc_html( $author ); ?></span>
                        <h3 class="blog-card__title"><?php echo esc_html( $title ); ?></h3>
                        <span class="blog-card__link"><?php echo $is_video ? 'Watch Video' : sf_post_cta( $cat_slug ); ?></span>
                    </div>
                </a>
                <?php $card_i++; endforeach; ?>
            </div><!-- .blog-grid -->

            <div class="blog-loadmore">
                <button class="sf-btn sf-btn--secondary load_more" id="blog-load-more" data-page="1" data-category="<?php echo esc_attr( $cat_filter ); ?>">
                    Load More
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
            </div>

        </div>
    </section>
    <!-- END ARTICLES -->

</main>

<script>
(function () {
  'use strict';

  var grid        = document.querySelector('.blog-grid');
  var loadMoreBtn = document.getElementById('blog-load-more');
  var currentPage = 1;
  var currentCat  = '<?php echo esc_js( $cat_filter ); ?>';
  var isLoading   = false;

  function buildCard(post) {
    var cat_slug  = post.cat_slug || '';
    var cat_name  = post.cat_name || '';
    var is_video  = cat_slug === 'videos';
    var embed_url = post.embed_url || '';
    var card      = document.createElement('a');
    card.href      = is_video ? (embed_url || '#') : (post.link || '#');
    card.className = 'sf-card blog-card blog-card-animate';
    card.setAttribute('data-category', cat_slug);
    if (is_video && embed_url) { card.setAttribute('data-video-url', embed_url); }
    card.innerHTML =
      (post.thumb ? '<div class="blog-card__image">' + post.thumb + '</div>' : '') +
      '<div class="blog-card__body">' +
        ((post.is_featured || cat_name) ? '<div class="blog-card__badges">' + (cat_name ? '<span class="sf-badge sf-badge--' + cat_slug + '">' + cat_name + '</span>' : '') + (post.is_featured ? '<span class="sf-badge sf-badge--featured">Featured</span>' : '') + '</div>' : '') +
        (post.date ? '<span class="blog-card__date">Published: ' + post.date + '</span>' : '') +
        (post.author ? '<span class="blog-card__author">By ' + post.author + '</span>' : '') +
        '<h3 class="blog-card__title">' + post.title + '</h3>' +
        '<span class="blog-card__link">' + (is_video ? 'Watch Video' : ({ 'success-stories': 'See the Results', 'press': 'Read It', 'blog': 'Dig In' }[cat_slug] || 'Keep Reading')) + '</span>' +
      '</div>';
    return card;
  }

  function fetchMore(page, category) {
    if (isLoading || typeof salefishAjax === 'undefined') return;
    isLoading = true;
    var formData = new FormData();
    formData.append('action',   'load_more_post');
    formData.append('paged',    page);
    formData.append('category', category);
    formData.append('nonce',    salefishAjax.loadMoreNonce);
    fetch(salefishAjax.ajaxurl, { method: 'POST', body: formData })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        (res.posts || []).forEach(function (post) { grid.appendChild(buildCard(post)); });
        if (loadMoreBtn) {
          loadMoreBtn.style.display = (page >= res.max || !res.max) ? 'none' : '';
        }
      })
      .catch(function () {})
      .finally(function () { isLoading = false; });
  }

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function () {
      currentPage++;
      fetchMore(currentPage, currentCat);
    });
  }
}());
</script>

<?php
// Category-aware CTA copy — mirrors single-post.php logic
$is_success_cat = ( $cat_filter === 'success-stories' );
$cta_heading    = $is_success_cat ? 'Ready to See Results Like These?' : 'See SaleFish in Action';
$cta_subtext    = $is_success_cat
    ? 'Join the builders and developers who are outselling the competition with SaleFish.'
    : 'The all-in-one platform built for new home sales teams who expect to win.';
$cta_label      = $is_success_cat ? 'Get My Demo' : 'Get a Demo';
?>

<!-- BLOG CATEGORY CTA STRIP -->
<section class="sp-cta">
	<div class="max_wrapper">
		<div class="sp-cta__inner">
			<h2><?php echo esc_html( $cta_heading ); ?></h2>
			<p><?php echo esc_html( $cta_subtext ); ?></p>
			<button class="sp-cta__btn" data-sf-modal="register"><?php echo esc_html( $cta_label ); ?></button>
		</div>
	</div>
</section>
<!-- END BLOG CATEGORY CTA STRIP -->

<?php get_footer(); ?>
