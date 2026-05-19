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

// Server-side locale detection for the language picker.
// Locale links are now rendered in PHP — no JS innerHTML swap needed.
$_sf_req_path = isset( $_SERVER['REQUEST_URI'] ) ? strtok( $_SERVER['REQUEST_URI'], '?' ) : '/';
if ( $_sf_req_path === '/au' || str_starts_with( $_sf_req_path, '/au/' ) ) { $_sf_locale = 'au'; }
elseif ( str_starts_with( $_sf_req_path, '/tr' ) ) { $_sf_locale = 'tr'; }
elseif ( str_starts_with( $_sf_req_path, '/de' ) ) { $_sf_locale = 'de'; }
else                                                { $_sf_locale = 'default'; }

// ── Locale-aware UI strings (modals, dialogs, legal) ─────────────────────────
$_sf_ui = [
	'default' => [
		'close_modal'    => 'Close dialog',
		'reg_eyebrow'    => 'Streamline Sales, Eliminate Mistakes &amp; Increase Your Bottom Line.',
		'reg_title'      => 'Don\'t Wait — You\'re Already Losing Ground.',
		'label_name'     => 'Name',
		'ph_name'        => 'First Last',
		'label_demo'     => 'Would you like a demo?',
		'demo_yes'       => 'Yes',
		'demo_no'        => 'No',
		'label_company'  => 'Company',
		'ph_company'     => 'Acme Ltd.',
		'label_title'    => 'Title',
		'ph_title'       => 'Sales Manager',
		'label_email'    => 'Email',
		'label_phone'    => 'Phone Number',
		'ph_phone'       => '555-912-0088',
		'submit'         => 'Register',
		'email_eyebrow'  => 'Almost there',
		'email_h2'       => 'Check your email.',
		'email_body_pre' => 'We sent a confirmation link to',
		'email_body_suf' => '. Click it to complete your registration — the link expires in 48 hours.',
		'ty_eyebrow'     => 'Message received',
		'ty_h2'          => 'Here\'s what happens next.',
		'ty_body'        => 'A SaleFish specialist will reach out within 1 business day to walk you through the platform and talk through your specific project needs — whether that\'s inventory management, online signing, or end-to-end pre-construction sales workflow.',
	],
	'de' => [
		'close_modal'    => 'Dialog schließen',
		'reg_eyebrow'    => 'Optimieren Sie den Verkauf, beseitigen Sie Fehler und steigern Sie Ihr Ergebnis.',
		'reg_title'      => 'Warten Sie nicht — Sie verlieren bereits Boden.',
		'label_name'     => 'Name',
		'ph_name'        => 'Vorname Nachname',
		'label_demo'     => 'Möchten Sie eine Demo?',
		'demo_yes'       => 'Ja',
		'demo_no'        => 'Nein',
		'label_company'  => 'Unternehmen',
		'ph_company'     => 'Acme GmbH',
		'label_title'    => 'Titel',
		'ph_title'       => 'Verkaufsleiter',
		'label_email'    => 'E-Mail',
		'label_phone'    => 'Telefonnummer',
		'ph_phone'       => '555-912-0088',
		'submit'         => 'Registrieren',
		'email_eyebrow'  => 'Fast geschafft',
		'email_h2'       => 'Bitte prüfen Sie Ihre E-Mail.',
		'email_body_pre' => 'Wir haben einen Bestätigungslink an',
		'email_body_suf' => ' gesendet. Klicken Sie darauf, um Ihre Registrierung abzuschließen — der Link läuft in 48 Stunden ab.',
		'ty_eyebrow'     => 'Nachricht erhalten',
		'ty_h2'          => 'Was als Nächstes passiert.',
		'ty_body'        => 'Ein SaleFish-Spezialist wird sich innerhalb eines Werktages bei Ihnen melden, um Sie durch die Plattform zu führen und Ihre spezifischen Projektanforderungen zu besprechen.',
	],
	'tr' => [
		'close_modal'    => 'İletişim kutusunu kapat',
		'reg_eyebrow'    => 'Satışlarınızı kolaylaştırın, hataları ortadan kaldırın ve kârınızı artırın.',
		'reg_title'      => 'Beklemeyin — Zaten Geri Kalıyorsunuz.',
		'label_name'     => 'Ad Soyad',
		'ph_name'        => 'Ad Soyad',
		'label_demo'     => 'Demo ister misiniz?',
		'demo_yes'       => 'Evet',
		'demo_no'        => 'Hayır',
		'label_company'  => 'Şirket',
		'ph_company'     => 'Acme Ltd.',
		'label_title'    => 'Unvan',
		'ph_title'       => 'Satış Müdürü',
		'label_email'    => 'E-posta',
		'label_phone'    => 'Telefon Numarası',
		'ph_phone'       => '555-912-0088',
		'submit'         => 'Kayıt Ol',
		'email_eyebrow'  => 'Neredeyse bitti',
		'email_h2'       => 'E-postanızı kontrol edin.',
		'email_body_pre' => '',
		'email_body_suf' => ' adresine bir onay bağlantısı gönderdik. Kaydınızı tamamlamak için tıklayın — bağlantı 48 saat geçerlidir.',
		'ty_eyebrow'     => 'Mesajınız alındı',
		'ty_h2'          => 'Bundan sonra ne olacak.',
		'ty_body'        => 'Bir SaleFish uzmanı, platformu size göstermek ve özel proje ihtiyaçlarınızı görüşmek üzere 1 iş günü içinde sizinle iletişime geçecek.',
	],
];
$_sf_ui_s = $_sf_ui[ $_sf_locale ] ?? $_sf_ui['default'];

