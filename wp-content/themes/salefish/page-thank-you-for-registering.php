<?php
/**
 * Template for the post-registration thank-you page.
 * WordPress auto-selects this file for any page with the slug
 * "thank-you-for-registering" — no manual template selection needed.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main class="sf-ty-page" id="main-content">

	<!-- Hero band — same purple treatment as other content pages -->
	<section class="sf-ty-page__hero">
		<div class="sf-ty-page__hero-inner max_wrapper">
			<div class="sf-ty-page__badge">
				<i data-lucide="circle-check-big" aria-hidden="true"></i>
			</div>
			<p class="sf-ty-page__eyebrow">Registration Confirmed</p>
			<h1 class="sf-ty-page__heading">Thank you for registering!</h1>
		</div>
	</section>

	<!-- Body content — white background, centered column -->
	<section class="sf-ty-page__body-section">
		<div class="max_wrapper sf-ty-page__body-inner">
			<p class="sf-ty-page__body-text">
				A SaleFish specialist will reach out within 1 business day to walk you through the platform and talk through your specific project needs — whether that's inventory management, online signing, or end-to-end pre-construction sales workflow.
			</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">
				Return to Homepage
			</a>
		</div>
	</section>

</main>

<?php get_footer(); ?>
