<?php

/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _pc
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta
		charset="<?php bloginfo('charset'); ?>">
	<meta content='width=device-width, initial-scale=1.0, viewport-fit=cover' name='viewport' />
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/icon-152x152.png">
	<link rel="apple-touch-icon" sizes="167x167" href="/icon-167x167.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/icon-96x96.png">
	<link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
	<link rel="manifest" href="/site.webmanifest">
	<meta name="msapplication-TileColor" content="#452D8C">
	<meta name="msapplication-TileImage" content="/mstile-150x150.png">
	<meta name="msapplication-config" content="/browserconfig.xml">
	<!-- PWA / Add to Home Screen -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="SaleFish">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="application-name" content="SaleFish">
	<?php
	// Match theme-color to the header that will be shown on this page.
	// Light-header pages (white bg) → white. All others → brand purple.
	$_sf_light_header = (
		is_page_template( 'page-contact-us.php' )    ||
		is_page_template( 'page-awards.php' )         ||
		is_page_template( 'page-privacy-policy.php' ) ||
		is_page_template( 'page-terms-of-use.php' )   ||
		is_page_template( 'page-blog.php' )            ||
		is_page_template( 'page-blog-filter.php' )     ||
		is_singular( 'post' )
	);
	$_sf_theme_color = $_sf_light_header ? '#ffffff' : '#452D8C';
	?>
	<meta name="theme-color" content="<?php echo esc_attr( $_sf_theme_color ); ?>">
	<link rel="preload" as="image" href="<?php echo esc_url( get_template_directory_uri() ); ?>/img/dark_salefish_logo.png">
	<?php wp_head(); ?>
	<script>
		if ('serviceWorker' in navigator) {
			window.addEventListener('load', function () {
				navigator.serviceWorker.register('/sw.js');
			});
		}
	</script>

</head>

<style>
	.cdp-copy-loader-overlay{
		display: none;
	}
</style>

<!-- Live Chat -->
<script src="//code.tidio.co/yz7mnwj2v5al4l2zvbdjaqbuge76emda.js" async></script>


<!-- Google Tag Manager -->
<script>
	(function(w, d, s, l, i) {
		w[l] = w[l] || [];
		w[l].push({
			'gtm.start': new Date().getTime(),
			event: 'gtm.js'
		});
		var f = d.getElementsByTagName(s)[0],
			j = d.createElement(s),
			dl = l != 'dataLayer' ? '&l=' + l : '';
		j.async = true;
		j.src =
			'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
		f.parentNode.insertBefore(j, f);
	})(window, document, 'script', 'dataLayer', 'GTM-5CX687F');
</script>
<!-- End Google Tag Manager -->


<a href="#main-content" class="skip-link">Skip to main content</a>

<header class="default" >
	<div class="max_wrapper" data-aos="fade-down" data-aos-delay="200">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish">
			</a>
		</div>
		<nav>
			<div class="languages">
				<div class="flag_active">
				</div>
				<div class="arrow">
					<i data-lucide="chevron-down" class="down_arrow" width="15" height="15"></i>
				</div>
				<div class="languages_option">
					<ul>
						<li>
							<a href="/au" aria-label="Australia (English)"><span class="flag">🇦🇺</span></a>
						</li>
						<li>
							<a href="/de" aria-label="Germany (Deutsch)"><span class="flag">🇩🇪</span></a>
						</li>
						<li>
							<a href="/tr" aria-label="Turkey (Türkçe)"><span class="flag">🇹🇷</span></a>
						</li>
					</ul>
				</div>
			</div>
			<ul>
				<li class="features_nav">
					<a href="/#features">Features</a>
				</li>
				<li class="our_story_nav">
					<a href="/our-story">Our Story</a>
				</li>
				<li class="awards_nav">
					<a href="/awards">Awards</a>
				</li>
				<li class="partners_nav">
					<a href="/partners">Partners</a>
				</li>
				<li class="blog_nav">
					<a href="/blog">Blog</a>
				</li>
				<li class="contact_us_nav">
					<a href="/contact-us">Contact Us</a>
				</li>
			</ul>
			<div class="menu">
				<button class="sf-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false">
					<i data-lucide="menu" class="icon-grid"></i>
					<i data-lucide="x" class="icon-close"></i>
				</button>
			</div>
		</nav>
	</div>
</header>

