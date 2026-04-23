<?php
/**
 * Template Name: Our Story Page
 * The template for displaying the our story page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */


get_header();
?>

<main class="our_story">
	<!-- HERO -->
	<section class="hero">
		<div class="hero__slideshow" aria-hidden="true">
			<div class="hero__slide is-active">
				<img src="<?php echo get_template_directory_uri(); ?>/img/hero-slides/new-construction-sales-platform-condo-tower-2560w.png" alt="" loading="eager">
			</div>
			<div class="hero__slide">
				<img src="<?php echo get_template_directory_uri(); ?>/img/hero-slides/new-home-developer-platform-townhomes-2560w.png" alt="" loading="lazy">
			</div>
			<div class="hero__slide">
				<img src="<?php echo get_template_directory_uri(); ?>/img/hero-slides/new-home-sales-software-suburban-community-2560w.png" alt="" loading="lazy">
			</div>
		</div>
		<div class="hero__overlay" aria-hidden="true"></div>
		<div class="wrapper">
			<div class="wrap">
				<span class="eyebrow" data-aos="fade-up" data-aos-delay="50">The SaleFish Story</span>
				<h1 data-aos="fade-up" data-aos-delay="100">The Best Way <br />
					to Sell Real Estate. <br />
					Period.</h1>
				<a class="button" data-aos="fade-up" data-aos-delay="300" href="javascript:void(0)" data-sf-modal="register" data-sf-section="Our Story — Hero">Make every sale a mic drop moment</a>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- INTRO -->
	<section class="intro">
		<div class="intro__wrap">
			<span class="eyebrow" data-aos="fade-up">About SaleFish</span>
			<h1 data-aos="fade-up">Turn Great Real Estate Into Effortless Sales</h1>
			<p data-aos="fade-up">You might not care about SaleFish's 15 years of experience making new real estate sales easier. We get it: in this industry, the only project that really matters is your next one. But with 1.5+ million users and 200,000+ transactions worth over $100 billion USD — you should pay attention. If the simple, streamlined SaleFish platform makes selling this easy for everyone else, imagine what we can do for you.</p>
		</div>
	</section>
	<!-- END INTRO -->

	<!-- STATS BAR -->
	<section class="stats_bar">
		<div class="stats_bar__grid" data-aos="fade-up">
			<div class="stat">
				<strong>15+</strong>
				<span>Years in Business</span>
			</div>
			<div class="stat">
				<strong>1.5M+</strong>
				<span>Users</span>
			</div>
			<div class="stat">
				<strong>200K+</strong>
				<span>Transactions</span>
			</div>
			<div class="stat">
				<strong>$100B+</strong>
				<span>USD Transacted</span>
			</div>
		</div>
	</section>
	<!-- END STATS BAR -->

	<!-- PLATFORM -->
	<section class="platform">
		<div class="platform__wrap">

			<div class="feature_cards">
				<div class="card" data-aos="fade-up">
					<h2>Get More, Do More, Sell More</h2>
					<p>What does the latest SaleFish release offer? The most up-to-date web standards. Real-time unit info. Seamless integration into every project. With enterprise integrations and exacting cybersecurity standards, you get one of the world's most powerful cloud-based real estate sales solutions.</p>
				</div>
				<div class="card" data-aos="fade-up" data-aos-delay="100">
					<h2>Your Brand, Front &amp; Center</h2>
					<p>Your buyers don't want to buy from someone else. That's why SaleFish integrates seamlessly into your workflows, solutions, and brand assets — one modern, intuitive user experience that builds trust from start to finish.</p>
				</div>
				<div class="card" data-aos="fade-up" data-aos-delay="200">
					<h2>Worksheets That Mean Less Work</h2>
					<p>Plug-and-play modular worksheets integrate directly into existing broker portals and websites — pre-coffee or post-happy hour. The admin portal ensures easy worksheet allocation and organization. You're bigger than the busywork.</p>
				</div>
			</div>

			<div class="platform__showcase" data-aos="zoom-in">
				<img src="https://salefish.app/wp-content/uploads/2024/09/Group-1441.png" alt="SaleFish Platform" loading="lazy" decoding="async">
				<a class="button" href="javascript:void(0)" data-sf-modal="register" data-sf-section="Our Story — Platform Showcase">See It in Action</a>
			</div>

		</div>
	</section>
	<!-- END PLATFORM -->

	<!-- SECURITY -->
	<section class="security">
		<div class="security__wrap">
			<div class="security__split">
				<div class="security__text" data-aos="fade-right">
					<h2>Certainty &amp; Security Sell</h2>
					<p>Your buyers are already nervous come transaction time. To close sales, you need to deliver total peace of mind. With SaleFish, your sales team gets:</p>
					<ul>
						<li>Best-in-class digital document e-signatures</li>
						<li>ID scanning and identity verification (FINTRAC and FinCEN compliant)</li>
						<li>Auto-population of critical information by scanning drivers' licences</li>
						<li>Facial recognition for remote purchase verification</li>
					</ul>
					<p>SaleFish is the <strong>only</strong> real estate platform with a CyberSecure Canada certification — meeting the highest levels of international cybersecurity standards. You only get the best, from our tech, our team, and our policies.</p>
				</div>
				<div class="security__badge" data-aos="fade-left">
					<div class="platform__img-stack">
						<div class="platform__img-slide is-active">
							<img src="<?php echo get_template_directory_uri(); ?>/img/homepage/hero-homes-v2.png" alt="SaleFish — Single Family Homes" loading="eager" decoding="async">
						</div>
						<div class="platform__img-slide">
							<img src="<?php echo get_template_directory_uri(); ?>/img/homepage/hero-condos-v2.png" alt="SaleFish — Condos" loading="lazy" decoding="async">
						</div>
						<div class="platform__img-slide">
							<img src="<?php echo get_template_directory_uri(); ?>/img/homepage/hero-townhomes-a-v2.png" alt="SaleFish — Townhomes" loading="lazy" decoding="async">
						</div>
						<div class="platform__img-slide">
							<img src="<?php echo get_template_directory_uri(); ?>/img/homepage/hero-townhomes-b-v2.png" alt="SaleFish — Townhomes" loading="lazy" decoding="async">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- END SECURITY -->

	<!-- CONTACT -->
	<section class="contact">
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<div class="top">
			<div class="top_content_center" data-aos="fade-up">
				<h2>
					"Builders, developers, and sales teams don't want to be sold
					to. But the SaleFish experience speaks for itself. Their job
					has never been easier."
				</h2>
				<p>
					Rick Haws <br />
					President &amp; Co-Founder
				</p>
			</div>
			<a class="button" href="javascript:void(0)" data-sf-modal="register" data-sf-section="Our Story — Contact CTA">Get My Demo</a>
		</div>
		<?php get_template_part('/partials/contact-general'); ?>

	</section>
	<!-- END CONTACT -->
</main>


<?php get_footer(); ?>