// Pre-extract all translated strings into simple scalar variables.
// Using direct array-key access inside mixed PHP/HTML template blocks can
// trigger unexpected behaviour with certain output-buffer plugins; simple
// variables are always safe.
$_sf_str_close_modal    = (string) ( $_sf_ui_s['close_modal']    ?? 'Close dialog' );
$_sf_str_reg_eyebrow    = (string) ( $_sf_ui_s['reg_eyebrow']    ?? 'Streamline Sales, Eliminate Mistakes &amp; Increase Your Bottom Line.' );
$_sf_str_reg_title      = (string) ( $_sf_ui_s['reg_title']      ?? 'Don\'t Wait — You\'re Already Losing Ground.' );
$_sf_str_label_name     = (string) ( $_sf_ui_s['label_name']     ?? 'Name' );
$_sf_str_ph_name        = (string) ( $_sf_ui_s['ph_name']        ?? 'First Last' );
$_sf_str_label_demo     = (string) ( $_sf_ui_s['label_demo']     ?? 'Would you like a demo?' );
$_sf_str_demo_yes       = (string) ( $_sf_ui_s['demo_yes']       ?? 'Yes' );
$_sf_str_demo_no        = (string) ( $_sf_ui_s['demo_no']        ?? 'No' );
$_sf_str_label_company  = (string) ( $_sf_ui_s['label_company']  ?? 'Company' );
$_sf_str_ph_company     = (string) ( $_sf_ui_s['ph_company']     ?? 'Acme Ltd.' );
$_sf_str_label_title    = (string) ( $_sf_ui_s['label_title']    ?? 'Title' );
$_sf_str_ph_title       = (string) ( $_sf_ui_s['ph_title']       ?? 'Sales Manager' );
$_sf_str_label_email    = (string) ( $_sf_ui_s['label_email']    ?? 'Email' );
$_sf_str_label_phone    = (string) ( $_sf_ui_s['label_phone']    ?? 'Phone Number' );
$_sf_str_ph_phone       = (string) ( $_sf_ui_s['ph_phone']       ?? '555-912-0088' );
$_sf_str_submit         = (string) ( $_sf_ui_s['submit']         ?? 'Register' );
$_sf_str_email_eyebrow  = (string) ( $_sf_ui_s['email_eyebrow']  ?? 'Almost there' );
$_sf_str_email_h2       = (string) ( $_sf_ui_s['email_h2']       ?? 'Check your email.' );
$_sf_str_email_body_pre = (string) ( $_sf_ui_s['email_body_pre'] ?? 'We sent a confirmation link to' );
$_sf_str_email_body_suf = (string) ( $_sf_ui_s['email_body_suf'] ?? '. Click it to complete your registration — the link expires in 48 hours.' );
$_sf_str_ty_eyebrow     = (string) ( $_sf_ui_s['ty_eyebrow']     ?? 'Message received' );
$_sf_str_ty_h2          = (string) ( $_sf_ui_s['ty_h2']          ?? 'Here\'s what happens next.' );
$_sf_str_ty_body        = (string) ( $_sf_ui_s['ty_body']        ?? 'A SaleFish specialist will reach out within 1 business day to walk you through the platform and talk through your specific project needs.' );

