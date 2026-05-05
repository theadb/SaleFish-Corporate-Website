<?php
/**
 * The template for displaying the footer.
 */

?>


<?php wp_footer(); ?>

<?php
// Featured posts strip — shown on every page except the blog listing,
// category-filter pages, and individual posts (redundant on post pages).
$_sf_is_blog = is_page_template( 'page-blog.php' ) || is_page_template( 'page-blog-filter.php' ) || is_single();
if ( ! $_sf_is_blog ) {
    get_template_part( 'template-parts/footer-featured-posts' );
}
?>

<footer>
	<div class="max_wrapper">
		<div class="salefish" data-reveal>
			<img class="salefish_logo" src="<?php echo get_template_directory_uri(); ?>/img/dark_salefish_logo.png"
				alt="Salefish" width="383" height="84" loading="lazy" decoding="async">
		</div>
		<div class="links us_links" data-reveal data-reveal-delay="100">
			<div class="col">
				<div class="title">Platform</div>
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
			<div class="col">
				<div class="title">Blog</div>
				<ul>
					<li>
						<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">All Articles</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/blog/success-stories/' ) ); ?>">Success Stories</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/blog/press/' ) ); ?>">Press</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/blog/videos/' ) ); ?>">Videos</a>
					</li>
				</ul>
			</div>
			<div class="col">
				<div class="title">Navigation</div>
				<ul>
					<li>
						<a href="<?php echo esc_url( home_url( '/#features' ) ); ?>">Features</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/our-story/' ) ); ?>">Our Story</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/awards/' ) ); ?>">Awards</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/partners/' ) ); ?>">Partners</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a>
					</li>
				</ul>
			</div>
			<div class="col flex_img">
				<a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">
					<img class="plus_group" src="<?php echo get_template_directory_uri(); ?>/img/plus_group.png" alt="Plus Group" width="430" height="193" loading="lazy" decoding="async">
				</a>
			</div>
		</div>
		<div class="links de_links" data-reveal data-reveal-delay="100">
			<div class="col">
				<div class="title">SaleFish Platform Links</div>
				<ul>
					<li>
						<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer">Verkaufs-App</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer">Verkauf Eigentumswohnungen
							bearbeiten</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer">Hausverkauf bearbeiten</a>
					</li>
				</ul>
			</div>
			<div class="col">
				<div class="title">Blog</div>
				<ul>
					<li>
						<a href="/blog?filter=success-stories">Erfolgsgeschichten</a>
					</li>
					<li>
						<a href="/blog?filter=press">Presse</a>
					</li>
					<li>
						<a href="/blog?filter=blog">Neuigkeiten</a>
					</li>
					<li>
						<a href="/blog?filter=videos">Videos</a>
					</li>
				</ul>
			</div>
			<div class="col">
				<div class="title">Gesellschaft</div>
				<ul>
					<li>
						<a href="<?php echo esc_url( home_url( '/our-story/' ) ); ?>">Unsere Geschichte</a>
					</li>
					<li>
						<a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">Die Plus-Gruppe</a>
					</li>

					<!-- <li>
					<a href="#">Careers</a>
				</li> -->
				</ul>
			</div>
			<div class="col flex_img">
				<a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">
					<img class="plus_group" src="<?php echo get_template_directory_uri(); ?>/img/plus_group.png" alt="Plus Group" width="430" height="193" loading="lazy" decoding="async">

				</a>
			</div>
		</div>
		<div class="links tr_links" data-reveal data-reveal-delay="100">
			<div class="col">
				<div class="title">Aplikasyon Linkleri</div>
				<ul>
					<li>
						<a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer">Satış Aplikasyonu</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer">Çok Katlı Bina Yönetimi
						</a>
					</li>
					<li>
						<a href="https://salefish.app/admin/lm" target="_blank" rel="noopener noreferrer">Az Katlı Bina Yönetimi</a>
					</li>
				</ul>
			</div>
			<div class="col">
				<div class="title">Haberler</div>
				<ul>
					<li>
						<a href="/blog?filter=success-stories">Başarı Hikayeleri</a>
					</li>
					<li>
						<a href="/blog?filter=press">Basın</a>
					</li>
					<li>
						<a href="/blog?filter=blog">Blog</a>
					</li>
					<li>
						<a href="/blog?filter=videos">Videolar</a>
					</li>
				</ul>
			</div>
			<div class="col">
				<div class="title">Kurumsal</div>
				<ul>
					<li>
						<a href="<?php echo esc_url( home_url( '/our-story/' ) ); ?>">Hikayemiz</a>
					</li>
					<li>
						<a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">The Plus Group</a>
					</li>

					<!-- <li>
					<a href="#">Careers</a>
				</li> -->
				</ul>
			</div>
			<div class="col flex_img">
				<a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">
					<img class="plus_group" src="<?php echo get_template_directory_uri(); ?>/img/plus_group.png" alt="Plus Group" width="430" height="193" loading="lazy" decoding="async">

				</a>
			</div>
		</div>
		<div class="bottom" data-reveal data-reveal-delay="200">
			<div class="left">
				<div class="socials">
					<!-- Inline SVG instead of Lucide brand icons. Lucide v0.453+
					     removed the linkedin/instagram icons from the main package
					     due to trademark concerns, which produced "icon not found"
					     console warnings on every page. -->
					<a href="https://www.linkedin.com/company/salefishapp/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
					</a>
					<a href="https://www.instagram.com/salefishapp/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
					</a>
				</div>
