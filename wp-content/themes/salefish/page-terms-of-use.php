<?php
/**
 * Template Name: Terms of Use Page
 * The template for displaying the our terms of use page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */


get_header();
?>

<main class="terms">
	<div class="wrap">
		<h1>Terms of Use</h1>
		<?php the_content(); ?>
	</div>
</main>


<?php get_footer(); ?>