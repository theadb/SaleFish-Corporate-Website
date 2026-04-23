<?php
/**
 * Template Name: Post Page
 */
$id           = get_the_ID();
$title        = get_the_title();
$content      = get_the_content();
$category     = get_the_category( $id );
$thumb        = get_the_post_thumbnail( $id, 'large' );
$date         = get_the_date( 'M j, Y' );
$author_id    = get_post_field( 'post_author', $id );
$author_name  = get_the_author_meta( 'display_name', $author_id );
$author_role  = get_the_author_meta( 'description', $author_id );
$author_avatar = get_avatar( $author_id, 56 );

// Reading time (avg 200 wpm)
$word_count   = str_word_count( strip_tags( $content ) );
$reading_time = max( 1, (int) ceil( $word_count / 200 ) );

// Related post — same category, exclude current
$cat_id  = $category ? $category[0]->term_id : 0;
$related = $cat_id ? get_posts( [
	'post_status'    => 'publish',
	'post_type'      => 'post',
	'posts_per_page' => 1,
	'category__in'   => [ $cat_id ],
	'exclude'        => [ $id ],
	'orderby'        => 'date',
	'order'          => 'DESC',
] ) : [];

get_header();
?>

<main class="single-post">
<article>

	<!-- TOP: meta + title -->
	<div class="sp-top">
		<div class="sf-container">
			<a class="sp-back" href="/blog">← Back to the Blog</a>
			<div class="sp-meta-bar">
				<span class="sp-meta-bar__date"><?php echo esc_html( $date ); ?></span>
				<span class="sp-meta-bar__sep">—</span>
				<span class="sp-meta-bar__read"><?php echo $reading_time; ?> min read</span>
				<?php if ( $category ) : ?>
				<span class="sf-badge sf-badge--<?php echo esc_attr( $category[0]->category_nicename ); ?>">
					<?php echo esc_html( $category[0]->cat_name ); ?>
				</span>
				<?php endif; ?>
			</div>
			<h1 class="sp-title"><?php echo esc_html( $title ); ?></h1>
		</div>
	</div>

	<!-- HERO IMAGE -->
	<?php if ( $thumb ) : ?>
	<div class="sp-hero-wrap">
		<div class="sf-container">
			<div class="sp-hero"><?php echo $thumb; ?></div>
		</div>
	</div>
	<?php endif; ?>

	<!-- BODY: sidebar + content -->
	<div class="sp-body-wrap">
		<div class="sf-container">
			<div class="sp-body">

				<!-- SIDEBAR -->
				<aside class="sp-sidebar">

					<?php if ( $author_name ) : ?>
					<div class="sp-author">
						<p class="sp-sidebar__label">Written By</p>
						<div class="sp-author__card">
							<?php if ( $author_avatar ) : ?>
							<div class="sp-author__avatar"><?php echo $author_avatar; ?></div>
							<?php endif; ?>
							<div class="sp-author__info">
								<p class="sp-author__name"><?php echo esc_html( $author_name ); ?></p>
								<?php if ( $author_role ) : ?>
								<p class="sp-author__role"><?php echo esc_html( $author_role ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<div class="sp-share">
						<p class="sp-sidebar__label">Share This</p>
						<div class="sp-share__btns">
							<button class="sp-share__btn sp-share__btn--copy" data-copy-link>
								<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
								<span>Copy link</span>
							</button>
							<a class="sp-share__btn" href="https://x.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( $title ); ?>" target="_blank" rel="noopener" title="Share on X">
								<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.74-8.867L1.254 2.25H8.08l4.259 5.63zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
							</a>
							<a class="sp-share__btn" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" title="Share on Facebook">
								<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
							</a>
							<a class="sp-share__btn" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( $title ); ?>" target="_blank" rel="noopener" title="Share on LinkedIn">
								<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
							</a>
						</div>
					</div>

					<?php if ( ! empty( $related ) ) :
						$rel       = $related[0];
						$rel_id    = $rel->ID;
						$rel_cats  = get_the_category( $rel_id );
						$rel_slug  = $rel_cats ? $rel_cats[0]->category_nicename : '';
						$rel_name  = $rel_cats ? $rel_cats[0]->cat_name : '';
						$rel_thumb = get_the_post_thumbnail( $rel_id, 'medium' );
						$rel_link  = get_permalink( $rel_id );
						$rel_date  = get_the_date( 'M j, Y', $rel_id );
						$rel_title = get_the_title( $rel_id );
						$rel_read  = max( 1, (int) ceil( str_word_count( strip_tags( $rel->post_content ) ) / 200 ) );
					?>
					<a class="sp-related sf-card" href="<?php echo esc_url( $rel_link ); ?>">
						<?php if ( $rel_thumb ) : ?>
						<div class="sp-related__image"><?php echo $rel_thumb; ?></div>
						<?php endif; ?>
						<div class="sp-related__body">
							<?php if ( $rel_slug ) : ?>
							<span class="sf-badge sf-badge--<?php echo esc_attr( $rel_slug ); ?>"><?php echo esc_html( $rel_name ); ?></span>
							<?php endif; ?>
							<p class="sp-related__meta"><?php echo esc_html( $rel_date ); ?> — <span><?php echo $rel_read; ?> min read</span></p>
							<h3 class="sp-related__title"><?php echo esc_html( $rel_title ); ?></h3>
							<span class="blog-read-more"><?php echo sf_post_cta( $rel_slug ); ?></span>
						</div>
					</a>
					<?php endif; ?>

				</aside><!-- .sp-sidebar -->

				<!-- ARTICLE CONTENT -->
				<div class="sf-prose sp-content">
					<?php echo wp_kses_post( apply_filters( 'the_content', $content ) ); ?>
				</div>

			</div><!-- .sp-body -->
		</div><!-- .sf-container -->
	</div><!-- .sp-body-wrap -->

</article>
</main>

<?php
// Category-aware CTA strip
$post_cat_slug  = isset( $category[0] ) ? $category[0]->category_nicename : '';
$is_success     = $post_cat_slug === 'success-stories';
$cta_heading    = $is_success
    ? 'Ready to See Results Like These?'
    : 'See SaleFish in Action';
$cta_subtext    = $is_success
    ? 'Join the builders and developers who are outselling the competition with SaleFish.'
    : 'The all-in-one platform built for new home sales teams who expect to win.';
$cta_label      = $is_success ? 'Get My Demo' : 'Get a Demo';
?>

<!-- POST CTA STRIP -->
<section class="sp-cta sf-gradient-primary sf-section">
	<div class="sf-container">
		<div class="sp-cta__inner">
			<h2><?php echo esc_html( $cta_heading ); ?></h2>
			<p><?php echo esc_html( $cta_subtext ); ?></p>
			<button class="sf-btn sp-cta__btn" data-open-form-popup>
				<?php echo esc_html( $cta_label ); ?>
			</button>
		</div>
	</div>
</section>
<!-- END POST CTA STRIP -->

<!-- FORM POPUP -->
<div class="sp-form-popup" id="sp-form-popup" role="dialog" aria-modal="true" aria-label="Get a Demo">
	<div class="sp-form-popup__overlay"></div>
	<div class="sp-form-popup__card">
		<button class="sp-form-popup__close" aria-label="Close">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M18 6 6 18M6 6l12 12"/>
			</svg>
		</button>
		<div class="home-contact">
			<?php get_template_part('/partials/contact-general'); ?>
		</div>
	</div>
</div>
<!-- END FORM POPUP -->

<script>
(function () {
	// Copy link button
	var btn = document.querySelector('[data-copy-link]');
	if (btn) {
		btn.addEventListener('click', function () {
			navigator.clipboard.writeText(window.location.href).then(function () {
				btn.classList.add('copied');
				btn.querySelector('span').textContent = 'Copied!';
				setTimeout(function () {
					btn.classList.remove('copied');
					btn.querySelector('span').textContent = 'Copy link';
				}, 2000);
			});
		});
	}

	// Form popup
	(function () {
		var popup   = document.getElementById('sp-form-popup');
		if (!popup) return;
		var overlay  = popup.querySelector('.sp-form-popup__overlay');
		var closeBtn = popup.querySelector('.sp-form-popup__close');
		var triggers = document.querySelectorAll('[data-open-form-popup]');

		function openPopup() {
			popup.classList.add('active');
			document.body.style.overflow = 'hidden';
		}
		function closePopup() {
			popup.classList.remove('active');
			document.body.style.overflow = '';
		}

		triggers.forEach(function (el) { el.addEventListener('click', openPopup); });
		if (overlay)  overlay.addEventListener('click', closePopup);
		if (closeBtn) closeBtn.addEventListener('click', closePopup);
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') closePopup();
		});
	}());
}());
</script>

<?php get_footer(); ?>
