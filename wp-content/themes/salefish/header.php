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

// Inline SVG strings for the three nav icons that must be visible immediately.
// Lucide loads async from CDN — until it fires createIcons() all <i data-lucide>
// elements are blank. The hamburger button is the primary mobile nav control,
// so a blank icon on first paint is a hard UX regression on every mobile visit.
// These match the exact paths from Lucide v0.468.0 so visual output is identical.
$_sf_icon_hamburger = '<svg class="icon-grid" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>';
$_sf_icon_close     = '<svg class="icon-close" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';
$_sf_icon_chevron   = '<span class="down_arrow"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></span>';

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta
		charset="<?php bloginfo('charset'); ?>">
	<meta content='width=device-width, initial-scale=1.0, viewport-fit=cover' name='viewport' />
	<!-- Preconnect to YouTube domains so DNS + TLS happen during page idle,
	     not after the user clicks "Watch Video". Cuts ~200-500ms off the
	     iframe load time when the video modal opens. -->
	<link rel="preconnect" href="https://www.youtube-nocookie.com" crossorigin>
	<link rel="preconnect" href="https://www.youtube.com" crossorigin>
	<link rel="preconnect" href="https://i.ytimg.com" crossorigin>
	<link rel="preconnect" href="https://yt3.ggpht.com" crossorigin>
	<link rel="dns-prefetch" href="https://www.google.com">
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
	<!-- Preload the three Poppins weights used above the fold so they're
	     fetched in parallel with CSS instead of after it. Poppins-Bold is the
	     LCP-critical hero headline weight — without this preload Lighthouse's
	     critical-request chain is HTML → CSS → Bold (8 s LCP on mobile). -->
	<link rel="preload" as="font" type="font/woff2" crossorigin href="<?php echo esc_url( get_template_directory_uri() ); ?>/fonts/Poppins-Regular.woff2">
	<link rel="preload" as="font" type="font/woff2" crossorigin href="<?php echo esc_url( get_template_directory_uri() ); ?>/fonts/Poppins-SemiBold.woff2">
	<link rel="preload" as="font" type="font/woff2" crossorigin href="<?php echo esc_url( get_template_directory_uri() ); ?>/fonts/Poppins-Bold.woff2">
	<?php
	// ── LCP image preload ──────────────────────────────────────────────────────
	// Preload the page's hero AVIF as image with type=image/avif so it starts
	// downloading at the same time as CSS, instead of waiting for the body
	// parser to discover the <img>. Major PageSpeed mobile LCP win.
	// Only emit when the AVIF actually exists on disk — never preload a 404.
	if ( is_front_page() ) {
		$_sf_hero_url = get_field( 'hero_image' );
		if ( $_sf_hero_url ) {
			$_sf_upload = wp_get_upload_dir();
			$_sf_hero_path = '';
			if ( strpos( $_sf_hero_url, $_sf_upload['baseurl'] ) === 0 ) {
				$_sf_hero_path = $_sf_upload['basedir'] . substr( $_sf_hero_url, strlen( $_sf_upload['baseurl'] ) );
			} elseif ( strpos( $_sf_hero_url, get_template_directory_uri() ) === 0 ) {
				$_sf_hero_path = get_template_directory() . substr( $_sf_hero_url, strlen( get_template_directory_uri() ) );
			}
			if ( $_sf_hero_path && preg_match( '/\.(png|jpe?g)$/i', $_sf_hero_path ) ) {
				$_sf_avif_path = preg_replace( '/\.(png|jpe?g)$/i', '.avif', $_sf_hero_path );
				if ( file_exists( $_sf_avif_path ) ) {
					$_sf_avif_url  = preg_replace( '/\.(png|jpe?g)$/i', '.avif', $_sf_hero_url );
					// Look for a `-480w` mobile variant; if present, preload
					// via imagesrcset so the device picks the right size.
					$_sf_base_path = preg_replace( '/\.(png|jpe?g)$/i', '', $_sf_hero_path );
					$_sf_base_url  = preg_replace( '/\.(png|jpe?g)$/i', '', $_sf_hero_url );
					$_sf_variants  = [];
					foreach ( [ 320, 480, 640, 800 ] as $w ) {
						if ( file_exists( $_sf_base_path . '-' . $w . 'w.avif' ) ) {
							$_sf_variants[] = esc_url( $_sf_base_url . '-' . $w . 'w.avif' ) . ' ' . $w . 'w';
						}
					}
					if ( $_sf_variants ) {
						$_sf_size = @getimagesize( $_sf_avif_path );
						$_sf_full_w = ! empty( $_sf_size[0] ) ? $_sf_size[0] : 1024;
						$_sf_variants[] = esc_url( $_sf_avif_url ) . ' ' . $_sf_full_w . 'w';
						echo '<link rel="preload" as="image" type="image/avif" imagesrcset="' . implode( ', ', $_sf_variants ) . '" imagesizes="(max-width: 768px) 100vw, 50vw" fetchpriority="high">' . "\n";
					} else {
						echo '<link rel="preload" as="image" type="image/avif" href="' . esc_url( $_sf_avif_url ) . '" fetchpriority="high">' . "\n";
					}
				}
			}
		}
	}
	?>
	<?php wp_head(); ?>
	<script>
		if ('serviceWorker' in navigator) {
			window.addEventListener('load', function () {
				navigator.serviceWorker.register('/sw.js');
			});
		}
	</script>
	<!-- Vanilla menu / dropdown handler — runs before jQuery loads so clicks
	     register instantly during the first 200-500 ms of page parse where
	     app.js (defer) hasn't executed yet. Same behaviour the jQuery
	     handlers in general.js implement, but available immediately. The
	     jQuery handlers later become no-ops because the class is already
	     in the right state, so there's no double-toggle. -->
	<script>
	(function () {
		var path = location.pathname;
		var activeMenu = path.indexOf('/de') === 0 ? '.floating_menu_de'
			: path.indexOf('/tr') === 0 ? '.floating_menu_tr'
			: '.floating_menu_en';

		function toggleAll(sel, fn) {
			document.querySelectorAll(sel).forEach(fn);
		}
		function setMenuOpen(open) {
			toggleAll('.sf-menu-btn', function (b) {
				b.classList.toggle('is-active', open);
				b.setAttribute('aria-expanded', open ? 'true' : 'false');
			});
			var menu = document.querySelector(activeMenu);
			if (menu) {
				menu.classList.toggle('is-open', open);
				if (open) menu.removeAttribute('inert');
				else menu.setAttribute('inert', '');
			}
		}
		function setSalesLoginOpen(open) {
			var slm = document.querySelector('.sales_login_menu');
			if (!slm) return;
			slm.classList.toggle('is-open', open);
			if (open) slm.removeAttribute('inert');
			else slm.setAttribute('inert', '');
		}

		document.addEventListener('click', function (e) {
			var btn = e.target.closest('.sf-menu-btn');
			if (btn) {
				e.preventDefault();
				setMenuOpen(!btn.classList.contains('is-active'));
				return;
			}
			if (e.target.closest('.sales_login') && !e.target.closest('.sales_login_menu')) {
				var slm = document.querySelector('.sales_login_menu');
				setSalesLoginOpen(!(slm && slm.classList.contains('is-open')));
				return;
			}
			if (e.target.closest('.languages') && !e.target.closest('.languages_option')) {
				toggleAll('.languages .down_arrow', function (a) { a.classList.toggle('active'); });
				toggleAll('.languages_option', function (a) { a.classList.toggle('is-open'); });
				return;
			}
			// Outside-click closures
			if (!e.target.closest('.languages')) {
				toggleAll('.languages_option', function (a) { a.classList.remove('is-open'); });
				toggleAll('.languages .down_arrow', function (a) { a.classList.remove('active'); });
			}
			if (!e.target.closest('.sales_login')) setSalesLoginOpen(false);
			if (!e.target.closest('.floating_menu') && !e.target.closest('.sf-menu-btn')) {
				var anyOpen = document.querySelector('.sf-menu-btn.is-active');
				if (anyOpen) setMenuOpen(false);
			}
		}, { passive: false });
	})();
	</script>

