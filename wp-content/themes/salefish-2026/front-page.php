<?php
/**
 * Template Name: Home Page
 */
get_header();

$fade_msg = get_field('fade_messages');
$fade = array();
if ( $fade_msg ) {
    foreach ($fade_msg as $msg) {
        array_push($fade, $msg['text']);
    }
}
$hero_header    = get_field('hero_header');
$hero_image     = get_field('hero_image');
$builders       = get_field('builders_developers');
$pillars        = get_field('pillars');
$numbers_header = get_field('numbers_header');
$the_numbers    = get_field('the_numbers');
?>

<script>let textArray = <?php echo wp_json_encode( $fade, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;</script>

<main class="home">

	<div class="selling_popup_container">
		<div class="selling_popup">
			<a href="#contact_us" class="scroll_link button-image">
				<img src="<?php bloginfo('template_directory'); ?>/img/popup.png" class="popup">
			</a>
			<img src="<?php bloginfo('template_directory'); ?>/img/x-closed.svg" class="close_icon">
		</div>
	</div>

	<!-- HERO -->
	<section class="home-hero sf-section">
		<div class="sf-container">
			<div class="home-hero__inner">
				<div class="home-hero__text" data-aos="fade-right" data-aos-delay="200">
					<span class="sf-badge sf-badge--ocean home-hero__eyebrow">AN EASIER WAY TO SELL <label id="app_for_home">HOME SALES</label></span>
					<h1><?php echo wp_kses_post( $hero_header ); ?></h1>
					<div class="home-hero__ctas">
						<a class="sf-btn" target="_blank" rel="noopener noreferrer"
							href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c">
							See Why They're Beating You
						</a>
						<a class="sf-btn sf-btn--ghost" href="#features">See the Platform</a>
					</div>
				</div>
				<?php if ( $hero_image ): ?>
				<div class="home-hero__image" data-aos="zoom-in" data-aos-delay="300">
					<img src="<?php echo esc_url( $hero_image ); ?>" alt="SaleFish App Demo" class="home-hero__img" fetchpriority="high" loading="eager">
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- BUILDERS LOGO STRIP -->
	<?php if ( $builders ): ?>
	<section class="home-builders sf-section--sm">
		<div class="sf-container">
			<p class="home-builders__label">TRUSTED BY THE REAL ESTATE LEADERS BEATING YOU BECAUSE OF SALEFISH:</p>
			<div class="home-builders__carousel">
				<div class="swiper buildersSwiper">
					<div class="swiper-wrapper">
						<?php foreach ( $builders as $logo_url ): ?>
						<div class="swiper-slide">
							<img class="home-builders__logo" src="<?php echo esc_url( $logo_url ); ?>" alt="">
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="home-builders__mobile">
				<?php foreach ( $builders as $logo_url ): ?>
				<img class="home-builders__logo" src="<?php echo esc_url( $logo_url ); ?>" alt="">
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END BUILDERS -->

	<!-- FEATURES (partial — ACF options field) -->
	<?php get_template_part('/partials/salefish-features'); ?>
	<!-- END FEATURES -->

	<!-- STATS STRIP -->
	<?php if ( $the_numbers ): ?>
	<section class="home-stats sf-gradient-primary">
		<div class="sf-container">
			<?php if ( $numbers_header ): ?>
			<div class="home-stats__header">
				<h2><?php echo esc_html( $numbers_header['title'] ); ?></h2>
				<p><?php echo wp_kses_post( $numbers_header['description'] ); ?></p>
			</div>
			<?php endif; ?>
			<div class="home-stats__grid">
				<?php $counter = 0; foreach ( $the_numbers as $row ): $counter++; ?>
				<div class="home-stats__item">
					<div class="home-stats__number">
						<?php echo esc_html( $row['prefix'] ); ?><span
							data-number="<?php echo esc_attr( $row['number'] ); ?>"
							id="count_<?php echo esc_attr( $counter ); ?>"></span><?php echo esc_html( $row['suffix'] ); ?>
					</div>
					<p class="home-stats__label"><?php echo wp_kses_post( $row['description'] ); ?></p>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="home-stats__cta">
				<a class="sf-btn" target="_blank" rel="noopener noreferrer"
					href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c">
					Get My Demo
				</a>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END STATS -->

	<!-- PILLARS -->
	<?php if ( $pillars ): ?>
	<section class="home-pillars sf-section">
		<div class="sf-container">
			<div class="home-pillars__header" data-aos="fade-up">
				<h2>The SaleFish Pillars</h2>
				<p>We Build Tools for People Who Expect to Win.</p>
			</div>
			<div class="home-pillars__grid" data-aos="fade-up" data-aos-delay="100">
				<?php foreach ( $pillars as $row ): ?>
				<div class="sf-card home-pillars__card">
					<?php if ( $row['icon'] ): ?>
					<img class="home-pillars__icon" src="<?php echo esc_url( $row['icon'] ); ?>" alt="">
					<?php endif; ?>
					<h3><?php echo esc_html( $row['title'] ); ?></h3>
					<p><?php echo wp_kses_post( $row['description'] ); ?></p>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END PILLARS -->

	<!-- CONTACT FORM -->
	<section class="home-contact sf-section" id="contact_us">
		<div class="sf-container">
			<?php get_template_part('/partials/contact-general'); ?>
		</div>
	</section>
	<!-- END CONTACT -->

</main>

<?php get_footer(); ?>
