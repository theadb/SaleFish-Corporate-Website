<?php
/**
 * Template Name: Newsroom Page
 * The template for displaying the newsroom page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
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

// print_r($articles);

get_header();
?>





<main class="newsroom">
	<!-- HERO -->
	<section class="hero">
		<div class="wrapper">
			<div class="left" data-aos="fade-right" data-aos-delay="300">
				<h3>INDISPUTABLE EXCELLENCE FROM</h3>
				<h1>THE SALEFISH</h1>
				<h1>NEWSROOM</h1>
				</br>
				<p>(Are you jealous yet?)</p>
			</div>
			<div class="right" data-aos="zoom-in" data-aos-delay="300">
				<img class="salefish_demo"
					src="<?php bloginfo('template_directory'); ?>/img/newsroom/newsroom-new.png"
					alt="Salefish App Demo">
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- ARTICLES -->
	<section class="articles" id="articles">
		<div class="filter">
			<div class="cut"></div>
			<div class="max_wrapper">
				<div class="wrap">
					<h3>
						YOU CAN FILTER THE POSTS BELOW:
					</h3>
					<div class="ui dropdown">
						<input type="hidden" name="gender">
						<div class="default text">All Articles</div>
						<i class="dropdown icon"></i>
						<div class="menu">
							<div class="item all" data-value="all">All Articles</div>
							<div class="item stories" data-value="success-stories">Success Stories</div>
							<div class="item press" data-value="press">Press</div>
							<div class="item blog" data-value="blog">Blog</div>
							<div class="item videos" data-value="videos">Videos</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="article_items">
			<div class="cut"></div>
			<div class="max_wrapper">
				<div class="items newsroom_articles">
					<?php
                $counter = 0;
foreach ($articles as $article) :
    $id = $article->ID;
    $category = get_the_category($id);
    $thumb = get_the_post_thumbnail($id);
    $link = get_permalink($id);
    $content = $article->post_content;
    $counter++;

    ?>

					<?php if ($category[0]->category_nicename == 'videos') : ?>
					<a data-fancybox href="<?php echo $content; ?>">
						<div
							class="item <?php echo $category[0]->category_nicename; ?> all">
							<div>
								<h3
									class="<?php echo $category[0]->category_nicename; ?>">
									<?php echo $category[0]->cat_name;  ?>
								</h3>
								<p>
									<?php echo limit_text($article->post_title, 14); ?>
								</p>
							</div>
							<div class="img_container">
								<?php echo $thumb; ?>
							</div>
							<span
								class="button <?php echo $category[0]->category_nicename; ?>">WATCH
								VIDEO</span>
						</div>
					</a>
					<?php else: ?>
					<a href="<?php echo $link; ?>">
						<div
							class="item <?php echo $category[0]->category_nicename; ?> all">
							<div>
								<h3
									class="<?php echo $category[0]->category_nicename; ?>">
									<?php echo $category[0]->cat_name;  ?>
								</h3>
								<p>
									<?php echo limit_text($article->post_title, 14); ?>
								</p>
							</div>
							<div class="img_container">
								<?php echo $thumb; ?>
							</div>
							<span
								class="button <?php echo $category[0]->category_nicename; ?>">READ
								MORE</span>
						</div>
					</a>
					<?php endif; ?>

					<?php endforeach; ?>
					<?php wp_reset_postdata(); ?>
				</div>
				<div class="btn__wrapper">
					<a href="#!" class="btn btn__primary" id="load-more">
						<span>Load More</span>
						<i class="ri-arrow-down-s-line"></i>
					</a>
				</div>
			</div>

		</div>
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
					“Builders, developers, and sales teams don’t want to be sold
					to. But the SaleFish experience speaks for itself. Their job
					has never been easier.“
				</h2>
				<p>
					RICK HAWS <br />
					PRESIDENT & CO-FOUNDER
				</p>
			</div>
			<a class="button" target="_blank"
				href="https://meetings.hubspot.com/leck?uuid=230963f4-62bf-47dc-99a4-264de6749b7b">BOOK A FREE DEMO</a>
		</div>
		<?php get_template_part('/partials/contact-general'); ?>

	</section>
	<!-- END CONTACT -->

</main>


<?php get_footer(); ?>