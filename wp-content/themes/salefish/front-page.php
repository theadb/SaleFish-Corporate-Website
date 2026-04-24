<?php

/**
 * Template Name: Home Page
 * The template for displaying the App page
 */
get_header();

// HERO
$fade_msg = get_field('fade_messages');
$fade = array();
foreach ((is_array($fade_msg) ? $fade_msg : []) as $msg) {
    array_push($fade, mb_convert_case($msg['text'], MB_CASE_TITLE, 'UTF-8'));
}
$hero_header = mb_convert_case(get_field('hero_header'), MB_CASE_TITLE, 'UTF-8');
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
	let textArray = <?php echo wp_json_encode( $fade, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;
</script>

<main class="home" id="main-content">
	<!-- HERO -->
	<section class="hero">
		<div class="wrapper">
			<div class="max_wrapper">
				<div class="left">
					<h3 data-aos="fade-up" data-aos-delay="100">An Easier Way to <span>Sell <span id="app_for_home">Home Sales</span></span></h3>
					<h1 data-aos="fade-up" data-aos-delay="280">
						<?php echo wp_kses_post( $hero_header ); ?>
					</h1>
					<a class="button" data-aos="fade-up" data-aos-delay="460" href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c" target="_blank" rel="noopener noreferrer" data-sf-section="Homepage — Hero">See Why They're Beating You</a>
				</div>
				<div class="right" data-aos="fade-left" data-aos-delay="200">
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
					<h3>The Real Estate Leaders</h3>
					<h3 class="bold">Beating You Because of SaleFish:</h3>
				</div>

				<div data-aos="fade-zoom-in" class="builders_wrap">
					<div class="builders_marquee">
						<div class="builders_track">
							<?php foreach((is_array($builders) ? $builders : []) as $builder): ?>
							<img class="builder_logo" src="<?php echo esc_url( $builder ); ?>" alt="" loading="eager" decoding="async">
							<?php endforeach; ?>
							<?php foreach((is_array($builders) ? $builders : []) as $builder): ?>
							<img class="builder_logo" src="<?php echo esc_url( $builder ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
							<?php endforeach; ?>
						</div>
					</div>
					<div class="mobile_builders">
						<div class="row">
							<?php foreach((is_array($builders) ? $builders : []) as $builder): ?>
							<div class="col">
								<img class="builder_logo" src="<?php echo esc_url( $builder ); ?>" alt="" loading="lazy" decoding="async">
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
	<?php get_template_part('/partials/salefish-features'); ?>
	<!-- END FEATURES -->

	<!-- PILLARS -->
	<section class="pillars">
		<div class="max_wrapper">
			<div data-aos="fade-right">
				<h1>The SaleFish Pillars</h1>
				<p class="subheader">We Build Tools for People Who Expect to Win.</p>
			</div>
			<div class="swiper pillarsSwiper" data-aos="fade-zoom-in">
				<div class="swiper-wrapper">
					<?php foreach((is_array($pillars) ? $pillars : []) as $row):
					    $icon        = $row['icon'];
					    $title       = ucwords( strtolower( $row['title'] ) );
					    $description = $row['description'];
					    ?>
					<div class="swiper-slide">
						<img class="pillar"
							src="<?php echo esc_url( $icon ); ?>" loading="lazy" decoding="async" alt="" aria-hidden="true">
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
					<button class="arrow left_arrow" type="button" aria-label="Previous slide">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</button>
					<button class="arrow right_arrow" type="button" aria-label="Next slide">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
					</button>
				</div>
			</div>
		</div>
	</section>
	<!-- END PILLARS -->

	<!-- CONTACT -->
	<section class="contact">
		<div class="contact_overlay"><div class="width"></div></div>
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<div class="top">
			<div class="title" data-aos="fade-up">
				<h1>
					<?php echo esc_html( mb_convert_case($numbers_header['title'], MB_CASE_TITLE, 'UTF-8') ); ?>
				</h1>
				<p>
					<?php echo wp_kses_post( $numbers_header['description'] ); ?>
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
				<div class="col" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( ($counter - 1) * 150 ); ?>">
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
							<p>builders, developers & sales partners</p>
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
						alt="Left Arrow" loading="lazy" decoding="async">
					<img class="arrow right_arrow"
						src="<?php echo get_template_directory_uri(); ?>/img/light_right_arrow.png"
						alt="Right Arrow" loading="lazy" decoding="async">
				</div>
			</div>

			<a class="button" href="javascript:void(0)" data-sf-modal="register" data-sf-section="Homepage — Numbers CTA">Get My Demo</a>
		</div>
		<?php get_template_part('/partials/contact-general'); ?>
	</section>
	<!-- END CONTACT -->
</main>

<?php
get_footer();

?>