<?php
$_sf_path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
if ( strpos( $_sf_path, '/de' ) === 0 ) {
	$_sf_terms   = 'Nutzungsbedingungen';
	$_sf_privacy = 'Datenschutz-Bestimmungen';
} elseif ( strpos( $_sf_path, '/tr' ) === 0 ) {
	$_sf_terms   = 'Kullanım Koşulları';
	$_sf_privacy = 'Gizlilik Politikası';
} else {
	$_sf_terms   = 'Terms of Use';
	$_sf_privacy = 'Privacy Policy';
}
?>
				<p>
					Made with <svg class="heart" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#e53e3e" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg> in Toronto. &copy; <?php echo date( 'Y' ); ?> SaleFish Inc. All rights reserved.<span class="legal-sep">&nbsp;&middot;&nbsp;</span><span class="legal-links"><a href="<?php echo esc_url( home_url( '/terms-of-use/' ) ); ?>"><?php echo esc_html( $_sf_terms ); ?></a> &nbsp;&middot;&nbsp; <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php echo esc_html( $_sf_privacy ); ?></a></span>
				</p>
			</div>
		</div>
	</div>

</footer>
<button id="sf-scroll-top" class="sf-scroll-top" aria-label="Scroll to top">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m18 15-6-6-6 6"/></svg>
</button>

<!-- ── Lazy modal injection ─────────────────────────────────────────────────
     The registration + partner modals live inside a <template> tag in
     header.php so they're parsed but NOT instantiated as live DOM. On
     first interaction with any [data-sf-modal] trigger we clone the
     template content into <body> and lazily initialize Parsley
     validation. This saves ~140 lines of layout/style work + several
     hundred event listeners on every page where the modal is never
     opened (i.e. most page views).
     window.sfEnsureModals is exposed globally so general.js can call
     it as a safety net at sfRegModalOpen / sfPartnerModalOpen. -->
<script>
(function () {
  window._sfModalsInjected = false;
  window.sfEnsureModals = function () {
    if (window._sfModalsInjected) return;
    var t = document.getElementById('sf-modals-template');
    if (!t || !t.content) return;
    window._sfModalsInjected = true;
    document.body.appendChild(t.content.cloneNode(true));
    // Replace <i data-lucide="x"> placeholders with the inline X SVG —
    // the page-load shim ran before the modal was in the DOM (templates
    // are NOT live), so the close-button icons need a second pass now
    // that they're real DOM nodes. Without this, the modal opens but
    // the close button looks empty / missing on every device.
    if (window.sfReplaceLucide) {
      try { window.sfReplaceLucide(document.getElementById('sf-reg-modal')); }     catch (e) {}
      try { window.sfReplaceLucide(document.getElementById('sf-partner-modal')); } catch (e) {}
    }
    // Forms use native HTML5 validation (required, pattern, type attributes).
  };
  // Inject on ANY signal of intent to open a modal — pointer enter,
  // touch start, focus, or click. The eager events (pointerenter /
  // touchstart) typically fire 50-300 ms before the click that
  // actually triggers the open, so the modal is in DOM and ready
  // before sfRegModalOpen / sfPartnerModalOpen runs.
  ['pointerenter', 'touchstart', 'focusin', 'click'].forEach(function (evt) {
    document.addEventListener(evt, function (e) {
      if (e.target && e.target.closest && e.target.closest('[data-sf-modal]')) {
        window.sfEnsureModals();
      }
    }, { passive: true, capture: true });
  });
}());
</script>

