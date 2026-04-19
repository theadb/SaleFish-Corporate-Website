<?php
/**
 * Template Name: Newsroom Page
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

<main class="newsroom">

	<!-- HERO -->
	<section class="newsroom-hero sf-section">
		<div class="sf-container">
			<div class="newsroom-hero__inner">
				<div class="newsroom-hero__text" data-aos="fade-up">
					<span class="sf-badge sf-badge--ocean">Newsroom</span>
					<h1>Indisputable Excellence<br>from the SaleFish Newsroom</h1>
					<p>(Are you jealous yet?)</p>
				</div>
				<div class="newsroom-hero__image" data-aos="zoom-in" data-aos-delay="200">
					<img src="<?php bloginfo('template_directory'); ?>/img/newsroom/newsroom-new.png" alt="SaleFish Newsroom">
				</div>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- FILTER + ARTICLES -->
	<section class="newsroom-articles sf-section" id="articles">
		<div class="sf-container">
			<div class="newsroom-filter">
				<button class="newsroom-filter__btn active" data-filter="all">All</button>
				<button class="newsroom-filter__btn" data-filter="success-stories">Success Stories</button>
				<button class="newsroom-filter__btn" data-filter="press">Press</button>
				<button class="newsroom-filter__btn" data-filter="blog">Blog</button>
				<button class="newsroom-filter__btn" data-filter="videos">Videos</button>
			</div>

			<div class="newsroom-grid">
				<?php foreach ( $articles as $article ) :
					$id       = $article->ID;
					$title    = get_the_title($id);
					$thumb    = get_the_post_thumbnail($id, 'medium_large');
					$date     = get_the_date('F j, Y', $id);
					$link     = get_permalink($id);
					$cats     = get_the_category($id);
					$cat_name = $cats ? $cats[0]->cat_name : '';
					$cat_slug = $cats ? $cats[0]->category_nicename : '';
				?>
				<a href="<?php echo esc_url( $link ); ?>" class="sf-card newsroom-card" data-category="<?php echo esc_attr( $cat_slug ); ?>">
					<?php if ( $thumb ): ?>
					<div class="newsroom-card__image"><?php echo $thumb; ?></div>
					<?php endif; ?>
					<div class="newsroom-card__body">
						<?php if ( $cat_name ): ?>
						<span class="sf-badge sf-badge--<?php echo esc_attr( $cat_slug ); ?> newsroom-card__cat"><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
						<h3 class="newsroom-card__title"><?php echo esc_html( $title ); ?></h3>
						<p class="newsroom-card__date"><?php echo esc_html( $date ); ?></p>
					</div>
				</a>
				<?php endforeach; ?>
			</div>

			<div class="newsroom-loadmore">
				<button class="sf-btn sf-btn--secondary load_more" data-page="1" data-category="all">
					Load More
				</button>
			</div>
		</div>
	</section>
	<!-- END ARTICLES -->

</main>

<?php get_footer(); ?>
