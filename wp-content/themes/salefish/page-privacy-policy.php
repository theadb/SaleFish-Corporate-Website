<?php
/**
 * Template Name: Privacy Policy Page
 * The template for displaying the our privacy policy page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */


get_header();
?>

<main class="policy">
	<div class="wrap">
		<h1>Privacy Policy</h1>
		<?php the_content(); ?>
	</div>
</main>


<?php get_footer(); ?>