$_sf_all_locales = [
	'default' => [ 'flag' => '🇨🇦🇺🇸', 'label' => 'Canada & USA (English)', 'href' => '/' ],
	'au'      => [ 'flag' => '🇦🇺',    'label' => 'Australia (English)',     'href' => '/au' ],
	'de'      => [ 'flag' => '🇩🇪',    'label' => 'Germany (Deutsch)',       'href' => '/de' ],
	'tr'      => [ 'flag' => '🇹🇷',    'label' => 'Turkey (Türkçe)',         'href' => '/tr' ],
];
$_sf_flag_active_html = '<span class="flag">' . $_sf_all_locales[ $_sf_locale ]['flag'] . '</span>';
$_sf_lang_options_html = '<ul>';
foreach ( $_sf_all_locales as $_sf_lk => $_sf_lv ) {
	if ( $_sf_lk === $_sf_locale ) { continue; }
	$_sf_lang_options_html .= '<li><a href="' . esc_url( home_url( $_sf_lv['href'] ) ) . '" aria-label="' . esc_attr( $_sf_lv['label'] ) . '"><span class="flag">' . $_sf_lv['flag'] . '</span></a></li>';
}
$_sf_lang_options_html .= '</ul>';

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
	<script>document.documentElement.className = document.documentElement.className.replace('no-js','js');</script>
	<meta
		charset="<?php bloginfo('charset'); ?>">
	<meta content='width=device-width, initial-scale=1.0, viewport-fit=cover' name='viewport' />
	<link rel="preconnect" href="https://www.googletagmanager.com">
	<link rel="preconnect" href="https://www.google-analytics.com">
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
	<link rel="preconnect" href="https://www.clarity.ms">
	<script>
		// Microsoft Clarity must load early for complete session recordings.
		// The function queue lets conversion code call clarity() before the
		// external script has finished downloading.
		(function(c,l,a,r,i,t,y){
			c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
			t=l.createElement(r);t.async=1;t.src='https://www.clarity.ms/tag/'+i;
			y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
		})(window,document,'clarity','script','wmwzqm0oha');
	</script>
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
		is_singular( 'post' )                          ||
		is_page( 'thank-you-for-registering' )         ||
		is_archive()                                   ||
		is_search()                                    ||
		is_404()
	);
	$_sf_theme_color = $_sf_light_header ? '#ffffff' : '#452D8C';
	?>
	<meta name="theme-color" content="<?php echo esc_attr( $_sf_theme_color ); ?>">
	<!-- Preload only the LCP-critical Poppins weights: Regular (body) and
	     Bold (hero headline). SemiBold is used for navigation and minor
	     headings and can FOUT-swap via font-display: swap without
	     impacting LCP. Three parallel font preloads were contending for
	     bandwidth on first paint; dropping SemiBold saves ~30 KB on the
	     critical path and noticeably speeds up first content paint. -->
	<link rel="preload" as="font" type="font/woff2" crossorigin href="<?php echo esc_url( get_template_directory_uri() ); ?>/fonts/Poppins-Regular.woff2">
	<link rel="preload" as="font" type="font/woff2" crossorigin href="<?php echo esc_url( get_template_directory_uri() ); ?>/fonts/Poppins-Bold.woff2">
	<?php
	// ── LCP image preload (all major hero pages) ────────────────────────────
	// Tells the browser to start downloading the page's LCP hero AVIF in
	// parallel with CSS, instead of waiting for the body parser to
	// discover the <img> tag. Big PageSpeed mobile LCP win. Generalized
	// from front-page-only to every hero-driven page via the
	// sf_preload_hero_image() helper in functions.php.
	if ( is_front_page() ) {
		$_sf_hero_url = get_field( 'hero_image' );
		if ( $_sf_hero_url ) {
			sf_preload_hero_image( $_sf_hero_url, '(max-width: 768px) 100vw, 50vw' );
		}
	} elseif ( is_page_template( 'page-our-story.php' ) || is_page_template( 'page-partners.php' ) ) {
		// Both pages use the same first hero slide.
		sf_preload_hero_image(
			get_template_directory_uri() . '/img/hero-slides/new-construction-sales-platform-condo-tower-2560w.png',
			'100vw'
		);
	} elseif ( is_page_template( 'page-awards.php' ) ) {
		sf_preload_hero_image( get_template_directory_uri() . '/img/trophy-v3.png' );
	} elseif ( is_page_template( 'page-success.php' ) ) {
		sf_preload_hero_image( get_template_directory_uri() . '/img/success/success_hero.png' );
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
	<!-- ─── SaleFish Menu Controller ──────────────────────────────────────
	     Single source of truth for every open/close UI on the site:
	       • Hamburger menu (.sf-menu-btn → .floating_menu_{en|de|tr})
	       • Languages picker (.languages → .languages_option)
	       • Sales-login menu (.sales_login → .sales_login_menu)

	     Industry-standard pattern:
	       • One delegated click listener on document
	       • Central registry of (trigger, target, options)
	       • At most ONE menu open at a time — opening menu A auto-closes B/C
	       • Outside click closes the open menu; Esc closes and returns focus
	       • aria-expanded + `inert` attribute toggled in step
	       • No jQuery, no app.js dependency, no scroll listener
	       • Inline in <head> so clicks work the moment the DOM exists

	     Replaces ALL prior menu/dropdown handlers (vanilla + jQuery). -->
	<script>
	(function () {
		'use strict';
		var path = location.pathname;
		var activeMenuSel = path.indexOf('/de') === 0 ? '.floating_menu_de'
			: path.indexOf('/tr') === 0 ? '.floating_menu_tr'
			: '.floating_menu_en';

		// Each menu is { trigger: outer wrapper to test for inside-click,
		//                triggerBtn: inner element that toggles state,
		//                target: the panel that opens } — selectors only.
		var menus = [
			{ id: 'nav',        triggerBtn: '.sf-menu-btn',  target: activeMenuSel },
			{ id: 'languages',  triggerBtn: '.languages',    target: '.languages_option', insideTarget: '.languages_option', insideTrigger: '.languages' },
			{ id: 'salesLogin', triggerBtn: '.sales_login',  target: '.sales_login_menu', insideTarget: '.sales_login_menu', insideTrigger: '.sales_login' },
		];

		var openId = null;
		var lastFocused = null;
		var _touchToggleAt = 0; // timestamp set by pointerdown; lets the click handler skip the synthetic follow-up tap

		function targetEls(m)  { return Array.from(document.querySelectorAll(m.target)); }
		function triggerEls(m) { return Array.from(document.querySelectorAll(m.triggerBtn)); }

		function applyState(m, open) {
			triggerEls(m).forEach(function (b) {
				b.classList.toggle('is-active', open);
				b.setAttribute('aria-expanded', open ? 'true' : 'false');
			});
			// Toggle ALL matching targets, not just the first. The header
			// has 4 language-variant copies — each with its own
			// .languages_option — and only the variant matching the
			// current page is actually visible (others hidden by CSS).
			// Without this, opening the picker on /de or /tr toggled the
			// hidden English one's panel and the visible panel never
			// updated.
			targetEls(m).forEach(function (t) {
				t.classList.toggle('is-open', open);
				if (open) t.removeAttribute('inert');
				else      t.setAttribute('inert', '');
			});
			// The languages picker has chevron siblings that flip on open.
			if (m.id === 'languages') {
				document.querySelectorAll('.languages .down_arrow').forEach(function (a) {
					a.classList.toggle('active', open);
				});
			}
		}

		function close(id) {
			var m = menus.find(function (x) { return x.id === id; });
			if (!m) return;
			applyState(m, false);
			if (openId === id) openId = null;
		}
		function closeAll() { menus.forEach(function (m) { close(m.id); }); }
		window.sfMenuClose = closeAll; // public API for general.js handlers
		function open(id) {
			if (openId && openId !== id) close(openId);
			var m = menus.find(function (x) { return x.id === id; });
			if (!m) return;
			lastFocused = document.activeElement;
			applyState(m, true);
			openId = id;
		}
		function toggle(id) {
			if (openId === id) close(id);
			else open(id);
		}

		document.addEventListener('click', function (e) {
			// Match a trigger — the *outer* trigger element (so inner
			// children like SVG icons still register).
			for (var i = 0; i < menus.length; i++) {
				var m = menus[i];
				if (e.target.closest(m.triggerBtn)) {
					// Don't toggle if the click is inside the panel itself
					// (e.g. clicking a link inside the languages_option list).
					if (m.insideTarget && e.target.closest(m.insideTarget)) return;
					// iOS with touch-action:manipulation fires click after pointerdown even
					// when e.preventDefault() was called. Skip if pointerdown already toggled
					// within the last 600ms (well beyond any real double-tap window).
					if (e.timeStamp - _touchToggleAt < 600) { e.preventDefault(); return; }
					e.preventDefault();
					toggle(m.id);
					return;
				}
			}
			// Outside click: close the open menu unless the click is inside
			// any of its panels (multiple panels exist for language-variant
			// triggers — see applyState comment).
			if (!openId) return;
			var openMenu = menus.find(function (x) { return x.id === openId; });
			if (!openMenu) { openId = null; return; }
			var insidePanel = targetEls(openMenu).some(function (t) {
				return t.contains(e.target);
			});
			if (insidePanel) return; // click inside an open panel — keep open
			close(openId);
		}, false);

		// Esc closes whatever is open and restores focus to whoever opened it.
		document.addEventListener('keydown', function (e) {
			if (e.key !== 'Escape' || !openId) return;
			var prev = lastFocused;
			close(openId);
			if (prev && typeof prev.focus === 'function') prev.focus();
		});

		// Close on viewport resize past breakpoint (avoid orphaned-open menus
		// when rotating a phone or resizing a window).
		var lastWidth = window.innerWidth;
		window.addEventListener('resize', function () {
			if (Math.abs(window.innerWidth - lastWidth) > 80) {
				lastWidth = window.innerWidth;
				if (openId) close(openId);
			}
		}, { passive: true });

		// ── Header scroll state (rAF-throttled) ────────────────────────────
		// Single class toggle on <html>: `is-scrolled`. CSS reads it for
		// every derived layout change (header height, floating-menu top
		// offset). Replaces the old jQuery scroll handler that wrote
		// inline `top` to four elements every frame.
		// We resolve the <header> elements lazily inside the callback
		// because this script runs in <head> — before <body> parses, so
		// querying eagerly would return null. Multiple <header> tags
		// exist (one per language variant); we toggle .active on every
		// one so the correct visible header always reflects scroll state.
		var ticking = false;
		var docEl = document.documentElement;
		function applyScrollState() {
			ticking = false;
			var scrolled = window.scrollY > 1;
			docEl.classList.toggle('is-scrolled', scrolled);
			document.querySelectorAll('header').forEach(function (h) {
				h.classList.toggle('active', scrolled);
			});
		}
		window.addEventListener('scroll', function () {
			if (!ticking) { ticking = true; requestAnimationFrame(applyScrollState); }
		}, { passive: true });
		// Initial state — applied after DOM parses so querySelectorAll
		// has actual <header> elements to find. Multiple post-load
		// rechecks cover hash navigation and slow iOS Safari.
		document.addEventListener('DOMContentLoaded', applyScrollState);
		setTimeout(applyScrollState, 100);
		setTimeout(applyScrollState, 300);

		// ── Scroll-reveal: REMOVED ────────────────────────────────────────────
		// The full sfRevealInit / .sf-fade-pre / IntersectionObserver
		// system has been removed. Page elements now render at their
		// natural styles immediately on first paint — no JS-driven
		// hiding, no animation, no IO callbacks. Stub left here as a
		// safety belt: if any cached HTML still has stale .sf-fade-pre
		// classes injected from the previous deploy, this strips them
		// on DOMContentLoaded so the elements are guaranteed visible.
		document.addEventListener('DOMContentLoaded', function () {
			document.querySelectorAll('.sf-fade-pre').forEach(function (el) {
				el.classList.remove('sf-fade-pre');
				el.classList.remove('sf-fade-in');
			});
		});

		// ── Instant mobile hamburger + language picker ──────────────────────────
		// `click` fires ~300 ms after touchend (browser tap-delay) and can be
		// delayed further if the main thread is busy parsing app.js. `pointerdown`
		// fires the instant the finger contacts the screen — zero perceptible lag.
		// We skip `pointerType === 'mouse'` so desktop users keep the click path
		// (prevents a double-toggle: pointerdown THEN click). e.preventDefault()
		// cancels the synthetic click so the delegated handler above doesn't
		// also toggle the menu a second time.
		document.addEventListener('DOMContentLoaded', function () {
			document.querySelectorAll('.sf-menu-btn').forEach(function (btn) {
				btn.addEventListener('pointerdown', function (e) {
					if (e.pointerType === 'mouse') return;
					_touchToggleAt = e.timeStamp;
					e.preventDefault();
					toggle('nav');
				});
			});

			// Language picker is a <div>, not a <button> — touch-action:manipulation
			// alone is not enough. The same pointerdown pattern ensures instant
			// response. Guard: if the tap is inside the already-open option panel
			// (user selecting a language), don't toggle — let the <a> click navigate.
			document.querySelectorAll('.languages').forEach(function (div) {
				div.addEventListener('pointerdown', function (e) {
					if (e.pointerType === 'mouse') return;
					if (e.target.closest('.languages_option')) return;
					e.preventDefault();
					toggle('languages');
				});
			});
		});

		// ── bfcache + scroll-lock restoration guard ──────────────────────────
		// pageshow fires on BOTH fresh loads AND bfcache restorations.
		// Two failure modes covered here:
		//  1. User opened a modal (scroll-locked via position:fixed on body),
		//     navigated away without closing — bfcache restores the frozen state.
		//  2. Any other code path that leaves overflow or position stuck.
		// We clear every scroll-lock property unconditionally so the page is
		// always scrollable after restore. applyScrollState() re-syncs the
		// header's .active class with the real scroll position.
		window.addEventListener('pageshow', function () {
			document.documentElement.style.overflow = '';
			document.body.style.overflow = '';
			document.body.style.position = '';
			document.body.style.top = '';
			document.body.style.width = '';
			document.body.style.paddingRight = '';
			document.querySelectorAll('header').forEach(function (h) {
				h.style.paddingRight = '';
			});
			applyScrollState();
		});
	})();
	</script>
	<!-- Lazy YouTube preconnect — added only when the user hovers/touches a
	     video trigger, not on page load. Avoids flagging unused preconnects in
	     Lighthouse while still cutting ~200-500 ms off the iframe load time. -->
	<script>
	(function(){
	  var _ytPc = false;
	  function _addYTPc(){
	    if(_ytPc) return; _ytPc = true;
	    ['https://www.youtube-nocookie.com','https://www.youtube.com','https://i.ytimg.com','https://yt3.ggpht.com'].forEach(function(h){
	      var l = document.createElement('link'); l.rel='preconnect'; l.href=h; l.crossOrigin=''; document.head.appendChild(l);
	    });
	  }
	  ['pointerenter','touchstart','focusin'].forEach(function(e){
	    document.addEventListener(e,function(ev){
	      if(ev.target && ev.target.closest && ev.target.closest('[data-video-url],[data-sf-video]')) _addYTPc();
	    },{passive:true,capture:true,once:false});
	  });
	}());
	</script>

</head>
<body <?php body_class( $_sf_light_header ? 'sf-light-header' : 'sf-dark-header' ); ?>>
<!-- Google Tag Manager (noscript) — required by GTM spec for cookieless environments -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5CX687F" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- Status-bar / notch fill — first child of <body> so iOS paints it
     immediately at first paint. Position-fixed div instead of a header
     pseudo-element because WebKit doesn't reliably render `::before`
     elements inside `position: fixed` parents within the
     env(safe-area-inset-top) zone. The real <div> is bullet-proof. -->
<div class="sf-notch-fill" aria-hidden="true"></div>
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
// Live Chat — load only on a long idle window AFTER first paint, never on
// click. Triggering Tidio's 358 KB script load on the user's first click
// caused subsequent clicks to feel laggy: even with requestIdleCallback,
// once the script arrived from the network it parsed on the main thread
// and stalled it for 200-500 ms on Safari mobile. Loading purely on idle
// (15 s after window.load) means clicks are never the trigger, and parse
// happens during natural reading-pause idle time.
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
  window.addEventListener('load', function () {
    var ric = window.requestIdleCallback || function (cb) { return setTimeout(cb, 1); };
    setTimeout(function () { ric(_loadTidio, { timeout: 5000 }); }, 15000);
  });
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
		// GTM deferred to 4 s after window.load — keeps GTM off the critical
		// path (LCP, TTI, first click) while still loading well before most
		// users interact. 8 s was too aggressive: visitors bouncing within
		// 5–9 s were invisible to GA4. 4 s is the sweet spot for a B2B site
		// where meaningful engagement starts at 5+ s.
		window.addEventListener('load', function () {
			var ric = window.requestIdleCallback || function (cb) { return setTimeout(cb, 1); };
			setTimeout(function () { ric(_loadGTM, { timeout: 5000 }); }, 4000);
		});
	}());