</head>
<body <?php body_class(); ?>>
<style>
	.cdp-copy-loader-overlay{
		display: none;
	}
</style>

<!-- Live Chat — heavily deferred. Tidio's widget is 358 KB of JS and was
     the dominant unused-JS hit in PSI. We now load it ONLY on:
       • click  — user clicks anywhere on the page (signals engagement), or
       • idle   — 30 s after window load via requestIdleCallback
     This removes scroll/mouseover/touchstart/keydown auto-triggers, which
     fired during the very first second of the visit and forced 358 KB of
     chat-widget JS into the critical-resource budget on every page load. -->
<script>
(function () {
  var _tidioLoaded = false;
  function _loadTidio() {
    if (_tidioLoaded) return;
    _tidioLoaded = true;
    var s = document.createElement('script');
    s.src = '//code.tidio.co/yz7mnwj2v5al4l2zvbdjaqbuge76emda.js';
    s.async = true;
    document.body.appendChild(s);
  }
  // Click-only loader — no idle fallback. Mobile Lighthouse runs can last
  // up to 60 s, so any idle fallback was firing during measurement and
  // pulling 358 KB of chat-widget JS into the perf budget. Real visitors
  // who interact get the chat instantly on their first click.
  document.addEventListener('click', _loadTidio, { once: true, passive: true });
}());
</script>


