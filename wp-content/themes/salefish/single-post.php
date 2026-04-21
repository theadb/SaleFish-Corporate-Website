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
$author_fname = get_the_author_meta( 'first_name', $author_id );
$author_lname = get_the_author_meta( 'last_name', $author_id );
$author_name  = trim( "$author_fname $author_lname" );
$author_role  = get_the_author_meta( 'description', $author_id );
$initials     = strtoupper( substr( $author_fname ?: 'A', 0, 1 ) . substr( $author_lname ?: '', 0, 1 ) );

// Reading time (avg 200 wpm)
$word_count   = str_word_count( strip_tags( $content ) );
$reading_time = max( 1, (int) ceil( $word_count / 200 ) );

// Share data
$share_url    = urlencode( get_permalink() );
$share_title  = urlencode( $title );
$share_desc   = urlencode( wp_trim_words( get_the_excerpt(), 20, '...' ) );

// Featured posts — tagged "featured", exclude current post
$featured_posts = get_posts( [
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 6,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'tag'            => 'featured',
	'exclude'        => [ $id ],
] );

get_header();
?>

<main class="single_post">
<article>

	<!-- TOP: meta + title -->
	<div class="sp-top">
		<div class="max_wrapper">
			<a class="sp-back" href="/blog">← Back to the Blog</a>
			<h1 class="sp-title"><?php echo esc_html( $title ); ?></h1>
			<div class="sp-meta-bar">
				<span class="sp-meta-bar__date"><?php echo esc_html( $date ); ?></span>
				<span class="sp-meta-bar__sep">—</span>
				<span class="sp-meta-bar__read"><?php echo $reading_time; ?> min read</span>
				<?php if ( $category ) : ?>
				<span class="sf-badge sf-badge--<?php echo esc_attr( $category[0]->category_nicename ); ?>"><?php echo esc_html( $category[0]->cat_name ); ?></span>
				<?php endif; ?>
				<?php $tags = get_the_tags( $id ); if ( $tags ) : foreach ( $tags as $tag ) :
					$tag_class = $tag->slug === 'featured' ? 'sf-badge--featured' : 'sf-badge--tag';
				?>
				<span class="sf-badge <?php echo esc_attr( $tag_class ); ?>"><?php echo esc_html( $tag->name ); ?></span>
				<?php endforeach; endif; ?>
			</div>
		</div>
	</div>

	<!-- HERO IMAGE -->
	<?php if ( $thumb ) : ?>
	<div class="sp-hero-wrap">
		<div class="max_wrapper">
			<div class="sp-hero"><?php echo $thumb; ?></div>
		</div>
	</div>
	<?php endif; ?>

	<!-- BODY: sidebar + content -->
	<div class="sp-body-wrap">
		<div class="max_wrapper">
			<div class="sp-body">

				<!-- SIDEBAR -->
				<aside class="sp-sidebar">

					<?php if ( $author_name ) : ?>
					<div class="sp-author">
						<p class="sp-sidebar__label">Written By</p>
						<div class="sp-author__card">
							<div class="sp-author__initials"><?php echo esc_html( $initials ); ?></div>
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

							<div class="sp-share__icons">

								<a class="sp-share__icon sp-share__icon--email"
								   href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_title; ?>%20-%20<?php echo $share_url; ?>"
								   title="Share via Email">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
								</a>

								<a class="sp-share__icon sp-share__icon--sms"
								   href="sms:?&body=<?php echo $share_title; ?>%20<?php echo $share_url; ?>"
								   title="Share via SMS">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
								</a>

								<a class="sp-share__icon sp-share__icon--whatsapp"
								   href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>"
								   target="_blank" rel="noopener" title="Share on WhatsApp">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
								</a>

								<a class="sp-share__icon sp-share__icon--facebook"
								   href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>&quote=<?php echo $share_title; ?>"
								   target="_blank" rel="noopener" title="Share on Facebook">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
								</a>

								<a class="sp-share__icon sp-share__icon--x"
								   href="https://x.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>"
								   target="_blank" rel="noopener" title="Share on X">
									<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.74-8.867L1.254 2.25H8.08l4.259 5.63zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
								</a>

								<a class="sp-share__icon sp-share__icon--linkedin"
								   href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>&summary=<?php echo $share_desc; ?>"
								   target="_blank" rel="noopener" title="Share on LinkedIn">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
								</a>

							</div>
						</div>
					</div>

				</aside><!-- .sp-sidebar -->

				<!-- ARTICLE CONTENT -->
				<div class="sp-content">
					<?php echo wp_kses_post( apply_filters( 'the_content', $content ) ); ?>
				</div>

			</div><!-- .sp-body -->
		</div><!-- .max_wrapper -->
	</div><!-- .sp-body-wrap -->

	<!-- FEATURED BLOG POSTS -->
	<?php if ( ! empty( $featured_posts ) ) : ?>
	<div class="sp-featured-wrap">
		<div class="max_wrapper">
			<div class="blog-sticky">
				<p class="blog-section-label">Featured Blog Posts</p>
				<div class="blog-sticky__grid">
					<?php foreach ( $featured_posts as $i => $sp ) :
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
					<a href="<?php echo $sp_video ? esc_url( sf_youtube_embed_url( $sp->post_content ) ) : esc_url( $sp_link ); ?>"
					   class="blog-sticky__card blog-card-animate"
					   style="animation-delay: <?php echo $i * 0.07; ?>s"
					   <?php echo $sp_video ? 'data-fancybox data-type="iframe"' : ''; ?>>
						<?php if ( $sp_thumb ) : ?>
						<div class="blog-sticky__card-image"><?php echo $sp_thumb; ?></div>
						<?php endif; ?>
						<div class="blog-sticky__card-body">
							<div class="blog-card__badges">
								<?php if ( $sp_cat_name ) : ?><span class="sf-badge sf-badge--<?php echo esc_attr( $sp_cat_slug ); ?>"><?php echo esc_html( $sp_cat_name ); ?></span><?php endif; ?>
								<span class="sf-badge sf-badge--featured">Featured</span>
							</div>
							<h3 class="blog-sticky__card-title"><?php echo esc_html( get_the_title( $sp_id ) ); ?></h3>
							<p class="blog-sticky__card-meta"><?php echo esc_html( $sp_date ); ?> &middot; <?php echo esc_html( $sp_author ); ?></p>
							<span class="blog-sticky__card-link"><?php echo $sp_video ? 'Watch Video' : 'Read More'; ?></span>
						</div>
					</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

</article>
</main>

<script>
(function () {
	var btn = document.querySelector('[data-copy-link]');
	if (!btn) return;
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
}());
</script>

<?php get_footer(); ?>
