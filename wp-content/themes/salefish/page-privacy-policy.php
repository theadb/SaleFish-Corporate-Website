<?php
/**
 * Template Name: Privacy Policy Page
 * The template for displaying the Privacy Policy page
 */

get_header();
?>

<main class="legal-page">

	<!-- HERO -->
	<section class="legal-hero">
		<div class="sf-container">
			<div class="legal-hero__inner">
				<span class="legal-eyebrow" data-aos="fade-up" data-aos-delay="100">Legal</span>
				<h1 data-aos="fade-up" data-aos-delay="250">Privacy Policy</h1>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- CONTENT -->
	<section class="legal-body">
		<div class="sf-container">
			<div class="legal-body__content">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<!-- END CONTENT -->

</main>

<?php get_footer(); ?>
