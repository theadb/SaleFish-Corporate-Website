<?php
/**
 * Template Name: Blog Page
 * The template for displaying the blog page
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




<main class="blog">
	<!-- HERO -->
	<section class="hero">
		<div class="wrapper">
			<div class="left" data-aos="fade-right" data-aos-delay="300">
				<h3>Insights, Stories & News From</h3>
				<h1>The SaleFish</h1>
				<h1>Blog</h1>
				</br>
				<p>(Are you taking notes?)</p>
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
						You Can Filter the Posts Below:
					</h3>
					<select id="blog-filter" class="blog-filter">
						<option value="all">All Articles</option>
						<option value="success-stories">Success Stories</option>
						<option value="press">Press</option>
						<option value="blog">Blog</option>
						<option value="videos">Videos</option>
					</select>
				</div>
			</div>

		</div>
		<div class="article_items">
			<div class="cut"></div>
			<div class="max_wrapper">
				<div class="items blog_articles">
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
								class="button <?php echo $category[0]->category_nicename; ?>">Watch Video</span>
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
								class="button <?php echo $category[0]->category_nicename; ?>">Read More</span>
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
					"Builders, developers, and sales teams don't want to be sold
					to. But the SaleFish experience speaks for itself. Their job
					has never been easier."
				</h2>
				<p>
					Rick Haws <br />
					President & Co-Founder
				</p>
			</div>
			<a class="button" target="_blank"
				href="https://meetings.hubspot.com/leck?uuid=230963f4-62bf-47dc-99a4-264de6749b7b">Book a Free Demo</a>
		</div>
		<?php get_template_part('/partials/contact-general'); ?>

	</section>
	<!-- END CONTACT -->

</main>


<?php get_footer(); ?>
