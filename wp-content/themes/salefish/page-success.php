<?php

/**
 * Template Name: Success Page
 *  Page
 * The template for displaying the App page
 */
get_header();
$register_header = get_field('register_header');

// PILLARS
$pillars = get_field('pillars');
?>

<main class="success">
	<!-- <div class="msg_fixed" data-aos="fade-in">
		<h3>UNCERTAINTY DOESN'T SELL.</h3>
		<p>CALL US AT <a href="tel:+18778927741">1.877.892.7741</a></p>
	</div> -->
	<!-- CONTACT -->
	<section class="contact">
		<div class="contact_bg"></div>
		<div class="success_hero" data-aos="fade-in">
			<div class="left">
				<div class="top_content">
					<h3>AN EASIER WAY TO SELL NEW HOMES</h3>
					<h1>
						DISCOVER THE <br/>
						PRE-CONSTRUCTION<br/>
						SALES POWER APP.
					</h1>
					<a class="button" target="_blank" href="https://meetings.hubspot.com/leck?uuid=230963f4-62bf-47dc-99a4-264de6749b7b">BOOK A FREE DEMO</a>
				</div>
				<div class="quote">
					<p>“The deal process had been 
					consummated under 5 minutes.”</p>
					<span>NICOLE LOMBARDI</span>
				</div>
			</div>
			<div class="right">
				<a data-fancybox href="https://www.youtube.com/watch?v=IsXY6NuAGFo">
					<img src="<?php bloginfo('template_directory'); ?>/img/success/success_hero.png">
				</a>
			</div>
		</div>
		<div class="divider"></div>
		<?php get_template_part('/partials/contact-success'); ?>
	</section>
	<!-- END CONTACT -->

	<!-- FEATURES -->
	<?php get_template_part('/partials/salefish-features'); ?>

	<?php get_template_part('/partials/need-more-info'); ?>

	<div class="contact contact_general">
		<?php get_template_part('/partials/contact-general'); ?>
	</div>




	<!-- PILLARS -->
	<section style="display: none;" class="pillars">
		<div class="max_wrapper">
			<!-- <div data-aos="fade-right">
				<h1>THE SALEFISH PILLARS</h1>
				<p class="subheader">Modern sales tools by the best, for the best.</p>
			</div> -->
			<div class="swiper pillarsSwiper" data-aos="fade-zoom-in">
				<div class="swiper-wrapper">
					<?php foreach($pillars as $row):
					    $icon = $row['icon'];
					    $title = $row['title'];
					    $description = $row['description'];
					    $button = $row['button'];
					    ?>
					<div class="swiper-slide">
						<img class="pillar"
							src="<?php echo $icon ?>">
						<h1>
							<?php echo $title ?>
						</h1>
						<p>
							<?php echo $description ?>
						</p>
						<?php if (!empty($button['text'])): ?>
						<a class="button"
							href="<?php echo esc_url($button['link']); ?>"
							target="_blank" rel="noopener noreferrer">
							<?php echo esc_html($button['text']); ?>
						</a>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
				<div class="controls">
					<div class="arrow">
						<img class="left_arrow"
							src="<?php bloginfo('template_directory'); ?>/img/left_arrow.png">
					</div>
					<div class="arrow">
						<img class="right_arrow"
							src="<?php bloginfo('template_directory'); ?>/img/right_arrow.png">
					</div>
				</div>
			</div>
		</div>

	</section>
	<!-- END PILLARS -->

	<!-- QUOTE -->
	<section style="display: none;" class="quote">
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<div class="top">
			<div class="top_content_center" data-aos="fade-up">
				<h2>
					“Builders, developers, and sales teams don’t want to be sold to. But the SaleFish experience speaks
					for itself. Their job has never been easier.“
				</h2>
				<p>
					RICK HAWS <br />
					PRESIDENT & CO-FOUNDER
				</p>
			</div>
			<!-- <a class="button" href="#contact_us">GET A DEMO</a> -->
		</div>

	</section>
	<!-- END QUOTE -->
</main>

<?php
get_footer();

?>