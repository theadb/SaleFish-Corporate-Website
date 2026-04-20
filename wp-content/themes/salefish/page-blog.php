<?php
/**
 * Template Name: Blog Page
 */
$articles = get_posts(array(
    'post_status'    => 'publish',
    'post_type'      => 'post',
    'posts_per_page' => 9,
    'paged'          => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
));

get_header();
?>

<main class="blog">

	<!-- HERO -->
	<section class="blog-hero sf-section">
		<div class="sf-container">
			<div class="blog-hero__inner" data-aos="fade-up">
				<span class="blog-hero__eyebrow">Insights, Stories & News From</span>
				<h1>The SaleFish Blog</h1>
				<p>(Are you taking notes?)</p>
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
				$f_video   = $f_cat_slug === 'videos';
				?>
				<a href="<?php echo $f_video ? esc_url( $fa->post_content ) : esc_url( $f_link ); ?>"
				   class="blog-featured__main"
				   <?php echo $f_video ? 'data-fancybox' : ''; ?>>
					<?php if ( $f_thumb ) : ?>
					<div class="blog-featured__main-image"><?php echo $f_thumb; ?></div>
					<?php endif; ?>
					<div class="blog-featured__main-body">
						<?php if ( $f_cat_name ) : ?>
						<span class="sf-badge sf-badge--<?php echo esc_attr( $f_cat_slug ); ?>"><?php echo esc_html( $f_cat_name ); ?></span>
						<?php endif; ?>
						<span class="blog-post-date">Published: <?php echo esc_html( $f_date ); ?></span>
						<h2 class="blog-featured__title"><?php echo esc_html( $f_title ); ?></h2>
						<?php if ( $f_excerpt ) : ?>
						<p class="blog-featured__excerpt"><?php echo esc_html( $f_excerpt ); ?></p>
						<?php endif; ?>
						<span class="blog-read-more"><?php echo $f_video ? 'Watch Video' : 'Read More'; ?></span>
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
						$s_video   = $s_cat_slug === 'videos';
					?>
					<a href="<?php echo $s_video ? esc_url( $sa->post_content ) : esc_url( $s_link ); ?>"
					   class="blog-featured__side<?php echo $s_thumb ? '' : ' no-image'; ?>"
					   <?php echo $s_video ? 'data-fancybox' : ''; ?>>
						<?php if ( $s_thumb ) : ?>
						<div class="blog-featured__side-image"><?php echo $s_thumb; ?></div>
						<?php endif; ?>
						<div class="blog-featured__side-body">
							<?php if ( $s_cat_name ) : ?>
							<span class="sf-badge sf-badge--<?php echo esc_attr( $s_cat_slug ); ?>"><?php echo esc_html( $s_cat_name ); ?></span>
							<?php endif; ?>
							<span class="blog-post-date">Published: <?php echo esc_html( $s_date ); ?></span>
							<h3 class="blog-featured__side-title"><?php echo esc_html( $s_title ); ?></h3>
							<span class="blog-read-more"><?php echo $s_video ? 'Watch Video' : 'Read More'; ?></span>
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
						$sp_video    = $sp_cat_slug === 'videos';
					?>
					<a href="<?php echo $sp_video ? esc_url( $sp->post_content ) : esc_url( $sp_link ); ?>"
					   class="blog-sticky__card blog-card-animate"
					   style="animation-delay: <?php echo $i * 0.07; ?>s"
					   <?php echo $sp_video ? 'data-fancybox' : ''; ?>>
						<?php if ( $sp_thumb ) : ?>
						<div class="blog-sticky__card-image"><?php echo $sp_thumb; ?></div>
						<?php endif; ?>
						<div class="blog-sticky__card-body">
							<?php if ( $sp_cat_name ) : ?>
							<span class="sf-badge sf-badge--<?php echo esc_attr( $sp_cat_slug ); ?>"><?php echo esc_html( $sp_cat_name ); ?></span>
							<?php endif; ?>
							<h3 class="blog-sticky__card-title"><?php echo esc_html( get_the_title( $sp_id ) ); ?></h3>
							<p class="blog-sticky__card-meta"><?php echo esc_html( $sp_date ); ?> &middot; <?php echo esc_html( $sp_author ); ?></p>
							<span class="blog-sticky__card-link"><?php echo $sp_video ? 'Watch Video' : 'Read More'; ?></span>
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
				<button class="blog-filter__btn active" data-filter="all">All Articles</button>
				<button class="blog-filter__btn" data-filter="success-stories">Success Stories</button>
				<button class="blog-filter__btn" data-filter="press">Press</button>
				<button class="blog-filter__btn" data-filter="videos">Videos</button>
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
				<a href="<?php echo $is_video ? esc_url( $content ) : esc_url( $link ); ?>"
				   class="sf-card blog-card blog-card-animate"
				   style="animation-delay: <?php echo ($card_i * 0.07); ?>s"
				   data-category="<?php echo esc_attr( $cat_slug ); ?>"
				   <?php echo $is_video ? 'data-fancybox' : ''; ?>>
					<?php if ( $thumb ) : ?>
					<div class="blog-card__image"><?php echo $thumb; ?></div>
					<?php endif; ?>
					<div class="blog-card__body">
						<?php if ( $featured ) : ?><span class="sf-badge sf-badge--featured">Featured</span><?php endif; ?>
						<?php if ( $cat_name ) : ?>
						<span class="sf-badge sf-badge--<?php echo esc_attr( $cat_slug ); ?> blog-card__cat"><?php echo esc_html( $cat_name ); ?></span>
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
				<button class="sf-btn sf-btn--secondary load_more" id="blog-load-more" data-page="1" data-category="all">
					Load More
				</button>
			</div>

		</div>
	</section>
	<!-- END ARTICLES -->