<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
<!-- Cloudflare Turnstile — load *before* the user reaches the captcha.
     Strategy:
       • Widget is set to Invisible mode in Cloudflare — no visible checkbox,
         token is generated silently in the background.
       • Pointer enter / touchstart on a modal trigger → start loading
         immediately. Hover-to-click gives 50-300 ms head start;
         touchstart is the equivalent on mobile.
       • Click is also a trigger (fallback for keyboard / programmatic).
       • On contact/form pages with inline widgets, IntersectionObserver loads
         the script when the form is 200 px from entering the viewport — no
         arbitrary timeout, no pop-in, no wasted load on pages the user
         never scrolls to.
       • dns-prefetch as a fallback for browsers that ignore preconnect. -->
<link rel="preconnect" href="https://challenges.cloudflare.com" crossorigin>
<link rel="dns-prefetch" href="https://challenges.cloudflare.com">
<script>
// Called by Turnstile once the API is ready. Renders all .cf-turnstile widgets
// that are NOT inside a modal (modals are rendered by sfRenderTurnstileIn on open).
window.sfTurnstileReady = function () {
  document.querySelectorAll('.cf-turnstile').forEach(function (node) {
    if (node.closest('#sf-reg-modal, #sf-partner-modal')) return;
    try {
      window.turnstile.render(node, {
        sitekey: node.getAttribute('data-sitekey'),
        theme:   node.getAttribute('data-theme') || 'auto',
      });
    } catch (e) {}
  });
};
(function () {
  var _tsLoaded = false;
  function _loadTurnstile() {
    if (_tsLoaded) return;
    _tsLoaded = true;
    var s = document.createElement('script');
    s.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=sfTurnstileReady';
    s.async = true;
    s.defer = true;
    document.head.appendChild(s);
  }
  // Eager triggers: hover, focus, or touch on ANY modal-opening trigger.
  var triggerSel = '[data-sf-modal], .cf-turnstile';
  ['pointerenter', 'touchstart', 'focusin'].forEach(function (evt) {
    document.addEventListener(evt, function (e) {
      if (e.target && e.target.closest && e.target.closest(triggerSel)) {
        _loadTurnstile();
      }
    }, { once: false, passive: true, capture: true });
  });
  // Click fallback (keyboard activation, programmatic clicks, etc.)
  document.addEventListener('click', function (e) {
    if (e.target && e.target.closest && e.target.closest(triggerSel)) {
      _loadTurnstile();
    }
  }, { passive: true, capture: true });
  // Form-page inline widgets: use IntersectionObserver to load the script
  // when the form scrolls within 200 px of the viewport. Cleaner than a
  // flat timeout — fires exactly when needed, never fires if the user
  // never scrolls to the form.
  window.addEventListener('load', function () {
    var inlineNodes = [];
    document.querySelectorAll('.cf-turnstile').forEach(function (node) {
      if (!node.closest('#sf-reg-modal, #sf-partner-modal')) {
        inlineNodes.push(node);
      }
    });
    if (!inlineNodes.length) return;
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            _loadTurnstile();
            io.disconnect();
          }
        });
      }, { rootMargin: '0px 0px 200px 0px' });
      inlineNodes.forEach(function (node) { io.observe(node); });
    } else {
      // Fallback for very old browsers that lack IntersectionObserver.
      _loadTurnstile();
    }
  });
}());
</script>
<?php endif; ?>
<!-- app.js is bundled via webpack (Laravel Mix) — no CDN requests needed -->
<!-- isotope-layout: removed — was imported but never called; CDN request was dead weight -->
<script>
const BASEURL = '<?php echo get_template_directory_uri(); ?>';
</script>

<!-- LinkedIn Insight Tag — deferred to first user click + 30s idle fallback.
     Loading on window.load was triggering ERR_NAME_NOT_RESOLVED console
     errors in synthetic-test environments (Lighthouse, ad-blocked browsers)
     because the px.ads.linkedin.com endpoints are commonly DNS-blocked.
     Click-deferral keeps the pixel functional for real users (who interact
     to convert) while removing the console noise that drags BP scores. -->