<header class="blog_header">
	<div class="max_wrapper" data-aos="fade-down" data-aos-delay="200">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish">
			</a>
		</div>
		<nav>
			<div class="languages">
				<div class="flag_active">
				</div>
				<div class="arrow">
					<i data-lucide="chevron-down" class="down_arrow" width="15" height="15"></i>
				</div>
				<div class="languages_option">
					<ul>
						<li>
							<a href="/au" aria-label="Australia (English)"><span class="flag">🇦🇺</span></a>
						</li>
						<li>
							<a href="/de" aria-label="Germany (Deutsch)"><span class="flag">🇩🇪</span></a>
						</li>
						<li>
							<a href="/tr" aria-label="Turkey (Türkçe)"><span class="flag">🇹🇷</span></a>
						</li>
					</ul>
				</div>
			</div>
			<ul>
				<li class="features_li">
					<a href="/#features">Features</a>
				</li>
				<li class="our_story_nav">
					<a href="/our-story">Our Story</a>
				</li>
				<li class="awards_nav">
					<a href="/awards">Awards</a>
				</li>
				<li class="partners_nav">
					<a href="/partners">Partners</a>
				</li>
				<li class="blog_nav">
					<a href="/blog">Blog</a>
				</li>
				<li class="contact_us_nav">
					<a href="/contact-us">Contact Us</a>
				</li>
			</ul>
			<div class="menu">
				<button class="sf-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false">
					<i data-lucide="menu" class="icon-grid"></i>
					<i data-lucide="x" class="icon-close"></i>
				</button>
			</div>
		</nav>
	</div>

</header>

<header class="de_header">
	<div class="max_wrapper" data-aos="fade-down" data-aos-delay="200">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish">
			</a>
		</div>
		<nav>
			<div class="languages">
				<div class="flag_active">
				</div>
				<div class="arrow">
					<i data-lucide="chevron-down" class="down_arrow" width="15" height="15"></i>
				</div>
				<div class="languages_option">
					<ul>
						<li>
							<a href="/au" aria-label="Australia (English)"><span class="flag">🇦🇺</span></a>
						</li>
						<li>
							<a href="/de" aria-label="Germany (Deutsch)"><span class="flag">🇩🇪</span></a>
						</li>
						<li>
							<a href="/tr" aria-label="Turkey (Türkçe)"><span class="flag">🇹🇷</span></a>
						</li>
					</ul>
				</div>
			</div>
			<ul>
				<li class="features_li">
					<a href="/#features">MERKMALE</a>
				</li>
				<li class="partners_nav">
					<a href="/partners">PARTNER</a>
				</li>
				<li class="blog_nav">
					<a href="/blog">BLOG</a>
				</li>
				<li class="contact_us_nav">
					<a href="/contact-us">KONTAKTIERE UNS</a>
				</li>
			</ul>
			<div class="menu">
				<button class="sf-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false">
					<i data-lucide="menu" class="icon-grid"></i>
					<i data-lucide="x" class="icon-close"></i>
				</button>
			</div>
		</nav>
	</div>
</header>

<header class="tr_header">
	<div class="max_wrapper" data-aos="fade-down" data-aos-delay="200">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish">
			</a>
		</div>
		<nav>
			<div class="languages">
				<div class="flag_active">
				</div>
				<div class="arrow">
					<i data-lucide="chevron-down" class="down_arrow" width="15" height="15"></i>
				</div>
				<div class="languages_option">
					<ul>
						<li>
							<a href="/au" aria-label="Australia (English)"><span class="flag">🇦🇺</span></a>
						</li>
						<li>
							<a href="/de" aria-label="Germany (Deutsch)"><span class="flag">🇩🇪</span></a>
						</li>
						<li>
							<a href="/tr" aria-label="Turkey (Türkçe)"><span class="flag">🇹🇷</span></a>
						</li>
					</ul>
				</div>
			</div>
			<ul>
				<li class="features_li">
					<a href="/#features">ÖZELLİKLERİ</a>
				</li>

				<li class="blog_nav">
					<a href="/blog">BLOG</a>
				</li>
				<li class="partners_nav">
					<a href="/partners">ORTAKLAR</a>
				</li>
				<li class="contact_us_nav">
					<a href="/tr/contact">BİZE ULAŞIN </a>
				</li>
			</ul>
			<div class="menu">
				<button class="sf-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false">
					<i data-lucide="menu" class="icon-grid"></i>
					<i data-lucide="x" class="icon-close"></i>
				</button>
			</div>
		</nav>
	</div>

</header>




<div class="loading" style="background-image: url('<?php echo esc_url( get_template_directory_uri() ); ?>/img/dark_salefish_logo.png')"></div>

