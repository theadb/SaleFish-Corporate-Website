<?php
/**
 * Archive template for author, category, and tag archive pages.
 * Matches the blog filter page visual design.
 */

global $wp_query;

$is_author   = is_author();
$is_category = is_category();
$is_tag      = is_tag();

$cat_filter    = 'all';
$archive_title = '';
$eyebrow       = 'The SaleFish Blog';

if ( $is_author ) {
	$author_obj    = get_queried_object();
	$archive_title = $author_obj ? $author_obj->display_name : get_the_archive_title();
	$eyebrow       = 'Author';
} elseif ( $is_category ) {
	$cat_obj       = get_queried_object();
	$archive_title = $cat_obj ? $cat_obj->name : get_the_archive_title();
	$cat_filter    = $cat_obj ? $cat_obj->slug : 'all';
} elseif ( $is_tag ) {
	$archive_title = single_tag_title( '', false ) ?: get_the_archive_title();
	$eyebrow       = 'Tag';
} else {
	$archive_title = get_the_archive_title();
}

// Collect posts from the main query before get_header()
$articles = [];
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$articles[] = get_post();
	}
}

// Load More only works for category archives (AJAX handler supports category_name)
$has_more_pages = $is_category && $wp_query->max_num_pages > 1;

get_header();
?>

<main class="blog">

	<!-- HERO -->
	<section class="blog-hero sf-section">
		<div class="sf-container">
			<div class="blog-hero__inner">
				<span class="blog-hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<h1><?php echo esc_html( $archive_title ); ?></h1>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- ARTICLES -->
	<section class="blog-articles sf-section" id="articles">
		<div class="sf-container">

			<?php if ( $is_category ) : ?>
			<div class="blog-filter">
				<a class="blog-filter__btn" href="/blog">All Articles</a>
				<a class="blog-filter__btn<?php echo $cat_filter === 'success-stories' ? ' active' : ''; ?>" href="/blog/success-stories">Success Stories</a>
				<a class="blog-filter__btn<?php echo $cat_filter === 'press' ? ' active' : ''; ?>" href="/blog/press">Press</a>
				<a class="blog-filter__btn<?php echo $cat_filter === 'videos' ? ' active' : ''; ?>" href="/blog/videos">Videos</a>
			</div>
			<?php endif; ?>

			<?php if ( $articles ) : ?>
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
					$is_video = sf_cats_include_video( $cats );
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
						<p class="blog-card__meta"><?php echo esc_html( $date ); ?> &middot; <?php echo esc_html( $author ); ?> &middot; <?php echo esc_html( sf_read_time( $content ) ); ?></p>
						<h3 class="blog-card__title"><?php echo esc_html( $title ); ?></h3>
						<span class="blog-card__link"><?php echo $is_video ? 'Watch Video' : sf_post_cta( $cat_slug ); ?></span>
					</div>
				</a>
				<?php $card_i++; endforeach; ?>
			</div><!-- .blog-grid -->
			<?php else : ?>
			<p class="blog-no-results">No posts found.</p>
			<?php endif; ?>

			<div class="blog-loadmore">
				<button type="button" class="sf-btn sf-btn--secondary load_more" id="blog-load-more"<?php echo $has_more_pages ? '' : ' style="display:none"'; ?>>
					Load More
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
				</button>
			</div>

		</div>
	</section>
	<!-- END ARTICLES -->

</main>

<?php if ( $has_more_pages ) : ?>
<script>
(function () {
  'use strict';

  var grid          = document.querySelector('.blog-grid');
  var loadMoreBtn   = document.getElementById('blog-load-more');
  var loadMoreLabel = loadMoreBtn ? loadMoreBtn.innerHTML : '';
  var currentPage   = 1;
  var currentCat    = '<?php echo esc_js( $cat_filter ); ?>';
  var isLoading     = false;

  function buildCard(post) {
    var cat_slug  = post.cat_slug || '';
    var cat_name  = post.cat_name || '';
    var is_video  = post.is_video || false;
    var embed_url = post.embed_url || '';
    var card      = document.createElement('a');
    card.href      = is_video ? (embed_url || '#') : (post.link || '#');
    card.className = 'sf-card blog-card blog-card-animate';
    card.setAttribute('data-category', cat_slug);
    if (is_video && embed_url) { card.setAttribute('data-video-url', embed_url); }
    var metaParts = [];
    if (post.date)      metaParts.push(post.date);
    if (post.author)    metaParts.push(post.author);
    if (post.read_time) metaParts.push(post.read_time);
    var metaLine = metaParts.length ? '<p class="blog-card__meta">' + metaParts.join(' · ') + '</p>' : '';
    card.innerHTML =
      (post.thumb ? '<div class="blog-card__image">' + post.thumb + '</div>' : '') +
      '<div class="blog-card__body">' +
        ((post.is_featured || cat_name) ? '<div class="blog-card__badges">' + (cat_name ? '<span class="sf-badge sf-badge--' + cat_slug + '">' + cat_name + '</span>' : '') + (post.is_featured ? '<span class="sf-badge sf-badge--featured">Featured</span>' : '') + '</div>' : '') +
        metaLine +
        '<h3 class="blog-card__title">' + post.title + '</h3>' +
        '<span class="blog-card__link">' + (is_video ? 'Watch Video' : ({ 'success-stories': 'See the Results', 'press': 'Read It', 'blog': 'Dig In' }[cat_slug] || 'Keep Reading')) + '</span>' +
      '</div>';
    return card;
  }

  function fetchMore(page, category) {
    if (isLoading || typeof salefishAjax === 'undefined') return;
    isLoading = true;
    if (loadMoreBtn) { loadMoreBtn.innerHTML = 'Loading…'; loadMoreBtn.disabled = true; }
    var formData = new FormData();
    formData.append('action',   'load_more_post');
    formData.append('paged',    page);
    formData.append('category', category);
    fetch(salefishAjax.ajaxurl, { method: 'POST', body: formData })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        currentPage = page;
        (res.posts || []).forEach(function (post) { grid.appendChild(buildCard(post)); });
        if (loadMoreBtn) {
          loadMoreBtn.style.display = (page >= res.max || !res.max) ? 'none' : '';
          loadMoreBtn.innerHTML = loadMoreLabel;
          loadMoreBtn.disabled = false;
        }
        isLoading = false;
      })
      .catch(function () {
        if (loadMoreBtn) { loadMoreBtn.innerHTML = loadMoreLabel; loadMoreBtn.disabled = false; }
        isLoading = false;
      });
  }

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function () {
      fetchMore(currentPage + 1, currentCat);
    });
  }
}());
</script>
<?php endif; ?>

<!-- CTA STRIP -->
<section class="sp-cta">
	<div class="max_wrapper">
		<div class="sp-cta__inner">
			<h2>See SaleFish in Action</h2>
			<p>The all-in-one platform built for new home sales teams who expect to win.</p>
			<button class="button" data-sf-modal="register">Get a Demo</button>
		</div>
	</div>
</section>
<!-- END CTA STRIP -->

<?php get_footer(); ?>
