<?php
/**
 * Template Name: Terms of Use Page
 * The template for displaying the Terms of Use page
 */

get_header();
?>

<main class="legal-page">

	<!-- HERO -->
	<section class="legal-hero">
		<div class="sf-container">
			<div class="legal-hero__inner">
				<span class="legal-eyebrow" data-aos="fade-up" data-aos-delay="100">Legal</span>
				<h1 data-aos="fade-up" data-aos-delay="220">Terms of Use</h1>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- CONTENT -->
	<section class="legal-body">
		<div class="sf-container">
			<div class="legal-body__content" data-aos="fade-up" data-aos-delay="100">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<!-- END CONTENT -->

</main>

<?php get_footer(); ?>
