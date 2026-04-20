<?php
/**
 * Template Name: Post Page
 * The template for displaying the post page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */


$id = get_the_ID();
$title = get_the_title();
$content = get_the_content();
$category = get_the_category($id);
$thumb = get_the_post_thumbnail($id);
$date = get_the_date();
$author_id = get_post_field('post_author', $id);
$author_fname = get_the_author_meta('first_name', $author_id);
$author_lname = get_the_author_meta('last_name', $author_id);

$featured_story = get_posts(
    array(
        'numberposts' => -1,
        'post_status' => 'any',
        'tag' => 'featured'
    )
);
get_header();
?>

<main class="single_post">
	<section class="article">
		<div class="max_wrapper">
			<div class="back_arrow">
				<a href="/blog">
					<img class="back"
						src="<?php bloginfo('template_directory'); ?>/img/back_arrow.png">
					<p>Back</p>
				</a>
			</div>

			<div class="header">
				<h3
					class="<?php  echo $category[0]->category_nicename; ?>">
					<?php echo $category[0]->cat_name; ?>
				</h3>
				<h1>
					<?php echo $title; ?>
				</h1>
				<p>
					<?php echo $date; ?> By
					<?php echo $author_fname; ?>
					<?php echo $author_lname; ?>
				</p>
			</div>

			<div class="thumb">
				<?php echo $thumb; ?>
			</div>

			<div class="content">
				<?php echo $content; ?>
			</div>
		</div>

	</section>
	<!-- ARTICLES -->
	<section class="featured_articles">
		<div class="filter">
			<div class="cut"></div>
			<div class="wrap">
				<h3>
					FEATURED STORY:
				</h3>
			</div>

		</div>
		<div class="wrapper">

			<div class="article_items">
				<div class="cut"></div>
				<div class="max_wrapper">
					<?php
                    foreach ($featured_story as $article) {
                        $article_id = $article->ID;
                        $category = get_the_category($article_id);
                        $content = $article->post_content;
                        $article_cat = get_the_category($article_id);
                        $article_thumb = get_the_post_thumbnail($article_id);
                        $article_link = get_permalink($article_id);
                        ?>
					<div class="items">
						<?php if ($category[0]->category_nicename == 'videos') : ?>
						<a data-fancybox
							href="<?php echo $content; ?>">
							<div
								class="item <?php echo $article_cat[0]->category_nicename; ?> all">
								<div>
									<h3
										class="<?php echo $article_cat[0]->category_nicename; ?>">
										<?php echo $article_cat[0]->cat_name;  ?>
									</h3>
									<p>
										<?php echo limit_text($article->post_title, 14); ?>
									</p>
								</div>
								<div class="img_container">
									<?php echo $article_thumb; ?>
								</div>
								<span
									class="button <?php echo $article_cat[0]->category_nicename; ?>">WATCH
									VIDEO</span>
							</div>
						</a>
						<?php else: ?>
						<a href="<?php echo $article_link; ?>">
							<div
								class="item <?php echo $article_cat[0]->category_nicename; ?> all">
								<div>
									<h3
										class="<?php echo $article_cat[0]->category_nicename; ?>">
										<?php echo $article_cat[0]->cat_name;  ?>
									</h3>
									<p>
										<?php echo limit_text($article->post_title, 14); ?>
									</p>
								</div>
								<div class="img_container">
									<?php echo $article_thumb; ?>
								</div>
								<span
									class="button <?php echo $article_cat[0]->category_nicename; ?>">READ
									MORE</span>
							</div>
						</a>
						<?php endif; ?>
					</div>
					<?php } ?>
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
			<a class="button" target="_blank" href="https://meetings.hubspot.com/leck?uuid=230963f4-62bf-47dc-99a4-264de6749b7b">BOOK A FREE DEMO</a>
		</div>
		<?php get_template_part('/partials/contact-general'); ?>

	</section>
	<!-- END CONTACT -->
</main>

<?php get_footer(); ?>