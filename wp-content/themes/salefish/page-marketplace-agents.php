<?php

/**
 * Template Name: Marketplace Agents Page
 *  Page
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
$hero_description = get_field('hero_description');
$hero_image = get_field('hero_image');

// BUILDERS
$builders_developers = get_field('builders_developers');


// AGENTS
$agents = get_field('agents');
$agents_header = get_field('agents_header') ?: [];
$agents_header_title = $agents_header['title'] ?? '';
$agents_header_content = $agents_header['content'] ?? '';
$agents_header_button = $agents_header['button'] ?? [];
$agents_header_button_text = $agents_header_button['text'] ?? '';
$agents_header_button_link = $agents_header_button['link'] ?? '';

// BUILDERS
$builders = get_field('builders');
$builders_header = get_field('builders_header') ?: [];
$builders_header_title = $builders_header['title'] ?? '';
$builders_header_content = $builders_header['content'] ?? '';
$builders_header_button = $builders_header['button'] ?? [];
$builders_header_button_text = $builders_header_button['text'] ?? '';
$builders_header_button_link = $builders_header_button['link'] ?? '';

?>

<script>
	let textArray = <?php echo wp_json_encode( $fade, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;
</script>

<main class="marketplace">
	<!-- HERO -->
	<section class="hero">
		<div class="wrapper">
			<div class="max_wrapper">
				<div class="left" data-aos="fade-right" data-aos-delay="300">
					<h3>AN EASIER WAY TO <span>SELL <label id="app_for_home">HOME SALES</label></span></h3>
					<h1>
						<?php echo wp_kses_post( $hero_header ); ?>
					</h1>
					<?php echo wp_kses_post( $hero_description ); ?>
					<a class="button" target="_blank" rel="noopener noreferrer" href="https://marketplace.salefish.app/marketplace-welcome">SIGN UP NOW</a>
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
					<h3>SALEFISH NEW HOME & CONDO MARKETPLACE</h3>
					<h3 class="bold">FEATURES PROJECTS BY:</h3>
				</div>

				<div data-aos="fade-zoom-in" class="builders_wrap">
					<div class="swiper marketplaceBuildersSwiper">
						<div class="swiper-wrapper">
							<?php foreach((is_array($builders_developers) ? $builders_developers : []) as $builder): ?>
							<div class="swiper-slide">
								<img class="builder_logo builder_1"
									src="<?php echo esc_url( $builder ); ?>">
							</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="mobile_builders">
						<div class="row">
							<?php foreach((is_array($builders_developers) ? $builders_developers : []) as $builder): ?>
							<div class="col">
								<img class="builder_logo builder_1"
									src="<?php echo esc_url( $builder ); ?>">
							</div>
							<?php endforeach; ?>
						</div>

					</div>
				</div>
			</div>
		</section>
	</div>

	<!-- END BUILDERS -->

	<!-- AGENTS -->
	<section class="agents">
		<div class="container">
			<div class="header" data-aos="fade-up" data-aos-delay="300">
				<h1><?php echo esc_html( $agents_header_title ); ?></h1>
				<p><?php echo wp_kses_post( $agents_header_content ); ?></p>
				<?php if($agents_header_button_link):?>
				<a class="button"
					href="<?php echo esc_url( $agents_header_button_link ); ?>"
					target="_blank" rel="noopener noreferrer">
					<?php echo esc_html( $agents_header_button_text ); ?>
				</a>
				<?php endif; ?>
			</div>
			<div class="items">
				<?php foreach((is_array($agents) ? $agents : []) as $row):
				    $image = $row['image'];
				    $sub_title = $row['sub_title'];
				    $title = $row['title'];
				    $content = $row['content'];
				    ?>
				<div class="item">
					<div class="content_img">
						<img data-aos="fade-up" data-aos-delay="300" src="<?php echo esc_url( $image ); ?>">
					</div>
					<div class="info" data-aos="fade-right" data-aos-delay="300">
						<h3><?php echo esc_html( $sub_title ); ?></h3>
						<h2><?php echo esc_html( $title ); ?></h2>
						<p><?php echo wp_kses_post( $content ); ?></p>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<!-- END AGENTS -->


	<div class="need_more_info need_more_info_top">
		<div class="top_overlay">
			<div class="width"></div>
		</div>
		<div class="container">
			<div class="max_width" data-aos="fade-up" data-aos-delay="300">
				<h1>NEED MORE INFO?</h1>
				<p>Contact us for a free assessment of
					your current sales process and tech stack.</p>

				<div class="info">
					<img
						src="<?php echo get_template_directory_uri(); ?>/img/success/pic.png">
					<div class="right">
						<div class="name">
							<p>Danny Leck</p>
							<span>Senior Sales Manager</span>
						</div>
						<div class="cta_button">
							<a class="button" target="_blank" rel="noopener noreferrer" href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c" data-sf-section="Agents — Top CTA">BOOK A FREE DEMO</a>
							<a class="button hollow" href="tel:+16475425325">CALL</a>
							<a class="button hollow" href="mailto:dannyl@salefish.app">EMAIL</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- FOR BUILDERS, DEVELOPERS& SALES TEAMS -->
	<section class="agents">
		<div class="container">
			<div class="header" data-aos="fade-up" data-aos-delay="300">
				<h1><?php echo esc_html( $builders_header_title ); ?></h1>
				<p><?php echo wp_kses_post( $builders_header_content ); ?></p>
				<?php if($builders_header_button_link):?>
				<a class="button"
					href="<?php echo esc_url( $builders_header_button_link ); ?>">
					<?php echo esc_html( $builders_header_button_text ); ?>
				</a>
				<?php endif; ?>

			</div>
			<div class="items">
				<?php foreach((is_array($builders) ? $builders : []) as $row):
				    $image = $row['image'];
				    $sub_title = $row['sub_title'];
				    $title = $row['title'];
				    $content = $row['content'];
				    ?>
				<div class="item">
					<div class="content_img" data-aos="fade-up" data-aos-delay="300">
						<img src="<?php echo esc_url( $image ); ?>">
					</div>
					<div class="info" data-aos="fade-right" data-aos-delay="300">
						<h3><?php echo esc_html( $sub_title ); ?></h3>
						<h2><?php echo esc_html( $title ); ?></h2>
						<p><?php echo wp_kses_post( $content ); ?></p>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<!-- END FOR BUILDERS, DEVELOPERS& SALES TEAMS -->

	<div class="need_more_info need_more_info_bottom">
		<div class="top_overlay">
			<div class="width"></div>
		</div>
		<div class="container">
			<div class="max_width" data-aos="fade-up" data-aos-delay="300">
				<h1>WANT TO MAKE MORE SALES?</h1>
				<p>Contact us for to add your unsold and inventory
					homes to the SaleFish New Home & Condo Marketplace.</p>

				<div class="info">
					<img
						src="<?php echo get_template_directory_uri(); ?>/img/success/cindy.png">
					<div class="right">
						<div class="name">
							<p>Cindy Lloyd</p>
							<span>DIRECTOR OF SALES AND CUSTOMER SUCCESS</span>
						</div>
						<div class="cta_button">
							<a class="button" target="_blank" rel="noopener noreferrer" href="https://meetings.hubspot.com/cindy-lloyd?uuid=37b969b2-b6fe-40ea-9257-6cff0dfaae5b" data-sf-section="Agents — Bottom CTA">BOOK A FREE DEMO</a>
							<a class="button hollow" href="tel:+14167290773">CALL</a>
							<a class="button hollow" href="mailto:clloyd@salefish.app">EMAIL</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>


</main>

<?php
get_footer();

?>