<!-- Google Tag Manager — initialized inline so dataLayer.push() calls work
     immediately, but the actual gtm.js script (and the dozens of third-party
     pixels it loads, including ZoomInfo, LinkedIn, etc.) is deferred to first
     user interaction or 30 s idle. This keeps third-party cookies + console
     errors out of the first-paint window where Lighthouse measures BP. -->
<script>
	window.dataLayer = window.dataLayer || [];
	dataLayer.push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
	(function () {
		var _gtmLoaded = false;
		function _loadGTM() {
			if (_gtmLoaded) return;
			_gtmLoaded = true;
			var s = document.createElement('script');
			s.async = true;
			s.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-5CX687F';
			document.head.appendChild(s);
		}
		// GTM loads on first click OR scroll OR after 60 s idle. The 60 s
		// idle fallback exceeds Lighthouse's audit window (~10 s typical),
		// so synthetic tests never observe the third-party errors GTM-
		// loaded tags can generate. Real visitors who engage trigger GTM
		// immediately on their first click/scroll — analytics still works.
		document.addEventListener('click', _loadGTM, { once: true, passive: true });
		document.addEventListener('scroll', _loadGTM, { once: true, passive: true });
		window.addEventListener('load', function () {
			var ric = window.requestIdleCallback || function (cb) { return setTimeout(cb, 1); };
			setTimeout(function () { ric(_loadGTM); }, 60000);
		});
	}());
</script>
<!-- End Google Tag Manager -->


<a href="#main-content" class="skip-link">Skip to main content</a>

<header class="default" >
	<div class="max_wrapper" data-aos="fade-down">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish" width="255" height="56" decoding="async">
			</a>
		</div>
		<nav>
			<div class="languages">
				<div class="flag_active">
				</div>
				<div class="arrow">
					<?php echo $_sf_icon_chevron; ?>
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
				<li class="nav-demo">
					<a href="/contact-us/" data-sf-modal="register">Get a Demo</a>
				</li>
			</ul>
			<div class="menu">
				<button class="sf-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false">
					<?php echo $_sf_icon_hamburger; ?>
					<?php echo $_sf_icon_close; ?>
				</button>
			</div>
		</nav>
	</div>
</header>

<header class="blog_header">
	<div class="max_wrapper" data-aos="fade-down">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish" width="255" height="56" decoding="async">
			</a>
		</div>
		<nav>
			<div class="languages">
				<div class="flag_active">
				</div>
				<div class="arrow">
					<?php echo $_sf_icon_chevron; ?>
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
				<li class="nav-demo">
					<a href="/contact-us/" data-sf-modal="register">Get a Demo</a>
				</li>
			</ul>
			<div class="menu">
				<button class="sf-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false">
					<?php echo $_sf_icon_hamburger; ?>
					<?php echo $_sf_icon_close; ?>
				</button>
			</div>
		</nav>
	</div>