<div class="floating_menu floating_menu_en">
	<div class="wrapper">
		<div class="wrap">
			<div class="top">
				<ul>
					<li class="mobile features_li">
						<a href="/#features">Features</a>
					</li>
					<li class="mobile our_story_nav">
						<a href="/our-story">Our Story</a>
					</li>
					<li class="mobile awards_nav">
						<a href="/awards">Awards</a>
					</li>
					<li class="mobile partners_nav">
						<a href="/partners">Partners</a>
					</li>
					<li class="mobile blog_nav">
						<a href="/blog">Blog</a>
					</li>
					<li class="mobile contact_us_nav">
						<a href="/contact-us">Contact Us</a>
					</li>
					<li class="mobile menu-sep"></li>
					<li>
						<a href="https://chatting.page/salefish" target="_blank" rel="noopener noreferrer">Live Chat Support</a>
					</li>
					<li>
						<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer">Sales App</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer">Highrise Admin</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer">Lowrise Admin</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>


<div class="floating_menu floating_menu_tr">
	<div class="wrapper">
		<div class="wrap">
			<div class="top">
				<ul>
					<li class="mobile features_li">
						<a href="/#features">Özelli̇kleri̇</a>
					</li>
					<li class="mobile blog_nav">
						<a href="/blog">Blog</a>
					</li>
					<li class="mobile partners_nav">
						<a href="/partners">Ortaklar</a>
					</li>
					
					<li class="mobile contact_us_nav">
						<a href="/tr/contact">Bi̇ze Ulaşin</a>
					</li>
	
					<li class="our_story_nav">
						<a href="/our-story">Bizim Hikayemiz</a>
					</li>
		
					<li class="awards_nav">
						<a href="/awards">Ödüller</a>
					</li>
			
					<li class="mobile">
						<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer">Sales App</a>
					</li>
					<li class="mobile">
						<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer">Highrise
							Admin</a>
					</li>
					<li class="mobile">
						<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer">Lowrise
							Admin</a>
					</li>
				</ul>
			</div>
			<hr>
			<div class="bottom">
				<img class="fish"
					src="<?php echo get_template_directory_uri(); ?>/img/fish.png"
					alt="Salefish">
				<a href="tel:+905333311236" class="hover-main-menu-style">+90 533 331 12 36</a>
				<a href="tel:+902122341494" class="hover-main-menu-style">+90 212 234 14 94</a>
				<a class="email hover-main-menu-style-email" href="mailto:hello@salefish.app">hello@salefish.app</a>
			</div>
		</div>
	</div>
</div>

<div class="floating_menu floating_menu_de">
	<div class="wrapper">
		<div class="wrap">
			<div class="top">
				<ul>
					<li class="mobile features_li">
						<a href="/#features">Merkmale</a>
					</li>
					<li class="mobile blog_nav">
						<a href="/blog">Blog</a>
					</li>
					<li class="mobile partners_nav">
						<a href="/partners">Partner</a>
					</li>
					<li class="mobile contact_us_nav">
						<a href="/contact-us">Kontaktiere Uns</a>
					</li>
					<li class="our_story_nav">
						<a href="/our-story">Unsere Geschichte</a>
					</li>
				
					<li class="awards_nav">
						<a href="/awards">Auszeichnungen
						</a>
					</li>
			
					<li class="mobile">
						<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer">Sales App</a>
					</li>
					<li class="mobile">
						<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer">Highrise
							Admin</a>
					</li>
					<li class="mobile">
						<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer">Lowrise
							Admin</a>
					</li>
				</ul>
			</div>
			<hr>
			<div class="bottom">
				<img class="fish"
					src="<?php echo get_template_directory_uri(); ?>/img/fish.png"
					alt="Salefish">
				<a href="tel:+18778927741" class="hover-main-menu-style">1.877.892.7741</a>
				<a href="tel:+19057615364" class="hover-main-menu-style">1.905.761.5364</a>
				<a class="email hover-main-menu-style-email" href="mailto:hello@salefish.app">hello@salefish.app</a>
			</div>
		</div>
	</div>
</div>

<div class="sales_login_menu">
	<div class="wrapper">
		<div class="wrap">
			<ul>
				<li>
					<a href="https://chatting.page/salefish" target="_blank" rel="noopener noreferrer">Live Chat Support</a>
				</li>
				<li>
					<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer">Sales App</a>
				</li>
				<li>
					<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer">Highrise Admin</a>
				</li>
				<li>
					<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer">Lowrise Admin</a>
				</li>
			</ul>
		</div>
	</div>

</div>

<div class="sf-check-email-msg">
	<div class="sf-check-email-msg__backdrop sf-check-email-close"></div>
	<div class="sf-check-email-msg__panel">
		<button class="sf-check-email-msg__close sf-check-email-close" aria-label="Close">
			<i data-lucide="x"></i>
		</button>
		<div class="sf-check-email-msg__icon-wrap">
			<i data-lucide="mail"></i>
		</div>
		<span class="sf-check-email-msg__eyebrow">Almost there</span>
		<h2 class="sf-check-email-msg__heading">Check your email.</h2>
		<p class="sf-check-email-msg__body">
			We sent a confirmation link to <strong class="sf-check-email-msg__address"></strong>. Click it to complete your registration — the link expires in 48 hours.
		</p>
	</div>