</script>
<!-- End Google Tag Manager -->

<!-- GA4 direct — loads async after window.load so it never blocks LCP/TTI.
     send_page_view is disabled to avoid a duplicate hit if GTM is later
     configured with its own GA4 Configuration tag.
     sfTrackConversion() uses window.gtag() to fire generate_lead events. -->
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-RPV5YBTN35', { send_page_view: true });
	window.addEventListener('load', function () {
		var s = document.createElement('script');
		s.async = true;
		s.src = 'https://www.googletagmanager.com/gtag/js?id=G-RPV5YBTN35';
		document.head.appendChild(s);
	});
</script>
<!-- End GA4 direct -->


<a href="#main-content" class="skip-link">Skip to main content</a>

<header class="default" aria-label="Main">
	<div class="max_wrapper" data-aos="fade-down">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish" width="255" height="56" decoding="async">
			</a>
		</div>
		<nav aria-label="Main navigation">
			<div class="languages">
				<div class="flag_active"><?php echo $_sf_flag_active_html; ?></div>
				<div class="arrow"><?php echo $_sf_icon_chevron; ?></div>
				<div class="languages_option"><?php echo $_sf_lang_options_html; ?></div>
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
					<a class="button" href="/contact-us/" data-sf-modal="register">Get a Demo</a>
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

