<?php

/**
 * Template Name: DE Home Page
 * The template for displaying the App page
 */
get_header();

// HERO
$fade_msg = get_field('fade_messages');
$fade = array();
foreach ((is_array($fade_msg) ? $fade_msg : []) as $msg) {
    $text = $msg['text'];
    array_push($fade, $text);
}
$hero_header = get_field('hero_header');
$hero_image = get_field('hero_image');

// BUILDERS
$builders = get_field('builders_developers');

// PILLARS
$pillars = get_field('pillars');

// FEATURES
$features = get_field('features');

// THE NUMBERS
$numbers_header = get_field('numbers_header');
$the_numbers = get_field('the_numbers');


?>


<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5CX687F" height="0" width="0"
		style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<script>
	let textArray = <?php echo wp_json_encode( $fade, JSON_HEX_TAG | JSON_HEX_AMP ); ?> ;
</script>

<main class="home">
	<!-- POPUP -->
	<div class="selling_popup_container">
		<div class="selling_popup">
			<a href="#contact_us" class="scroll_link button-image">
				<img src="<?php echo get_template_directory_uri(); ?>/img/popup.png"
					class="popup" alt="Demo buchen">
			</a>
			<img src="<?php echo get_template_directory_uri(); ?>/img/x-closed.svg"
				class="close_icon" alt="" aria-hidden="true">
		</div>
	</div>
	<!-- HERO -->
	<section class="hero">
		<div class="wrapper">
			<div class="max_wrapper">
				<div class="left" data-aos="fade-right" data-aos-delay="300">
					<h3>EIN EINFACHERER WEG, HAUSVERKÄUFE ZU VERKAUFEN</h3>
					<h1>
						<?php echo wp_kses_post( $hero_header ); ?>
					</h1>
					<a class="button" href="#contact_us">BUCHEN SIE EINE KOSTENLOSE DEMO
					</a>
				</div>
				<div class="right" data-aos="zoom-in" data-aos-delay="300">
					<img class="salefish_demo"
						src="<?php echo esc_url( $hero_image ); ?>"
						alt="Salefish App Demo">
				</div>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- BUILDER -->
	 <div class="builders_overlay">
	 	<section class="builders">
			<div class="max_wrapper">
				<div data-aos="fade-right">
					<h3>DIE IMMOBILIENFÜHRER</h3>
					<h3 class="bold">DICH WEGEN SALEFISH SCHLAGEN:</h3>
				</div>

				<div data-aos="fade-zoom-in" class="builders_wrap">
					<div class="builders_marquee">
						<div class="builders_track">
							<?php foreach((is_array($builders) ? $builders : []) as $builder): ?>
							<img class="builder_logo" src="<?php echo esc_url( $builder ); ?>" alt="">
							<?php endforeach; ?>
							<?php foreach((is_array($builders) ? $builders : []) as $builder): ?>
							<img class="builder_logo" src="<?php echo esc_url( $builder ); ?>" alt="" aria-hidden="true">
							<?php endforeach; ?>
						</div>
					</div>
					<div class="mobile_builders">
						<div class="row">
							<?php foreach((is_array($builders) ? $builders : []) as $builder): ?>
							<div class="col">
								<img class="builder_logo" src="<?php echo esc_url( $builder ); ?>" alt="">
							</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	 </div>

	<!-- END BUILDERS -->

	<!-- FEATURES -->
	<?php get_template_part('/partials/salefish-features-de'); ?>
	<!-- END FEATURES -->


	<!-- CONTACT -->
	<section class="contact">
		<div class="contact_overlay"><div class="width"></div></div>
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<div class="top">
			<div class="title" data-aos="fade-up">
				<h1>
					<?php echo esc_html( $numbers_header['title'] ?? '' ); ?>
				</h1>
				<p>
					<?php echo wp_kses_post( $numbers_header['description'] ?? '' ); ?>
				</p>
			</div>
			<div class="content">
				<?php
				$counter = 0;
				foreach((is_array($the_numbers) ? $the_numbers : []) as $row):
				    $prefix = $row['prefix'];
				    $number = $row['number'];
				    $suffix = $row['suffix'];
				    $description = $row['description'];
				    $counter++;
				    ?>
				<div class="col">
					<h1>
						<?php echo esc_html( $prefix ); ?> <span
							data-number="<?php echo esc_attr( $number ); ?>"
							id="count_<?php echo esc_attr( $counter ); ?>"></span>
						<?php echo esc_html( $suffix ); ?>
					</h1>
					<p>
						<?php echo wp_kses_post( $description ); ?>
					</p>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="mobile_content">
				<div class="swiper numbersSwiper">
					<div class="swiper-wrapper">
						<div class="swiper-slide">
							<h1>$<span id="count_1_mobile"></span>B</h1>
							<p>in global new home sales</p>
						</div>
						<div class="swiper-slide">
							<h1><span id="count_2_mobile"></span>+</h1>
							<p>builders, developers &amp; sales partners</p>
						</div>
						<div class="swiper-slide">
							<h1><span id="count_3_mobile"></span>M</h1>
							<p>users of the SaleFish platform</p>
						</div>
					</div>
				</div>
				<div class="arrows numbersSwiperArrow">
					<img class="arrow left_arrow"
						src="<?php echo get_template_directory_uri(); ?>/img/light_left_arrow.png"
						alt="Left Arrow">
					<img class="arrow right_arrow"
						src="<?php echo get_template_directory_uri(); ?>/img/light_right_arrow.png"
						alt="Right Arrow">
				</div>
			</div>

			<a class="button" target="_blank" rel="noopener noreferrer" href="https://chatting.page/salefish">CHATTEN SIE MIT UNS</a>
		</div>
		<!-- PILLARS -->
		<section class="pillars">
			<div class="max_wrapper">
				<div data-aos="fade-right">
					<h1>Die SaleFish-Säulen</h1>
					<p class="subheader">Alle Tools, die Sie benötigen, um auf dem heutigen Markt erfolgreich zu verkaufen.</p>
				</div>
				<div class="swiper pillarsSwiper" data-aos="fade-zoom-in">
					<div class="swiper-wrapper">
						<?php foreach((is_array($pillars) ? $pillars : []) as $row):
						    $icon = $row['icon'];
						    $title = ucwords( strtolower( $row['title'] ) );
						    $description = $row['description'];
						    ?>
						<div class="swiper-slide">
							<img class="pillar"
								src="<?php echo esc_url( $icon ); ?>" alt="" aria-hidden="true">
							<h3>
								<?php echo esc_html( $title ); ?>
							</h3>
							<p>
								<?php echo wp_kses_post( $description ); ?>
							</p>
						</div>
						<?php endforeach; ?>
					</div>
					<div class="controls">
						<div class="arrow">
							<img class="left_arrow"
								src="<?php echo get_template_directory_uri(); ?>/img/left_arrow.png" alt="" aria-hidden="true">
						</div>
						<div class="arrow">
							<img class="right_arrow"
								src="<?php echo get_template_directory_uri(); ?>/img/right_arrow.png" alt="" aria-hidden="true">
						</div>
					</div>
				</div>
			</div>

		</section>
		<!-- END PILLARS -->

		<?php get_template_part('/partials/contact-de'); ?>
	</section>
	<!-- END CONTACT -->
</main>

<?php
get_footer();

?>
