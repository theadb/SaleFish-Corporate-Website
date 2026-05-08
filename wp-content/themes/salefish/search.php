<?php
/**
 * Search results template.
 * Matches the blog filter page visual design.
 */

$search_query = get_search_query();

$articles = [];
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$articles[] = get_post();
	}
}

get_header();
?>

<main class="blog">

	<!-- HERO -->
	<section class="blog-hero sf-section">
		<div class="sf-container">
			<div class="blog-hero__inner">
				<span class="blog-hero__eyebrow">The SaleFish Blog</span>
				<h1>Search Results<?php if ( $search_query ) : ?>: <em><?php echo esc_html( $search_query ); ?></em><?php endif; ?></h1>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- RESULTS -->
	<section class="blog-articles sf-section" id="articles">
		<div class="sf-container">

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
			<p class="blog-no-results">No results found for &ldquo;<?php echo esc_html( $search_query ); ?>&rdquo;. Try a different search term.</p>
			<?php endif; ?>

		</div>
	</section>
	<!-- END RESULTS -->

</main>

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