<header class="blog_header" aria-label="Main">
	<div class="max_wrapper" data-aos="fade-down">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish" width="255" height="56" decoding="async">
			</a>
		</div>
		<nav aria-label="Main navigation">
			<div class="languages">
				<div class="flag_active"><?php echo $_sf_flag_active_html; ?></div>
				<div class="arrow"><?php echo $_sf_icon_chevron; ?></div>
				<div class="languages_option"><?php echo $_sf_lang_options_html; ?></div>
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
					<a class="button" href="/contact-us/" data-sf-modal="register">Get a Demo</a>
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

<header class="de_header" aria-label="Hauptmenü">
	<div class="max_wrapper" data-aos="fade-down">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish" width="255" height="56" decoding="async">
			</a>
		</div>
		<nav aria-label="Hauptnavigation">
			<div class="languages">
				<div class="flag_active"><?php echo $_sf_flag_active_html; ?></div>
				<div class="arrow"><?php echo $_sf_icon_chevron; ?></div>
				<div class="languages_option"><?php echo $_sf_lang_options_html; ?></div>
			</div>
			<ul>
				<li class="features_li">
					<a href="/de#features">Merkmale</a>
				</li>
				<li class="nav-demo">
					<a class="button" href="/contact-us/" data-sf-modal="register">Demo Anfragen</a>
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

