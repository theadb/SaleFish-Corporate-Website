<?php
/**
 * Template Name: Contact Us Page
 * The template for displaying the contact us page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */


get_header();
?>

<main class="contact_us">
	<!-- CONTACT -->
	<section class="contact">
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<?php get_template_part('/partials/contact-general'); ?>
	</section>
	<!-- END CONTACT -->

	<!-- SALEFISH HQ -->
	<section class="hq">
		<div class="max_wrapper">
			<div class="left" data-aos="fade-right">
				<h1>SALEFISH HQ</h1>
				<a class="important-p"
					href="https://www.google.com/maps/place/8395+Jane+St+%23203,+Concord,+ON+L4K+5Y2/@43.8121808,-79.5277303,17z/data=!3m1!4b1!4m5!3m4!1s0x882b2f0f9ebd82b9:0x617bae8e4bdb708b!8m2!3d43.8121808!4d-79.5277303"
					target="_blank">
					8395 Jane Street, Suite 202 <br />
					Vaughan, ON, L4K 5Y2 <br />
					Canada <br />
				</a>
				<a href="tel:+18778927741">1.877.892.7741</a>
				<a href="tel:+19057615364">1.905.761.5364</a>
				<a class="email" href="mailto:hello@salefish.app">hello@salefish.app</a>
				<div class="socials">
					<a href="https://www.linkedin.com/company/salefishapp/" target="_blank" rel="noopener noreferrer">
						<img class="social_logo linkedin"
							src="<?php bloginfo('template_directory'); ?>/img/linkedin_logo.png"
							alt="Linkedin">
					</a>
					<a href="https://www.instagram.com/salefishapp/" target="_blank" rel="noopener noreferrer">
						<img class="social_logo ig"
							src="<?php bloginfo('template_directory'); ?>/img/ig_logo.png"
							alt="Instagram">
					</a>
				</div>
			</div>
			<div class="right" id="map" data-aos="fade-left">

			</div>
		</div>

	</section>
	<!-- SALEFISH HQ -->

	<!-- CONTACT -->
	<section class="contact contact_bottom">
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<div class="top">
			<div class="top_content_center">
				<h1>
					NEED SUPPORT?
				</h1>
				<p data-aos="fade-up">
					Are you a SaleFish user and need some help?<br />
					Our award-winning support team has you covered.
				</p>
			</div>
			<a class="button" target="_blank" href="https://chatting.page/salefish">GET LIVE CHAT SUPPORT</a>
		</div>
	</section>
	<!-- END CONTACT -->
</main>

<?php get_footer(); ?>
<script async
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBysfnL28j4RcQy5y3PfTPtQY_6Ao6AAog&callback=initMap">
</script>