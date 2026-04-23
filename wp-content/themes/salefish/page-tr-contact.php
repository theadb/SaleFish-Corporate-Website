<?php
/**
 * Template Name: TR Contact Us Page
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
		<?php get_template_part( '/partials/contact-tr' ); ?>
	</section>
	<!-- END CONTACT -->

	<!-- SALEFISH HQ -->
	<section class="hq">
		<div class="max_wrapper">
			<div class="left" data-aos="fade-right">
				<h1>SaleFish Türkiye - Mimario Gayrimenkul</h1>
				<a class="important-p" href="https://maps.app.goo.gl/9VEiKxHZaXGe3uz6A" target="_blank" rel="noopener noreferrer">
					Şerifali Mah. <br />
					Kutup Sok. No:40 <br />
					WorkHub Plaza İç Kapı No:3 34775 <br />
					Ümraniye/İstanbul
				</a>
				<a href="tel:+905333311236">+90 0533 331 12 36</a>
				<a class="email" href="mailto:hello@salefish.app">hello@salefish.app</a>
				<div class="socials">
					<a href="https://www.instagram.com/salefishapp/" target="_blank" rel="noopener noreferrer">
						<img class="social_logo ig" src="<?php echo get_template_directory_uri(); ?>/img/ig_logo.png" alt="Instagram">
					</a>
					<a href="https://www.facebook.com/salefishapp" target="_blank" rel="noopener noreferrer">
						<img class="social_logo facebook" src="<?php echo get_template_directory_uri(); ?>/img/facebook_logo.png"
							alt="Facebook">
					</a>
					<a href="https://www.youtube.com/@salefishapp" target="_blank" rel="noopener noreferrer">
						<img class="social_logo youtube" src="<?php echo get_template_directory_uri(); ?>/img/youtube_logo.png"
							alt="Youtube">
					</a>
					<a href="https://www.linkedin.com/company/salefishapp/" target="_blank" rel="noopener noreferrer">
						<img class="social_logo linkedin" src="<?php echo get_template_directory_uri(); ?>/img/linkedin_logo.png"
							alt="Linkedin">
					</a>
				</div>
			</div>
			<div class="right" id="TR_map" data-aos="fade-left">

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
				<h2>
					Desteğe mi ihtiyacınız var?
				</h2>
				<p>
					Bir SaleFish kullanıcısı mısınız ve yardıma mı ihtiyacınız var? <br />
					Yardım etmek için buradayız.
				</p>
			</div>
			<a class="button" href="mailto:hello@salefish.app">BİZE E-POSTAS</a>
		</div>
	</section>
	<!-- END CONTACT -->
</main>

<?php get_footer(); ?>
<script async src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr( defined( 'GOOGLE_MAPS_API_KEY' ) ? GOOGLE_MAPS_API_KEY : '' ); ?>&callback=initMap"></script>