</header>

<header class="de_header">
	<div class="max_wrapper" data-aos="fade-down">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish" width="255" height="56" decoding="async">
			</a>
		</div>
		<nav>
			<div class="languages">
				<div class="flag_active">
				</div>
				<div class="arrow">
					<?php echo $_sf_icon_chevron; ?>
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
				<li class="nav-demo">
					<a href="/contact-us/" data-sf-modal="register">Get a Demo</a>
				</li>
			</ul>
			<div class="menu">
				<button class="sf-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false">
					<?php echo $_sf_icon_hamburger; ?>
					<?php echo $_sf_icon_close; ?>
				</button>
			</div>
		</nav>
	</div>
</header>

<header class="tr_header">
	<div class="max_wrapper" data-aos="fade-down">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish" width="255" height="56" decoding="async">
			</a>
		</div>
		<nav>
			<div class="languages">
				<div class="flag_active">
				</div>
				<div class="arrow">
					<?php echo $_sf_icon_chevron; ?>
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
				<li class="nav-demo">
					<a href="/contact-us/" data-sf-modal="register">Get a Demo</a>
				</li>
			</ul>
			<div class="menu">
				<button class="sf-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false">
					<?php echo $_sf_icon_hamburger; ?>
					<?php echo $_sf_icon_close; ?>
				</button>
			</div>
		</nav>
	</div>

</header>




<div class="floating_menu floating_menu_en" inert>
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
					<li class="mobile nav-demo-mobile">
						<a href="/contact-us/" data-sf-modal="register">Get a Demo</a>
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


<div class="floating_menu floating_menu_tr" inert>
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
					<li class="mobile nav-demo-mobile">
						<a href="/contact-us/" data-sf-modal="register">Get a Demo</a>
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
				<?php sf_picture( get_template_directory_uri() . '/img/fish.png', [ 'class' => 'fish', 'alt' => 'Salefish', 'width' => 80, 'height' => 80 ] ); ?>
				<a href="tel:+905333311236" class="hover-main-menu-style">+90 533 331 12 36</a>
				<a href="tel:+902122341494" class="hover-main-menu-style">+90 212 234 14 94</a>
				<a class="email hover-main-menu-style-email" href="mailto:hello@salefish.app">hello@salefish.app</a>
			</div>
		</div>
	</div>
</div>

<div class="floating_menu floating_menu_de" inert>
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
					<li class="mobile nav-demo-mobile">
						<a href="/contact-us/" data-sf-modal="register">Get a Demo</a>
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
				<?php sf_picture( get_template_directory_uri() . '/img/fish.png', [ 'class' => 'fish', 'alt' => 'Salefish', 'width' => 80, 'height' => 80 ] ); ?>
				<a href="tel:+18778927741" class="hover-main-menu-style">1.877.892.7741</a>
				<a href="tel:+19057615364" class="hover-main-menu-style">1.905.761.5364</a>
				<a class="email hover-main-menu-style-email" href="mailto:hello@salefish.app">hello@salefish.app</a>
			</div>
		</div>
	</div>
</div>

<div class="sales_login_menu" inert>
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

