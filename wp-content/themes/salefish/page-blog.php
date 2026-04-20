<?php
/**
 * Template Name: Blog Page
 * The template for displaying the blog page
 */
$articles = get_posts(
    array(
        'post_status' => 'publish',
        'post_type' => 'post',
        'posts_per_page' => 9,
        'paged' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
    )
);

get_header();
?>

<main class="blog">

	<!-- HERO -->
	<section class="hero">
		<div class="hero__inner" data-aos="fade-up" data-aos-delay="200">
			<h3>Insights, Stories & News From</h3>
			<h1>The SaleFish Blog</h1>
			<p>(Are you taking notes?)</p>
		</div>
	</section>
	<!-- END HERO -->

	<!-- FEATURED POSTS -->
	<?php if ( ! empty( $articles ) && count( $articles ) >= 2 ) : ?>
	<section class="featured">
		<div class="max_wrapper">
			<p class="section-label">Recent Blog Posts</p>
			<div class="featured-grid">

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
				   class="featured-main"
				   <?php echo $f_video ? 'data-fancybox' : ''; ?>>
					<?php if ( $f_thumb ) : ?>
					<div class="featured-main__image"><?php echo $f_thumb; ?></div>
					<?php endif; ?>
					<div class="featured-main__body">
						<?php if ( $f_cat_name ) : ?>
						<span class="cat-badge <?php echo esc_attr( $f_cat_slug ); ?>"><?php echo esc_html( $f_cat_name ); ?></span>
						<?php endif; ?>
						<span class="post-date">Published: <?php echo esc_html( $f_date ); ?></span>
						<h2 class="post-title"><?php echo esc_html( $f_title ); ?></h2>
						<?php if ( $f_excerpt ) : ?>
						<p class="post-excerpt"><?php echo esc_html( $f_excerpt ); ?></p>
						<?php endif; ?>
						<span class="read-more"><?php echo $f_video ? 'Watch Video' : 'Read More'; ?></span>
					</div>
				</a>

				<div class="featured-secondary">
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
					   class="featured-side<?php echo $s_thumb ? '' : ' no-image'; ?>"
					   <?php echo $s_video ? 'data-fancybox' : ''; ?>>
						<?php if ( $s_thumb ) : ?>
						<div class="featured-side__image"><?php echo $s_thumb; ?></div>
						<?php endif; ?>
						<div class="featured-side__body">
							<?php if ( $s_cat_name ) : ?>
							<span class="cat-badge <?php echo esc_attr( $s_cat_slug ); ?>"><?php echo esc_html( $s_cat_name ); ?></span>
							<?php endif; ?>
							<span class="post-date">Published: <?php echo esc_html( $s_date ); ?></span>
							<h3 class="post-title"><?php echo esc_html( $s_title ); ?></h3>
							<span class="read-more"><?php echo $s_video ? 'Watch Video' : 'Read More'; ?></span>
						</div>
					</a>
					<?php endfor; ?>
				</div><!-- .featured-secondary -->

			</div><!-- .featured-grid -->
		</div><!-- .max_wrapper -->
	</section>
	<?php endif; ?>
	<!-- END FEATURED POSTS -->

	<!-- ARTICLES -->
	<section class="articles" id="articles">
		<div class="max_wrapper">

			<div class="filter-tabs">
				<button class="filter-tab active" data-filter="all">All Articles</button>
				<button class="filter-tab" data-filter="success-stories">Success Stories</button>
				<button class="filter-tab" data-filter="press">Press</button>
				<button class="filter-tab" data-filter="blog">Blog</button>
				<button class="filter-tab" data-filter="videos">Videos</button>
			</div>

			<div class="items blog_articles">
				<?php foreach ( $articles as $article ) :
					$id       = $article->ID;
					$cat      = get_the_category( $id );
					$cat_name = $cat ? $cat[0]->cat_name : '';
					$cat_slug = $cat ? $cat[0]->category_nicename : '';
					$thumb    = get_the_post_thumbnail( $id, 'medium_large' );
					$link     = get_permalink( $id );
					$content  = $article->post_content;
					$date     = get_the_date( 'M j, Y', $id );
					$is_video = $cat_slug === 'videos';
				?>
				<a href="<?php echo $is_video ? esc_url( $content ) : esc_url( $link ); ?>"
				   class="item <?php echo esc_attr( $cat_slug ); ?> all"
				   data-category="<?php echo esc_attr( $cat_slug ); ?>"
				   <?php echo $is_video ? 'data-fancybox' : ''; ?>>
					<?php if ( $thumb ) : ?>
					<div class="img_container"><?php echo $thumb; ?></div>
					<?php endif; ?>
					<div class="item-body">
						<?php if ( $cat_name ) : ?>
						<span class="cat-badge <?php echo esc_attr( $cat_slug ); ?>"><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
						<span class="post-date">Published: <?php echo esc_html( $date ); ?></span>
						<h3 class="post-title"><?php echo esc_html( limit_text( $article->post_title, 10 ) ); ?></h3>
						<span class="read-more"><?php echo $is_video ? 'Watch Video' : 'Read More'; ?></span>
					</div>
				</a>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
			</div><!-- .items -->

			<div class="btn__wrapper">
				<a href="#!" class="btn btn__primary" id="load-more">
					<span>Load More</span>
					<i class="ri-arrow-down-s-line"></i>
				</a>
			</div>

		</div><!-- .max_wrapper -->
	</section>
	<!-- END ARTICLES -->

	<!-- CONTACT -->
	<section class="contact">
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<div class="top">
			<div class="top_content_center" data-aos="fade-up">
				<h2>
					"Builders, developers, and sales teams don't want to be sold
					to. But the SaleFish experience speaks for itself. Their job
					has never been easier."
				</h2>
				<p>
					RICK HAWS <br />
					PRESIDENT & CO-FOUNDER
				</p>
			</div>
			<a class="button" target="_blank"
				href="https://meetings.hubspot.com/leck?uuid=230963f4-62bf-47dc-99a4-264de6749b7b">BOOK A FREE DEMO</a>
		</div>
		<?php get_template_part( '/partials/contact-general' ); ?>
	</section>
	<!-- END CONTACT -->

</main>

<?php get_footer(); ?>
