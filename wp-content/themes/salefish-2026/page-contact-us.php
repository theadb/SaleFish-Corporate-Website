<?php
/**
 * Template Name: Contact Us Page
 */
get_header();
?>

<main class="contact-page">

	<!-- MAIN CONTACT SECTION -->
	<section class="contact-page__main sf-section" id="contact_us">
		<div class="sf-container">
			<div class="contact-page__grid">

				<!-- HQ INFO CARD -->
				<div class="sf-card contact-page__info">
					<h2>SaleFish HQ</h2>
					<div class="contact-page__address">
						<a href="https://www.google.com/maps/place/8395+Jane+St+%23203,+Concord,+ON+L4K+5Y2/@43.8121808,-79.5277303,17z"
							target="_blank" rel="noopener noreferrer">
							8395 Jane Street, Suite 202<br>
							Vaughan, ON, L4K 5Y2<br>
							Canada
						</a>
					</div>
					<div class="contact-page__contact-links">
						<a href="tel:+18778927741">1.877.892.7741</a>
						<a href="tel:+19057615364">1.905.761.5364</a>
						<a href="mailto:hello@salefish.app">hello@salefish.app</a>
					</div>
					<div id="map" class="contact-page__map"></div>
					<div class="contact-page__social">
						<a href="https://www.linkedin.com/company/salefishapp/" target="_blank" rel="noopener noreferrer">
							<img class="social_logo linkedin" src="<?php bloginfo('template_directory'); ?>/img/linkedin_logo.png" alt="LinkedIn">
						</a>
						<a href="https://www.instagram.com/salefishapp/" target="_blank" rel="noopener noreferrer">
							<img class="social_logo ig" src="<?php bloginfo('template_directory'); ?>/img/ig_logo.png" alt="Instagram">
						</a>
					</div>
				</div>

				<!-- CONTACT FORM CARD -->
				<div class="sf-card contact-page__form-card">
					<?php get_template_part('/partials/contact-general'); ?>
				</div>

			</div>
		</div>
	</section>
	<!-- END MAIN CONTACT -->

	<!-- SUPPORT CTA STRIP -->
	<section class="contact-page__support sf-gradient-primary sf-section">
		<div class="sf-container">
			<div class="contact-page__support-inner">
				<h2>Need Support?</h2>
				<p>Are you a SaleFish user and need some help? Our award-winning support team has you covered.</p>
				<a class="sf-btn" style="background:hsl(var(--sf-primary-fg));color:hsl(var(--sf-primary));"
					target="_blank" rel="noopener noreferrer"
					href="https://chatting.page/salefish">
					Get Live Chat Support
				</a>
			</div>
		</div>
	</section>
	<!-- END SUPPORT -->

</main>

<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBysfnL28j4RcQy5y3PfTPtQY_6Ao6AAog&callback=initMap"></script>

<?php get_footer(); ?>
