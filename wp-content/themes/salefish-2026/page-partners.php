<?php
/**
 * Template Name: Partners Page
 */
get_header();

$fade_msg = get_field('fade_messages');
$fade = array();
if ( $fade_msg ) {
    foreach ($fade_msg as $msg) {
        array_push($fade, $msg['text']);
    }
}
$hero_header      = get_field('hero_header');
$hero_description = get_field('hero_description');
$hero_image       = get_field('hero_image');
$builders_devs    = get_field('builders_developers');
$agents           = get_field('agents');
$agents_header    = get_field('agents_header');
$builders         = get_field('builders');
$builders_header  = get_field('builders_header');
?>

<script>let textArray = <?php echo wp_json_encode( $fade, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;</script>

<main class="partners">

	<!-- HERO -->
	<section class="partners-hero sf-section">
		<div class="sf-container">
			<div class="partners-hero__inner">
				<div class="partners-hero__text" data-aos="fade-right">
					<span class="sf-badge sf-badge--ocean">Partners</span>
					<h1><?php echo wp_kses_post( $hero_header ); ?></h1>
					<?php if ( $hero_description ): ?>
					<p><?php echo wp_kses_post( $hero_description ); ?></p>
					<?php endif; ?>
					<a class="sf-btn" href="#contact_us">Let's Partner Up</a>
				</div>
				<?php if ( $hero_image ): ?>
				<div class="partners-hero__image" data-aos="zoom-in" data-aos-delay="200">
					<img src="<?php echo esc_url( $hero_image ); ?>" alt="Partners">
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- AGENTS SECTION -->
	<?php if ( $agents && $agents_header ): ?>
	<section class="partners-logos sf-section">
		<div class="sf-container">
			<div class="partners-logos__header" data-aos="fade-up">
				<h2><?php echo esc_html( $agents_header['title'] ); ?></h2>
				<p><?php echo wp_kses_post( $agents_header['content'] ); ?></p>
				<?php if ( $agents_header['button']['link'] ): ?>
				<a class="sf-btn" href="<?php echo esc_url( $agents_header['button']['link'] ); ?>">
					<?php echo esc_html( $agents_header['button']['text'] ); ?>
				</a>
				<?php endif; ?>
			</div>
			<div class="partners-logos__grid" data-aos="fade-up" data-aos-delay="100">
				<?php foreach ( $agents as $logo ):
					$src = is_array($logo) ? ($logo['url'] ?? $logo) : $logo;
				?>
				<div class="partners-logos__item">
					<img src="<?php echo esc_url( $src ); ?>" alt="">
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END AGENTS -->

	<!-- BUILDERS SECTION -->
	<?php if ( $builders && $builders_header ): ?>
	<section class="partners-logos partners-logos--alt sf-section">
		<div class="sf-container">
			<div class="partners-logos__header" data-aos="fade-up">
				<h2><?php echo esc_html( $builders_header['title'] ); ?></h2>
				<p><?php echo wp_kses_post( $builders_header['content'] ); ?></p>
				<?php if ( $builders_header['button']['link'] ): ?>
				<a class="sf-btn" href="<?php echo esc_url( $builders_header['button']['link'] ); ?>">
					<?php echo esc_html( $builders_header['button']['text'] ); ?>
				</a>
				<?php endif; ?>
			</div>
			<div class="partners-logos__grid" data-aos="fade-up" data-aos-delay="100">
				<?php foreach ( $builders as $logo ):
					$src = is_array($logo) ? ($logo['url'] ?? $logo) : $logo;
				?>
				<div class="partners-logos__item">
					<img src="<?php echo esc_url( $src ); ?>" alt="">
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END BUILDERS -->

	<!-- CONTACT FORM -->
	<section class="sf-section" id="contact_us">
		<div class="sf-container">
			<?php get_template_part('/partials/contact-general'); ?>
		</div>
	</section>
	<!-- END CONTACT -->

</main>

<?php get_footer(); ?>
