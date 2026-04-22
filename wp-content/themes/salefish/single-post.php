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
			<a class="sp-back" href="/blog"><i data-lucide="arrow-left" width="16" height="16"></i> Back to the Blog</a>
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
								<i data-lucide="copy" width="13" height="13"></i>
								<span>Copy link</span>
							</button>

							<div class="sp-share__icons">

								<a class="sp-share__icon sp-share__icon--email"
								   href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_title; ?>%20-%20<?php echo $share_url; ?>"
								   title="Share via Email">
									<i data-lucide="mail" width="16" height="16"></i>
								</a>

								<a class="sp-share__icon sp-share__icon--sms"
								   href="sms:?&body=<?php echo $share_title; ?>%20<?php echo $share_url; ?>"
								   title="Share via SMS">
									<i data-lucide="message-square" width="16" height="16"></i>
								</a>

								<a class="sp-share__icon sp-share__icon--whatsapp"
								   href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>"
								   target="_blank" rel="noopener" title="Share on WhatsApp">
									<i data-lucide="message-circle" width="16" height="16"></i>
								</a>

								<a class="sp-share__icon sp-share__icon--facebook"
								   href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>&quote=<?php echo $share_title; ?>"
								   target="_blank" rel="noopener" title="Share on Facebook">
									<i data-lucide="facebook" width="16" height="16"></i>
								</a>

								<a class="sp-share__icon sp-share__icon--x"
								   href="https://x.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>"
								   target="_blank" rel="noopener" title="Share on X">
									<i data-lucide="twitter" width="15" height="15"></i>
								</a>

								<a class="sp-share__icon sp-share__icon--linkedin"
								   href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>&summary=<?php echo $share_desc; ?>"
								   target="_blank" rel="noopener" title="Share on LinkedIn">
									<i data-lucide="linkedin" width="16" height="16"></i>
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
