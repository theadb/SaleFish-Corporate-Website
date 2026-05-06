<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _pc
 */

get_header(); ?>

<main id="main-content" class="site-main" role="main">
	<?php get_template_part( 'partials/analyticstracking' ); ?>
	<?php get_template_part( 'partials/text-content' ); ?>
</main>

<?php

get_footer();