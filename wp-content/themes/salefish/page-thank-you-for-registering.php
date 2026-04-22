<?php
/**
 * Template for the post-registration thank-you page.
 * WordPress auto-selects this file for any page with the slug
 * "thank-you-for-registering" — no manual template selection needed.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main class="sf-ty-page">
	<div class="sf-ty-page__inner">
		<div class="sf-ty-page__card">

			<div class="sf-ty-page__icon-wrap">
				<i data-lucide="circle-check-big"></i>
			</div>

			<span class="sf-ty-page__eyebrow">Registration Confirmed</span>

			<h1 class="sf-ty-page__heading">Thank you for registering!</h1>

			<p class="sf-ty-page__body">
				A SaleFish specialist will reach out within 1 business day to walk you through the platform and talk through your specific project needs — whether that's inventory management, online signing, or end-to-end pre-construction sales workflow.
			</p>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="sf-ty-page__btn">
				Return to Homepage
			</a>

		</div>
	</div>
</main>

<?php get_footer(); ?>
