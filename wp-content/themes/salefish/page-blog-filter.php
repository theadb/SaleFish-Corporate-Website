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
                ?>
                <a href="<?php echo $is_video ? esc_url( sf_youtube_embed_url( $content ) ) : esc_url( $link ); ?>"
                   class="sf-card blog-card blog-card-animate"
                   style="animation-delay: <?php echo ( $card_i * 0.07 ); ?>s"
                   data-category="<?php echo esc_attr( $cat_slug ); ?>"
                   <?php echo $is_video ? 'data-fancybox data-type="iframe"' : ''; ?>>
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
                        <span class="blog-card__link"><?php echo $is_video ? 'Watch Video' : 'Read More'; ?></span>
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

  function youtubeEmbedUrl(url) {
    if (!url) return url;
    var m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    return m ? 'https://www.youtube.com/embed/' + m[1] + '?autoplay=1&rel=0&origin=https://salefish.app' : url;
  }

  function buildCard(post) {
    var cat_slug = post.cat_slug || '';
    var cat_name = post.cat_name || '';
    var is_video = cat_slug === 'videos';
    var card     = document.createElement('a');
    card.href      = is_video ? youtubeEmbedUrl(post.content || post.link || '#') : (post.link || '#');
    card.className = 'sf-card blog-card blog-card-animate';
    card.setAttribute('data-category', cat_slug);
    if (is_video) { card.setAttribute('data-fancybox', ''); card.setAttribute('data-type', 'iframe'); }
    card.innerHTML =
      (post.thumb ? '<div class="blog-card__image">' + post.thumb + '</div>' : '') +
      '<div class="blog-card__body">' +
        ((post.is_featured || cat_name) ? '<div class="blog-card__badges">' + (cat_name ? '<span class="sf-badge sf-badge--' + cat_slug + '">' + cat_name + '</span>' : '') + (post.is_featured ? '<span class="sf-badge sf-badge--featured">Featured</span>' : '') + '</div>' : '') +
        (post.date ? '<span class="blog-card__date">Published: ' + post.date + '</span>' : '') +
        (post.author ? '<span class="blog-card__author">By ' + post.author + '</span>' : '') +
        '<h3 class="blog-card__title">' + post.title + '</h3>' +
        '<span class="blog-card__link">' + (is_video ? 'Watch Video' : 'Read More') + '</span>' +
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

<?php get_footer(); ?>
