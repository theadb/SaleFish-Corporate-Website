<?php
/**
 * Template Name: Terms of Use Page
 */
get_header();
?>
<main class="prose-page">
	<section class="prose-page__section sf-section">
		<div class="prose-page__container">
			<h1><?php the_title(); ?></h1>
			<div class="sf-prose">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