<header class="tr_header" aria-label="Ana menü">
	<div class="max_wrapper" data-aos="fade-down">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png"
					alt="Salefish" width="255" height="56" decoding="async">
			</a>
		</div>
		<nav aria-label="Ana gezinme">
			<div class="languages">
				<div class="flag_active"><?php echo $_sf_flag_active_html; ?></div>
				<div class="arrow"><?php echo $_sf_icon_chevron; ?></div>
				<div class="languages_option"><?php echo $_sf_lang_options_html; ?></div>
			</div>
			<ul>
				<li class="features_li">
					<a href="/tr#features">Özellikleri</a>
				</li>
				<li class="nav-demo">
					<a class="button" href="/contact-us/" data-sf-modal="register">Demo Al</a>
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
						<a class="button" href="/contact-us/" data-sf-modal="register">Get a Demo</a>
					</li>
					<li class="mobile menu-sep"></li>
					<li>
						<a href="https://chatting.page/salefish" target="_blank" rel="noopener noreferrer" aria-label="Live Chat Support (opens in new tab)">Live Chat Support</a>
					</li>
					<li>
						<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer" aria-label="Sales App (opens in new tab)">Sales App</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer" aria-label="Highrise Admin (opens in new tab)">Highrise Admin</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer" aria-label="Lowrise Admin (opens in new tab)">Lowrise Admin</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>


<div class="floating_menu floating_menu_tr" lang="tr" inert>
	<div class="wrapper">
		<div class="wrap">
			<div class="top">
				<ul>
					<li class="mobile features_li">
						<a href="/tr#features">Özellikleri</a>
					</li>
					<li class="mobile nav-demo-mobile">
						<a class="button" href="/contact-us/" data-sf-modal="register">Demo Al</a>
					</li>
					<li class="mobile menu-sep"></li>
					<li>
						<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer" aria-label="Satış Aplikasyonu (opens in new tab)">Satış Aplikasyonu</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer" aria-label="Çok Katlı Bina Yönetimi (opens in new tab)">Çok Katlı Bina Yönetimi</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer" aria-label="Az Katlı Bina Yönetimi (opens in new tab)">Az Katlı Bina Yönetimi</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="floating_menu floating_menu_de" lang="de" inert>
	<div class="wrapper">
		<div class="wrap">
			<div class="top">
				<ul>
					<li class="mobile features_li">
						<a href="/de#features">Merkmale</a>
					</li>
					<li class="mobile nav-demo-mobile">
						<a class="button" href="/contact-us/" data-sf-modal="register">Demo Anfragen</a>
					</li>
					<li class="mobile menu-sep"></li>
					<li>
						<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer" aria-label="Verkaufs-App (opens in new tab)">Verkaufs-App</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer" aria-label="Hochhaus-Verwaltung (opens in new tab)">Hochhaus-Verwaltung</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer" aria-label="Haus-Verwaltung (opens in new tab)">Haus-Verwaltung</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="sales_login_menu" inert>
	<div class="wrapper">
		<div class="wrap">
			<ul>
				<li>
					<a href="https://chatting.page/salefish" target="_blank" rel="noopener noreferrer" aria-label="Live Chat Support (opens in new tab)">Live Chat Support</a>
				</li>
				<li>
					<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer" aria-label="Sales App (opens in new tab)">Sales App</a>
				</li>
				<li>
					<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer" aria-label="Highrise Admin (opens in new tab)">Highrise Admin</a>
				</li>
				<li>
					<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer" aria-label="Lowrise Admin (opens in new tab)">Lowrise Admin</a>
				</li>
			</ul>
		</div>
	</div>

