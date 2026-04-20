<?php
/**
 * Template Name: Post Page
 */
$id         = get_the_ID();
$title      = get_the_title();
$content    = get_the_content();
$category   = get_the_category($id);
$thumb      = get_the_post_thumbnail($id, 'large');
$date       = get_the_date('F j, Y');
$author_id  = get_post_field('post_author', $id);
$author_fname = get_the_author_meta('first_name', $author_id);
$author_lname = get_the_author_meta('last_name', $author_id);

get_header();
?>

<main class="single-post">

	<!-- ARTICLE -->
	<section class="single-post__section sf-section">
		<div class="single-post__container">

			<div class="single-post__back">
				<a href="/blog">
					&larr; Back to the Blog
				</a>
			</div>

			<header class="single-post__header">
				<?php if ( $category ): ?>
				<span class="sf-badge sf-badge--<?php echo esc_attr( $category[0]->category_nicename ); ?>">
					<?php echo esc_html( $category[0]->cat_name ); ?>
				</span>
				<?php endif; ?>
				<h1><?php echo esc_html( $title ); ?></h1>
				<p class="single-post__meta">
					<?php echo esc_html( $date ); ?>
					<?php if ( $author_fname || $author_lname ): ?>
					&middot; By <?php echo esc_html( trim("$author_fname $author_lname") ); ?>
					<?php endif; ?>
				</p>
			</header>

			<?php if ( $thumb ): ?>
			<div class="single-post__thumb">
				<?php echo $thumb; ?>
			</div>
			<?php endif; ?>

			<div class="sf-prose single-post__content">
				<?php echo wp_kses_post( apply_filters('the_content', $content) ); ?>
			</div>

		</div>
	</section>
	<!-- END ARTICLE -->

</main>

<?php get_footer(); ?>