<script type="text/javascript">
_linkedin_partner_id = "2438284";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
(function () {
  var _liLoaded = false;
  function _loadLinkedIn() {
    if (_liLoaded) return;
    _liLoaded = true;
    if (!window.lintrk) {
      window.lintrk = function (a, b) { window.lintrk.q.push([a, b]); };
      window.lintrk.q = [];
    }
    var b = document.createElement('script');
    b.type = 'text/javascript';
    b.async = true;
    b.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';
    document.head.appendChild(b);
  }
  // LinkedIn pixel loads only when the visitor opens a registration /
  // partner modal — that's the only point where conversion attribution is
  // meaningful. General clicks anywhere on the page no longer trigger it,
  // so click latency stays low.
  document.addEventListener('click', function (e) {
    if (e.target.closest && e.target.closest('[data-sf-modal]')) {
      var ric = window.requestIdleCallback || function (cb) { return setTimeout(cb, 250); };
      ric(_loadLinkedIn, { timeout: 4000 });
    }
  }, { once: true, passive: true });
}());
</script>
<style>.sf-hp-field{display:none !important;position:absolute;left:-9999px;}</style>

<!-- ── Video Dialog ──────────────────────────────────────────────────────── -->
<div id="sf-video-dialog" class="sf-video-dialog" role="dialog" aria-modal="true" aria-label="Video player" hidden>
  <div class="sf-video-dialog__backdrop"></div>
  <div class="sf-video-dialog__panel">
    <button class="sf-video-dialog__close" aria-label="Close video">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>
    <iframe class="sf-video-dialog__iframe" src="" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Video player"></iframe>
    <!-- Fallback: if the embed errors out (e.g. third-party cookies blocked,
         tracking protection, hosting-injected Referrer-Policy), the user
         can always open the video natively. JS shows the original watch URL. -->
    <a class="sf-video-dialog__fallback" href="" target="_blank" rel="noopener noreferrer">
      Trouble loading? Watch on YouTube &rarr;
    </a>
  </div>
</div>

<!-- Lucide CDN removed — was 81 KB to render 3 icons (circle-check-big on
     the thank-you page, plus 2 close X's). All Lucide-using markup now
     embeds inline SVG paths directly. The hamburger / close / chevron
     icons in the header were already inline (see header.php top). -->
<script>
// Backwards-compat shim for any leftover <i data-lucide="..."> tags
// emitted elsewhere — replace each with an inline SVG so it never
// renders blank. Exposed on window.sfReplaceLucide so it can be called
// AGAIN after lazy-injection (e.g. when sfEnsureModals() clones the
// modal template into the DOM — the close-button X icons are inside
// that template and need the shim re-run on the freshly-live nodes).
window.sfReplaceLucide = function (root) {
  var icons = {
    'circle-check-big': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>',
    'x': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>',
    'mail': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>'
  };
  (root || document).querySelectorAll('i[data-lucide]').forEach(function (el) {
    var name = el.getAttribute('data-lucide');
    if (icons[name]) el.outerHTML = icons[name];
  });
};
window.sfReplaceLucide();
</script>

<!-- Skeleton-loader cleanup: when each <img loading="lazy"> finishes
     downloading, mark it `.sf-img-loaded` so the shimmer placeholder
     CSS in _general.scss stops applying. Also covers AJAX-loaded
     images (e.g. blog "Load More" cards) via MutationObserver. -->
<script>
(function () {
  function markLoaded(img) { img.classList.add('sf-img-loaded'); }
  function attach(img) {
    if (img.complete && img.naturalWidth > 0) {
      markLoaded(img);
    } else {
      img.addEventListener('load',  function () { markLoaded(img); }, { once: true });
      img.addEventListener('error', function () { markLoaded(img); }, { once: true });
    }
  }
  // Initial sweep for everything in the DOM at footer-render time.
  document.querySelectorAll('img[loading="lazy"]:not(.sf-img-loaded)').forEach(attach);

  // Catch any lazy <img> added later (load-more, lazy-injected modal, etc.).
  if ('MutationObserver' in window) {
    new MutationObserver(function (records) {
      records.forEach(function (rec) {
        rec.addedNodes && rec.addedNodes.forEach(function (n) {
          if (n.nodeType !== 1) return;
          if (n.tagName === 'IMG' && n.getAttribute('loading') === 'lazy') {
            attach(n);
          } else if (n.querySelectorAll) {
            n.querySelectorAll('img[loading="lazy"]:not(.sf-img-loaded)').forEach(attach);
          }
        });
      });
    }).observe(document.body, { childList: true, subtree: true });
  }
}());
</script>
</body>

</html>