</main>

<script>
(function () {
  'use strict';

  var filterBtns  = document.querySelectorAll('.blog-filter__btn');
  var grid        = document.querySelector('.blog-grid');
  var loadMoreBtn = document.getElementById('blog-load-more');
  var currentPage = 1;
  var currentCat  = 'all';
  var isLoading   = false;

  function buildCard(post) {
    var cat_slug = post.cat_slug || '';
    var cat_name = post.cat_name || '';
    var is_video = cat_slug === 'videos';
    var card     = document.createElement('a');
    card.href      = is_video ? (post.content || post.link || '#') : (post.link || '#');
    card.className = 'sf-card blog-card';
    card.setAttribute('data-category', cat_slug);
    if (is_video) card.setAttribute('data-fancybox', '');
    card.innerHTML =
      (post.thumb ? '<div class="blog-card__image">' + post.thumb + '</div>' : '') +
      '<div class="blog-card__body">' +
        (post.is_featured ? '<span class="sf-badge sf-badge--featured">Featured</span>' : '') +
        (cat_name ? '<span class="sf-badge sf-badge--' + cat_slug + ' blog-card__cat">' + cat_name + '</span>' : '') +
        (post.date ? '<span class="blog-card__date">Published: ' + post.date + '</span>' : '') +
        (post.author ? '<span class="blog-card__author">By ' + post.author + '</span>' : '') +
        '<h3 class="blog-card__title">' + post.title + '</h3>' +
        '<span class="blog-card__link">' + (is_video ? 'Watch Video' : 'Read More') + '</span>' +
      '</div>';
    return card;
  }

  function fetchPosts(page, category, replace) {
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
        if (replace) grid.innerHTML = '';
        (res.posts || []).forEach(function (post) {
          grid.appendChild(buildCard(post));
        });
        if (loadMoreBtn) {
          loadMoreBtn.style.display = (page >= res.max || !res.max) ? 'none' : '';
        }
      })
      .catch(function () {})
      .finally(function () { isLoading = false; });
  }

  // Filter buttons
  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var filter = this.getAttribute('data-filter');
      filterBtns.forEach(function (b) { b.classList.remove('active'); });
      this.classList.add('active');
      currentCat  = filter;
      currentPage = 1;
      fetchPosts(1, filter, true);
    });
  });

  // Load More
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function () {
      currentPage++;
      fetchPosts(currentPage, currentCat, false);
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

<?php get_footer(); ?>