</div>

<!-- Book a Demo Modal -->
<div class="sf-demo-modal" id="sf-demo-modal" role="dialog" aria-modal="true" aria-label="Book a Free Demo">
	<div class="sf-demo-modal__backdrop"></div>
	<div class="sf-demo-modal__panel">
		<div class="sf-demo-modal__header">
			<span class="sf-demo-modal__title">Book a Free Demo</span>
			<button class="sf-demo-modal__close" aria-label="Close dialog">
				<i data-lucide="x"></i>
			</button>
		</div>
		<iframe class="sf-demo-modal__frame" src="" frameborder="0" title="Book a Free Demo — SaleFish"></iframe>
	</div>
</div>

<div class="thank_you_msg">
	<div class="thank_you_msg__backdrop close_thank_you_msg"></div>
	<div class="thank_you_msg__panel">
		<button class="thank_you_msg__close close_thank_you_msg" aria-label="Close">
			<i data-lucide="x"></i>
		</button>
		<span class="thank_you_msg__eyebrow">Message received</span>
		<h2 class="thank_you_msg__heading">Here’s what happens next.</h2>
		<p class="thank_you_msg__body">
			A SaleFish specialist will reach out within 1 business day to walk you through the platform and talk through your specific project needs — whether that’s inventory management, online signing, or end-to-end pre-construction sales workflow.
		</p>
	</div>
</div>

<div class="privacy_policy">
	<?php
        $content = get_post(48)
?>
	<div class="wrapper">
		<h1>Privacy Policy</h1>
		<i data-lucide="x" class="close_privacy"></i>
		<div class="wrap">
			<?php echo wp_kses_post( $content->post_content ); ?>

		</div>

	</div>
</div>
<div class="terms_popup">
	<?php
    $content = get_post(39)
?>
	<div class="wrapper">
		<h1>Terms of Use</h1>
		<i data-lucide="x" class="close_terms"></i>
		<div class="wrap">
			<?php echo wp_kses_post( $content->post_content ); ?>

		</div>

	</div>
</div>

<?php
    $banner = get_field('banner', 'option');
if ($banner != ''):
    ?>
<div class="profile_popup">
	<div class="wrapper">
		<div class="close_profile_popup">
			<svg class="profile_popup_close" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
				width="24px" fill="#e8eaed">
				<path
					d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
			</svg>
		</div>
		<h1>Please Select <br />your role</h1>
		<div class="role">
			<div class="item item_1">
				<svg class="svg_1" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
					width="24px" fill="#e8eaed">
					<path
						d="M411-480q-28 0-46-21t-13-49l12-72q8-43 40.5-70.5T480-720q44 0 76.5 27.5T597-622l12 72q5 28-13 49t-46 21H411Zm24-80h91l-8-49q-2-14-13-22.5t-25-8.5q-14 0-24.5 8.5T443-609l-8 49ZM124-441q-23 1-39.5-9T63-481q-2-9-1-18t5-17q0 1-1-4-2-2-10-24-2-12 3-23t13-19l2-2q2-19 15.5-32t33.5-13q3 0 19 4l3-1q5-5 13-7.5t17-2.5q11 0 19.5 3.5T208-626q1 0 1.5.5t1.5.5q14 1 24.5 8.5T251-596q2 7 1.5 13.5T250-570q0 1 1 4 7 7 11 15.5t4 17.5q0 4-6 21-1 2 0 4l2 16q0 21-17.5 36T202-441h-78Zm676 1q-33 0-56.5-23.5T720-520q0-12 3.5-22.5T733-563l-28-25q-10-8-3.5-20t18.5-12h80q33 0 56.5 23.5T880-540v20q0 33-23.5 56.5T800-440ZM0-240v-63q0-44 44.5-70.5T160-400q13 0 25 .5t23 2.5q-14 20-21 43t-7 49v65H0Zm240 0v-65q0-65 66.5-105T480-450q108 0 174 40t66 105v65H240Zm560-160q72 0 116 26.5t44 70.5v63H780v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5Zm-320 30q-57 0-102 15t-53 35h311q-9-20-53.5-35T480-370Zm0 50Zm1-280Z" />
				</svg>
				<h2>BUILDER, DEVELOPER <span>OR SALES TEAM</span></h2>
				<a class="profile_popup_close" href="#features">SALES APPS</a>
			</div>
		</div>
		<p class="profile_popup_close">
			Not sure? <span>Explore without choosing a role.</span>
		</p>
	</div>
</div>
<?php endif; ?>

<body <?php body_class(); ?>>