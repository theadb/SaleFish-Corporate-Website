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
		<img class="hero_bg"
			src="<?php bloginfo('template_directory'); ?>/img/our_story/hero.jpg"
			alt="Living Room">
		<div class="wrapper" data-aos="fade-right" data-aos-delay="300">
			<div class="wrap">
				<h1>THE BEST WAY <br />
					TO SELL REAL ESTATE. <br />
					PERIOD.</h1>
				<a class="button" target="_blank" href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c">Make every sale a mic drop moment</a>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- HERO CONTENT -->
	<section class="hero_content">
		<div class="wrap">
			<h1 data-aos="fade-up">
				TURN GREAT REAL ESTATE INTO EFFORTLESS SALES
			</h1>
			<p data-aos="fade-up">
				You might not care about SaleFish’s 15 years of experience
				making new real estate sales easier.
			</p>
			<p data-aos="fade-up">
				We get it: in this industry, the only project that really
				matters is your next one.
			</p>
			<p data-aos="fade-up">
				But with 1.5+ million users and 200,000+ transactions valued at
				over $100+ billion USD, you should pay attention. If the simple,
				streamlined, and secure SaleFish platform makes selling that
				easy for everyone else? Imagine what we can do for you.
			</p>
		</div>
	</section>
	<!-- END HERO CONTENT -->

	<!-- PLATFORM -->
	<section class="platform">
		<div class="max_wrapper">
			<h1 data-aos="fade-up">
				GET MORE, DO MORE, SELL MORE
			</h1>
			<p data-aos="fade-up">
				What does the latest SaleFish release have to offer? What you’d
				expect — only the best.
			</p>

			<p data-aos="fade-up">
				That means the most up-to-date web standards. Real-time unit info.
				And seamless integration into every project.
			</p>

			<p data-aos="fade-up">
				Those are the table stakes.
			</p>

			<p data-aos="fade-up">
				With our enterprise integrations, unparalleled process efficiency,
				and exacting cybersecurity standards, you also get one of the
				world’s most powerful cloud-based real estate sales solutions. Or
				not. The decision is yours.
			</p>

			</br></br></br></br>

			<h1 data-aos="fade-up">
				YOUR BRAND, FRONT & CENTER
			</h1>

			<p data-aos="fade-up">
				Your buyers don’t want to buy from someone else.
			</p>

			<p data-aos="fade-up">
				That’s why SaleFish integrates seamlessly into your workflows,
				solutions, and brand assets. One modern, intuitive user experience
				that builds trust from the start until the deal is done.
			</p>

			</br></br></br></br>

			<h1 data-aos="fade-up">
				WORKSHEETS THAT MEAN LESS WORK
			</h1>
			<p data-aos="fade-up">
				Ready to idiot-proof your planning & sales process?
			</p>

			<p data-aos="fade-up">
				SaleFish’s plug-and-play modular worksheets integrate directly into
				existing broker portals and websites so no one gets confused —
				whether it’s pre-coffee or post-happy hour.
			</p>

			<p data-aos="fade-up">
				Better yet, the SaleFish admin portal ensures easy worksheet
				allocation and organization. You’re bigger than the busywork.
			</p>


			<a class="button" target="_blank" href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c">BOOK A FREE DEMO</a>
			<img data-aos="zoom-in" class="white_labeling"
				src="https://salefish.app/wp-content/uploads/2024/09/Group-1441.png">
		</div>

	</section>
	<!-- END PLATFORM -->

	<!-- SECURITY -->
	<section class="security">
		<div class="max_wrapper">
			<h1 data-aos="fade-up">CERTAINTY SELLS</h1>
			<p data-aos="fade-up">
				Your buyers are already nervous come transaction time. To close
				sales, you need to deliver total peace of mind.
			</p>
			<p data-aos="fade-up">
				With SaleFish, your sales team gets:</br>
				• Best-in-class digital document e-signatures</br>
				• ID scanning and identity verification (FINTRAC and FinCEN
				compliant)</br>
				• The ability to auto-populate critical information by scanning
				drivers’ licenses

			</p>
			<p data-aos="fade-up">
				SaleFish also helps you separate the bots from your buyers. For
				remote purchases, facial recognition compares each person’s face
				to their ID. so you can be sure that you’ve convinced a real
				human to sign on the dotted line.

			</p>

			</br></br></br></br>

			<h1 data-aos="fade-up">SECURITY SELLS</h1>
			<p data-aos="fade-up">
				Here’s something that no one else can tell you.
			</p>
			<p data-aos="fade-up">
				SaleFish is the ONLY real estate platform to have a CyberSecure
				Canada certification for meeting the highest levels of security
				and international cybersecurity standards. You only get the best
				— from our tech, our team, and our policies.

			</p>
			<p data-aos="fade-up">
				If anyone else says the same? Well, you’ve probably exaggerated
				to make a sale, too.

			</p>

			<a href="https://ised-isde.canada.ca/site/cybersecure-canada/en" target="_blank" rel="noopener noreferrer">
				<img data-aos="zoom-in"
					src="https://salefish.app/wp-content/uploads/2024/09/Group-1427.jpg">
			</a>
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
					“Builders, developers, and sales teams don’t want to be sold
					to. But the SaleFish experience speaks for itself. Their job
					has never been easier.“
				</h2>
				<p>
					RICK HAWS <br />
					PRESIDENT & CO-FOUNDER
				</p>
			</div>
			<a class="button" target="_blank" href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c">BOOK A FREE DEMO</a>
		</div>
		<?php get_template_part('/partials/contact-general'); ?>

	</section>
	<!-- END CONTACT -->
</main>


<?php get_footer(); ?>