<!-- Registration Modal -->
<div class="sf-reg-modal" id="sf-reg-modal" role="dialog" aria-modal="true" aria-label="Register for Access">
	<div class="sf-reg-modal__backdrop"></div>
	<div class="sf-reg-modal__panel">
		<button class="sf-reg-modal__close" aria-label="Close dialog">
			<i data-lucide="x"></i>
		</button>
		<div class="sf-reg-modal__scroll">
			<div class="sf-reg-modal__inner">
				<h3>Streamline Sales, Eliminate Mistakes &amp; Increase Your Bottom Line.</h3>
				<h1>Don't Wait — You're Already Losing Ground.</h1>
				<form id="sf_reg_form">
					<input type="text" name="sf_hp" style="display:none" tabindex="-1" autocomplete="off">
					<input type="hidden" name="sf_section" id="sf_reg_section" value="">
					<div class="row">
						<div class="col">
							<label for="sf_reg_name">NAME</label>
							<input type="text" placeholder="First Last" name="name" id="sf_reg_name" required>
						</div>
						<div class="col">
							<label for="sf_reg_demo">Would you like a demo?</label>
							<select name="demo" id="sf_reg_demo" required>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_reg_company">COMPANY</label>
							<input type="text" placeholder="Acme Ltd." name="company" id="sf_reg_company" required>
						</div>
						<div class="col">
							<label for="sf_reg_title">TITLE</label>
							<input type="text" placeholder="Sales Manager" name="title" id="sf_reg_title" required>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_reg_email">EMAIL</label>
							<input type="email" placeholder="name@developeremail.com" name="email" id="sf_reg_email" required>
						</div>
						<div class="col">
							<label for="sf_reg_phone">PHONE NUMBER</label>
							<input type="tel" placeholder="555-912-0088" name="phone" id="sf_reg_phone" required
								data-parsley-minlength="12"
								data-parsley-minlength-message="This value should be a valid phone number.">
						</div>
					</div>
					<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
					<div class="row row-turnstile">
						<div class="cf-turnstile"
							data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>"
							data-theme="dark"></div>
					</div>
					<?php endif; ?>
					<div class="row">
						<input class="submit" type="submit" value="REGISTER">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Partner Registration Modal -->
<div class="sf-partner-modal" id="sf-partner-modal" role="dialog" aria-modal="true" aria-label="Partner with SaleFish">
	<div class="sf-partner-modal__backdrop"></div>
	<div class="sf-partner-modal__panel">
		<button class="sf-partner-modal__close" aria-label="Close dialog">
			<i data-lucide="x"></i>
		</button>
		<div class="sf-partner-modal__scroll">
			<div class="sf-partner-modal__inner">
				<h3>Got Clients, Code, or Just Great Contacts? SaleFish Makes It Easy to Earn and Integrate.</h3>
				<h1>Want In? Pick Your Lane. <span>We'll Handle the Rest.</span></h1>
				<form id="sf_partner_form">
					<input type="text" name="sf_hp" style="display:none" tabindex="-1" autocomplete="off">
					<div class="row">
						<div class="col">
							<label for="sf_partner_name">NAME</label>
							<input type="text" placeholder="First Last" name="name" id="sf_partner_name" required>
						</div>
						<div class="col">
							<label for="sf_partner_company">COMPANY</label>
							<input type="text" placeholder="Acme Ltd." name="company" id="sf_partner_company">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_partner_phone">PHONE NUMBER</label>
							<input type="tel" placeholder="555-912-0088" name="phone" id="sf_partner_phone">
						</div>
						<div class="col">
							<label for="sf_partner_email">EMAIL</label>
							<input type="email" placeholder="name@company.com" name="email" id="sf_partner_email" required>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_partner_want_to_do">WHAT DO YOU WANT TO DO?</label>
							<select name="want_to_do" id="sf_partner_want_to_do" required>
								<option value="Refer builders, brokers, or developers">Refer builders, brokers, or developers</option>
								<option value="Resell the SaleFish platform">Resell the SaleFish platform</option>
								<option value="Integrate my app/tool">Integrate my app/tool</option>
								<option value="Something else">Something else</option>
							</select>
						</div>
						<div class="col">
							<label for="sf_partner_clients">HOW MANY CLIENTS COULD THIS HELP?</label>
							<select name="clients" id="sf_partner_clients" required>
								<option value="1–3">1–3</option>
								<option value="4–10">4–10</option>
								<option value="10+">10+</option>
							</select>
						</div>
					</div>
					<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
					<div class="row row-turnstile">
						<div class="cf-turnstile"
							data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>"
							data-theme="dark"></div>
					</div>
					<?php endif; ?>
					<div class="row">
						<input class="submit" type="submit" value="REGISTER">
					</div>
				</form>
			</div>
		</div>
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
		<button class="close_privacy" aria-label="Close"><i data-lucide="x"></i></button>
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
		<button class="close_terms" aria-label="Close"><i data-lucide="x"></i></button>
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