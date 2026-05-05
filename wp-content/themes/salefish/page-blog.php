<?php
/**
 * Template Name: Blog Page
 */
$initial_query = new WP_Query( array(
    'post_status'    => 'publish',
    'post_type'      => 'post',
    'posts_per_page' => 9,
    'paged'          => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
$articles       = $initial_query->posts;
$has_more_pages = $initial_query->max_num_pages > 1;
wp_reset_postdata();

get_header();
?>

<main class="blog">

	<!-- HERO -->
	<section class="blog-hero sf-section">
		<div class="sf-container">
			<div class="blog-hero__inner">
				<span class="blog-hero__eyebrow" data-aos="fade-up" data-aos-delay="100">Insights, Stories & News From</span>
				<h1 data-aos="fade-up" data-aos-delay="250">The SaleFish Blog</h1>
				<p data-aos="fade-up" data-aos-delay="400">(Are you taking notes?)</p>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- FEATURED POSTS -->
	<?php if ( ! empty( $articles ) && count( $articles ) >= 2 ) : ?>
	<section class="blog-featured sf-section">
		<div class="sf-container">
			<p class="blog-section-label">Recent Blog Posts</p>
			<div class="blog-featured__grid">

				<?php
				$fa        = $articles[0];
				$fid       = $fa->ID;
				$f_cats    = get_the_category( $fid );
				$f_cat_name = $f_cats ? $f_cats[0]->cat_name : '';
				$f_cat_slug = $f_cats ? $f_cats[0]->category_nicename : '';
				$f_thumb   = get_the_post_thumbnail( $fid, 'large' );
				$f_link    = get_permalink( $fid );
				$f_date    = get_the_date( 'M j, Y', $fid );
				$f_title   = get_the_title( $fid );
				$f_excerpt = wp_trim_words( get_the_excerpt( $fid ), 22, '...' );
				$f_author  = get_the_author_meta( 'display_name', $fa->post_author );
				$f_read    = sf_read_time( $fa->post_content );
				$f_featured = has_tag( 'featured', $fid );
				$f_video   = sf_cats_include_video( $f_cats );
				$f_embed   = $f_video ? sf_video_embed_url( $fa->post_content ) : '';
				if ( empty( $f_thumb ) && $f_video ) {
					$f_vthumb = sf_video_thumbnail_url( $fa->post_content );
					if ( $f_vthumb ) $f_thumb = '<img src="' . esc_url( $f_vthumb ) . '" alt="' . esc_attr( $f_title ) . '" loading="lazy">';
				}
				?>
				<a href="<?php echo $f_video ? esc_url( $f_embed ) : esc_url( $f_link ); ?>"
				   class="blog-featured__main card-animate"
				   style="animation-delay: 0.05s"
				   <?php echo $f_video ? 'data-video-url="' . esc_attr( $f_embed ) . '"' : ''; ?>>
					<?php if ( $f_thumb ) : ?>
					<div class="blog-featured__main-image"><?php echo $f_thumb; ?></div>
					<?php endif; ?>
					<div class="blog-featured__main-body">
						<div class="blog-card__badges">
							<?php if ( $f_cat_name ) : ?>
							<span class="sf-badge sf-badge--<?php echo esc_attr( $f_cat_slug ); ?>"><?php echo esc_html( $f_cat_name ); ?></span>
							<?php endif; ?>
							<?php if ( $f_featured ) : ?>
							<span class="sf-badge sf-badge--featured">Featured</span>
							<?php endif; ?>
						</div>
						<p class="blog-card__meta"><?php echo esc_html( $f_date ); ?> &middot; <?php echo esc_html( $f_author ); ?> &middot; <?php echo esc_html( $f_read ); ?></p>
						<h2 class="blog-featured__title"><?php echo esc_html( $f_title ); ?></h2>
						<?php if ( $f_excerpt ) : ?>
						<p class="blog-featured__excerpt"><?php echo esc_html( $f_excerpt ); ?></p>
						<?php endif; ?>
						<span class="blog-card__link"><?php echo $f_video ? 'Watch Video' : sf_post_cta( $f_cat_slug ); ?></span>
					</div>
				</a>

				<div class="blog-featured__secondary">
					<?php for ( $si = 1; $si <= min( 2, count( $articles ) - 1 ); $si++ ) :
						$sa        = $articles[ $si ];
						$sid       = $sa->ID;
						$s_cats    = get_the_category( $sid );
						$s_cat_name = $s_cats ? $s_cats[0]->cat_name : '';
						$s_cat_slug = $s_cats ? $s_cats[0]->category_nicename : '';
						$s_thumb   = get_the_post_thumbnail( $sid, 'medium' );
						$s_link    = get_permalink( $sid );
						$s_date    = get_the_date( 'M j, Y', $sid );
						$s_title   = get_the_title( $sid );
						$s_author  = get_the_author_meta( 'display_name', $sa->post_author );
						$s_read    = sf_read_time( $sa->post_content );
						$s_featured = has_tag( 'featured', $sid );
						$s_video   = sf_cats_include_video( $s_cats );
						$s_embed   = $s_video ? sf_video_embed_url( $sa->post_content ) : '';
						if ( empty( $s_thumb ) && $s_video ) {
							$s_vthumb = sf_video_thumbnail_url( $sa->post_content );
							if ( $s_vthumb ) $s_thumb = '<img src="' . esc_url( $s_vthumb ) . '" alt="' . esc_attr( $s_title ) . '" loading="lazy">';
						}
					?>
					<a href="<?php echo $s_video ? esc_url( $s_embed ) : esc_url( $s_link ); ?>"
					   class="blog-featured__side card-animate<?php echo $s_thumb ? '' : ' no-image'; ?>"
					   style="animation-delay: <?php echo 0.1 + ($si * 0.08); ?>s"
					   <?php echo $s_video ? 'data-video-url="' . esc_attr( $s_embed ) . '"' : ''; ?>>
						<?php if ( $s_thumb ) : ?>
						<div class="blog-featured__side-image"><?php echo $s_thumb; ?></div>
						<?php endif; ?>
						<div class="blog-featured__side-body">
							<div class="blog-card__badges">
								<?php if ( $s_cat_name ) : ?>
								<span class="sf-badge sf-badge--<?php echo esc_attr( $s_cat_slug ); ?>"><?php echo esc_html( $s_cat_name ); ?></span>
								<?php endif; ?>
								<?php if ( $s_featured ) : ?>
								<span class="sf-badge sf-badge--featured">Featured</span>
								<?php endif; ?>
							</div>
							<p class="blog-card__meta"><?php echo esc_html( $s_date ); ?> &middot; <?php echo esc_html( $s_author ); ?> &middot; <?php echo esc_html( $s_read ); ?></p>
							<h3 class="blog-featured__side-title"><?php echo esc_html( $s_title ); ?></h3>
							<span class="blog-card__link"><?php echo $s_video ? 'Watch Video' : sf_post_cta( $s_cat_slug ); ?></span>
						</div>
					</a>
					<?php endfor; ?>
				</div><!-- .blog-featured__secondary -->

			</div><!-- .blog-featured__grid -->

			<?php
			$sticky_posts = get_posts( [
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 6,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'tag'            => 'featured',
			] );
			if ( ! empty( $sticky_posts ) ) : ?>
			<div class="blog-sticky">
				<p class="blog-section-label">Featured Blog Posts</p>
				<div class="blog-sticky__grid">
					<?php foreach ( $sticky_posts as $i => $sp ) :
						$sp_id       = $sp->ID;
						$sp_cats     = get_the_category( $sp_id );
						$sp_cat_slug = $sp_cats ? $sp_cats[0]->category_nicename : '';
						$sp_cat_name = $sp_cats ? $sp_cats[0]->cat_name          : '';
						$sp_thumb    = get_the_post_thumbnail( $sp_id, 'thumbnail' );
						$sp_link     = get_permalink( $sp_id );
						$sp_date     = get_the_date( 'M j, Y', $sp_id );
						$sp_author   = get_the_author_meta( 'display_name', $sp->post_author );
						$sp_read     = sf_read_time( $sp->post_content );
						$sp_video    = sf_cats_include_video( $sp_cats );
						$sp_embed    = $sp_video ? sf_video_embed_url( $sp->post_content ) : '';
						if ( empty( $sp_thumb ) && $sp_video ) {
							$sp_vthumb = sf_video_thumbnail_url( $sp->post_content );
							if ( $sp_vthumb ) $sp_thumb = '<img src="' . esc_url( $sp_vthumb ) . '" alt="' . esc_attr( get_the_title( $sp_id ) ) . '" loading="lazy">';
						}
					?>
					<a href="<?php echo $sp_video ? esc_url( $sp_embed ) : esc_url( $sp_link ); ?>"
					   class="blog-sticky__card blog-card-animate"
					   style="animation-delay: <?php echo $i * 0.07; ?>s"
					   <?php echo $sp_video ? 'data-video-url="' . esc_attr( $sp_embed ) . '"' : ''; ?>>
						<?php if ( $sp_thumb ) : ?>
						<div class="blog-sticky__card-image"><?php echo $sp_thumb; ?></div>
						<?php endif; ?>
						<div class="blog-sticky__card-body">
							<div class="blog-card__badges">
								<?php if ( $sp_cat_name ) : ?><span class="sf-badge sf-badge--<?php echo esc_attr( $sp_cat_slug ); ?>"><?php echo esc_html( $sp_cat_name ); ?></span><?php endif; ?>
								<span class="sf-badge sf-badge--featured">Featured</span>
							</div>
							<p class="blog-sticky__card-meta"><?php echo esc_html( $sp_date ); ?> &middot; <?php echo esc_html( $sp_author ); ?> &middot; <?php echo esc_html( $sp_read ); ?></p>
							<h3 class="blog-sticky__card-title"><?php echo esc_html( get_the_title( $sp_id ) ); ?></h3>
							<span class="blog-sticky__card-link"><?php echo $sp_video ? 'Watch Video' : sf_post_cta( $sp_cat_slug ); ?></span>
						</div>
					</a>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>
	<!-- END FEATURED POSTS -->

	<!-- FILTER + ARTICLES -->
	<section class="blog-articles sf-section" id="articles">
		<div class="sf-container">

			<div class="blog-filter">
				<button type="button" class="blog-filter__btn active" data-filter="all">All Articles</button>
				<button type="button" class="blog-filter__btn" data-filter="success-stories">Success Stories</button>
				<button type="button" class="blog-filter__btn" data-filter="press">Press</button>
				<button type="button" class="blog-filter__btn" data-filter="videos">Videos</button>
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
					$is_video = sf_cats_include_video( $cats );
					$embed    = $is_video ? sf_video_embed_url( $content ) : '';
					if ( empty( $thumb ) && $is_video ) {
						$vthumb = sf_video_thumbnail_url( $content );
						if ( $vthumb ) $thumb = '<img src="' . esc_url( $vthumb ) . '" alt="' . esc_attr( $title ) . '" loading="lazy">';
					}
				?>
				<a href="<?php echo $is_video ? esc_url( $embed ) : esc_url( $link ); ?>"
				   class="sf-card blog-card blog-card-animate"
				   style="animation-delay: <?php echo ($card_i * 0.07); ?>s"
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

<script>
(function () {
  'use strict';

  var filterBtns    = document.querySelectorAll('.blog-filter__btn');
  var grid          = document.querySelector('.blog-grid');
  var loadMoreBtn   = document.getElementById('blog-load-more');
  var loadMoreLabel = loadMoreBtn ? loadMoreBtn.innerHTML : '';
  var currentPage   = 1;
  var currentCat    = 'all';
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

  function fetchPosts(page, category, replace) {
    if (isLoading || typeof salefishAjax === 'undefined') return;
    isLoading = true;
    if (loadMoreBtn) {
      loadMoreBtn.innerHTML = 'Loading…';
      loadMoreBtn.disabled = true;
    }

    var formData = new FormData();
    formData.append('action',   'load_more_post');
    formData.append('paged',    page);
    formData.append('category', category);
    // No nonce — the endpoint is read-only public data and the page HTML
    // is cache-able for a week (LSCache). A frozen-in-cache nonce would
    // expire after 12-24 h and break the button. See functions.php
    // load_more_post() for the full reasoning.

    fetch(salefishAjax.ajaxurl, { method: 'POST', body: formData })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        // Commit the page advance only on success — failed fetches
        // leave currentPage unchanged so the next click retries correctly.
        currentPage = page;
        if (replace) grid.innerHTML = '';
        (res.posts || []).forEach(function (post) {
          grid.appendChild(buildCard(post));
        });
        if (replace && (res.posts || []).length === 0) {
          grid.innerHTML = '<div class="blog-empty"><p>No articles yet in this category — check back soon.</p></div>';
        }
        if (loadMoreBtn) {
          var hasMore = page < res.max && !!res.max;
          loadMoreBtn.style.display = hasMore ? '' : 'none';
          loadMoreBtn.innerHTML = loadMoreLabel;
          loadMoreBtn.disabled = false;
        }
        isLoading = false;
      })
      .catch(function () {
        // Network / JSON error — reset gate so the user can retry.
        if (loadMoreBtn) {
          loadMoreBtn.innerHTML = loadMoreLabel;
          loadMoreBtn.disabled = false;
        }
        isLoading = false;
      });
    // Note: .finally() is intentionally avoided — it is not supported in
    // iOS Safari < 11.1 or Android Chrome < 63 and would permanently lock
    // isLoading = true on those devices, silently disabling the button.
  }

  // Filter buttons
  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var filter = this.getAttribute('data-filter');
      filterBtns.forEach(function (b) { b.classList.remove('active'); });
      this.classList.add('active');
      currentCat  = filter;
      currentPage = 0; // fetchPosts success will set it to 1
      fetchPosts(1, filter, true);
    });
  });

  // Load More — pass next page as argument; currentPage only advances
  // inside fetchPosts on success, so a failed request is always retryable.
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function () {
      fetchPosts(currentPage + 1, currentCat, false);
    });
  }

  // URL param filter on load
  window.addEventListener('load', function () {
    var urlParams   = new URLSearchParams(window.location.search);
    var filterParam = urlParams.get('filter');
    if (filterParam && filterParam !== 'all') {
      var targetBtn = document.querySelector('.blog-filter__btn[data-filter="' + filterParam + '"]');
      if (targetBtn) targetBtn.click();
    }
  });
}());
</script>

<!-- BLOG CTA STRIP -->
<section class="sp-cta">
	<div class="max_wrapper">
		<div class="sp-cta__inner">
			<h2>Stay Ahead of the Competition</h2>
			<p>Insights, success stories, and strategies — backed by the platform builders trust to sell more homes.</p>
			<button class="sp-cta__btn" data-sf-modal="register">Get a Demo</button>
		</div>
	</div>
</section>
<!-- END BLOG CTA STRIP -->

<?php get_footer(); ?>