</div>

<div class="sf-check-email-msg">
	<div class="sf-check-email-msg__backdrop sf-check-email-close"></div>
	<div class="sf-check-email-msg__panel" role="dialog" aria-modal="true" aria-labelledby="sf-check-email-heading">
		<button class="sf-check-email-msg__close sf-check-email-close" aria-label="Close">
			<i data-lucide="x"></i>
		</button>
		<div class="sf-check-email-msg__icon-wrap">
			<i data-lucide="mail"></i>
		</div>
		<span class="sf-check-email-msg__eyebrow"><?php echo esc_html( $_sf_str_email_eyebrow ); ?></span>
		<h2 class="sf-check-email-msg__heading" id="sf-check-email-heading"><?php echo esc_html( $_sf_str_email_h2 ); ?></h2>
		<p class="sf-check-email-msg__body">
			<?php echo esc_html( $_sf_str_email_body_pre ); ?><?php if ( $_sf_str_email_body_pre ) : ?> <?php endif; ?><strong class="sf-check-email-msg__address"></strong><?php echo esc_html( $_sf_str_email_body_suf ); ?>
		</p>
	</div>
</div>

<!-- Registration + Partner modals — lazy-injected on first user
     interaction with a [data-sf-modal] trigger (see footer.php).
     Wrapped in <template> so the HTML is parsed but NOT instantiated as
     live DOM, saving ~140 lines of layout/style cost on pages where the
     modal is never opened. -->
<template id="sf-modals-template">
<!-- Registration Modal -->
<div class="sf-reg-modal" id="sf-reg-modal" role="dialog" aria-modal="true" aria-labelledby="sf-reg-modal-title">
	<div class="sf-reg-modal__backdrop"></div>
	<div class="sf-reg-modal__panel">
		<button class="sf-reg-modal__close" aria-label="<?php echo esc_attr( $_sf_str_close_modal ); ?>">
			<i data-lucide="x"></i>
		</button>
		<div class="sf-reg-modal__scroll">
			<div class="sf-reg-modal__inner">
				<p class="sf-reg-modal__eyebrow"><?php echo wp_kses_post( $_sf_str_reg_eyebrow ); ?></p>
				<h2 id="sf-reg-modal-title"><?php echo esc_html( $_sf_str_reg_title ); ?></h2>
				<form id="sf_reg_form">
					<input type="text" name="sf_hp" style="display:none" tabindex="-1" autocomplete="off" aria-hidden="true">
					<input type="hidden" name="sf_page_ts" class="sf-page-ts">
					<input type="hidden" name="sf_section" id="sf_reg_section" value="">
					<div class="row">
						<div class="col">
							<label for="sf_reg_name"><?php echo esc_html( $_sf_str_label_name ); ?></label>
							<input type="text" placeholder="<?php echo esc_attr( $_sf_str_ph_name ); ?>" name="name" id="sf_reg_name" required>
						</div>
						<div class="col">
							<label for="sf_reg_demo"><?php echo esc_html( $_sf_str_label_demo ); ?></label>
							<select name="demo" id="sf_reg_demo" required>
								<option value="Yes"><?php echo esc_html( $_sf_str_demo_yes ); ?></option>
								<option value="No"><?php echo esc_html( $_sf_str_demo_no ); ?></option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_reg_company"><?php echo esc_html( $_sf_str_label_company ); ?></label>
							<input type="text" placeholder="<?php echo esc_attr( $_sf_str_ph_company ); ?>" name="company" id="sf_reg_company" required>
						</div>
						<div class="col">
							<label for="sf_reg_title"><?php echo esc_html( $_sf_str_label_title ); ?></label>
							<input type="text" placeholder="<?php echo esc_attr( $_sf_str_ph_title ); ?>" name="title" id="sf_reg_title" required>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_reg_email"><?php echo esc_html( $_sf_str_label_email ); ?></label>
							<input type="email" placeholder="name@developeremail.com" name="email" id="sf_reg_email" required>
						</div>
						<div class="col">
							<label for="sf_reg_phone"><?php echo esc_html( $_sf_str_label_phone ); ?></label>
							<input type="tel" placeholder="<?php echo esc_attr( $_sf_str_ph_phone ); ?>" name="phone" id="sf_reg_phone" required
								pattern="\d{3}-\d{3}-\d{4}"
								title="Please enter a valid 10-digit phone number (e.g. 555-912-0088)"
								data-parsley-minlength="12"
								data-parsley-minlength-message="This value should be a valid phone number.">
						</div>
					</div>
					<div class="row">
						<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
						<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>" data-theme="auto"></div>
						<?php endif; ?>
					</div>
					<div class="row">
						<input class="button" type="submit" value="<?php echo esc_attr( $_sf_str_submit ); ?>">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Partner Registration Modal -->
