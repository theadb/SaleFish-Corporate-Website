<?php
/**
 * 404 — Page Not Found template.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main class="sf-ty-page" id="main-content">

	<section class="sf-ty-page__hero">
		<div class="sf-ty-page__hero-inner max_wrapper">
			<div class="sf-ty-page__badge">
				<i data-lucide="map-pin-off" aria-hidden="true"></i>
			</div>
			<p class="sf-ty-page__eyebrow">404 &mdash; Page Not Found</p>
			<h1 class="sf-ty-page__heading">This page doesn&rsquo;t exist.</h1>
		</div>
	</section>

	<section class="sf-ty-page__body-section">
		<div class="max_wrapper sf-ty-page__body-inner">
			<p class="sf-ty-page__body-text">
				The page you&rsquo;re looking for may have moved or been removed. Head back to the homepage to find what you need.
			</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">
				Back to Homepage
			</a>
		</div>
	</section>

</main>

<?php get_footer(); ?>