<div class="sf-partner-modal" id="sf-partner-modal" role="dialog" aria-modal="true" aria-labelledby="sf-partner-modal-title">
	<div class="sf-partner-modal__backdrop"></div>
	<div class="sf-partner-modal__panel">
		<button class="sf-partner-modal__close" aria-label="Close dialog">
			<i data-lucide="x"></i>
		</button>
		<div class="sf-partner-modal__scroll">
			<div class="sf-partner-modal__inner">
				<p class="sf-partner-modal__eyebrow">Got Clients, Code, or Just Great Contacts? SaleFish Makes It Easy to Earn and Integrate.</p>
				<h2 id="sf-partner-modal-title">Want In? Pick Your Lane. <span>We'll Handle the Rest.</span></h2>
				<form id="sf_partner_form">
					<input type="text" name="sf_hp" style="display:none" tabindex="-1" autocomplete="off" aria-hidden="true">
					<input type="hidden" name="sf_page_ts" class="sf-page-ts">
					<div class="row">
						<div class="col">
							<label for="sf_partner_name">Name</label>
							<input type="text" placeholder="First Last" name="name" id="sf_partner_name" required>
						</div>
						<div class="col">
							<label for="sf_partner_company">Company</label>
							<input type="text" placeholder="Acme Ltd." name="company" id="sf_partner_company">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_partner_phone">Phone Number</label>
							<input type="tel" placeholder="555-912-0088" name="phone" id="sf_partner_phone">
						</div>
						<div class="col">
							<label for="sf_partner_email">Email</label>
							<input type="email" placeholder="name@company.com" name="email" id="sf_partner_email" required>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_partner_want_to_do">What Do You Want to Do?</label>
							<select name="want_to_do" id="sf_partner_want_to_do" required>
								<option value="Refer builders, brokers, or developers">Refer builders, brokers, or developers</option>
								<option value="Resell the SaleFish platform">Resell the SaleFish platform</option>
								<option value="Integrate my app/tool">Integrate my app/tool</option>
								<option value="Something else">Something else</option>
							</select>
						</div>
						<div class="col">
							<label for="sf_partner_clients">How Many Clients Could This Help?</label>
							<select name="clients" id="sf_partner_clients" required>
								<option value="1–3">1–3</option>
								<option value="4–10">4–10</option>
								<option value="10+">10+</option>
							</select>
						</div>
					</div>
					<div class="row">
						<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
						<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>" data-theme="auto"></div>
						<?php endif; ?>
					</div>
					<div class="row">
						<input class="button" type="submit" value="Register">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</template><!-- end #sf-modals-template — modals lazy-injected via window.sfEnsureModals() -->

<div class="thank_you_msg">
	<div class="thank_you_msg__backdrop close_thank_you_msg"></div>
	<div class="thank_you_msg__panel" role="dialog" aria-modal="true" aria-labelledby="sf-thank-you-heading">
		<button class="thank_you_msg__close close_thank_you_msg" aria-label="Close">
			<i data-lucide="x"></i>
		</button>
		<span class="thank_you_msg__eyebrow"><?php echo esc_html( $_sf_str_ty_eyebrow ); ?></span>
		<h2 class="thank_you_msg__heading" id="sf-thank-you-heading"><?php echo esc_html( $_sf_str_ty_h2 ); ?></h2>
		<p class="thank_you_msg__body">
			<?php echo esc_html( $_sf_str_ty_body ); ?>
		</p>
	</div>
</div>

<div class="privacy_policy" role="dialog" aria-modal="true" aria-labelledby="sf-privacy-heading">
	<?php
        $content = get_post(48)
?>
	<div class="wrapper">
		<h2 id="sf-privacy-heading">Privacy Policy</h2>
		<button class="close_privacy" aria-label="Close privacy policy dialog"><i data-lucide="x"></i></button>
		<div class="wrap">
			<?php echo wp_kses_post( $content->post_content ); ?>

		</div>

	</div>
</div>
<div class="terms_popup" role="dialog" aria-modal="true" aria-labelledby="sf-terms-heading">
	<?php
    $content = get_post(39)
?>
	<div class="wrapper">
		<h2 id="sf-terms-heading">Terms of Use</h2>
		<button class="close_terms" aria-label="Close terms of use dialog"><i data-lucide="x"></i></button>
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
				<h2>Builder, Developer <span>or Sales Team</span></h2>
				<a class="profile_popup_close" href="#features">Sales Apps</a>
			</div>
		</div>
		<p class="profile_popup_close">
			Not sure? <span>Explore without choosing a role.</span>
		</p>
	</div>
</div>
<?php endif; ?>
