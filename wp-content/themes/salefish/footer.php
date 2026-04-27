<?php
/**
 * The template for displaying the footer.
 */

?>


<?php wp_footer(); ?>
<footer>
	<div class="max_wrapper">
		<div class="salefish">
			<img class="salefish_logo" src="<?php echo get_template_directory_uri(); ?>/img/dark_salefish_logo.png"
				alt="Salefish">
		</div>
		<div class="links us_links">
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
						<a href="/blog">All Articles</a>
					</li>
					<li>
						<a href="/blog/success-stories">Success Stories</a>
					</li>
					<li>
						<a href="/blog/press">Press</a>
					</li>
					<li>
						<a href="/blog/videos">Videos</a>
					</li>
				</ul>
			</div>
			<div class="col">
				<div class="title">Navigation</div>
				<ul>
					<li>
						<a href="/#features">Features</a>
					</li>
					<li>
						<a href="/our-story">Our Story</a>
					</li>
					<li>
						<a href="/awards">Awards</a>
					</li>
					<li>
						<a href="/partners">Partners</a>
					</li>
					<li>
						<a href="/blog">Blog</a>
					</li>
					<li>
						<a href="/contact-us">Contact Us</a>
					</li>
				</ul>
			</div>
			<div class="col flex_img">
				<a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">
					<img class="plus_group" src="<?php echo get_template_directory_uri(); ?>/img/plus_group.png" alt="Plus Group" loading="lazy" decoding="async">
				</a>
			</div>
		</div>
		<div class="links de_links">
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
						<a href="/our-story">Unsere Geschichte</a>
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
					<img class="plus_group" src="<?php echo get_template_directory_uri(); ?>/img/plus_group.png" alt="Plus Group" loading="lazy" decoding="async">

				</a>
			</div>
		</div>
		<div class="links tr_links">
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
						<a href="/our-story">Hikayemiz</a>
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
					<img class="plus_group" src="<?php echo get_template_directory_uri(); ?>/img/plus_group.png" alt="Plus Group" loading="lazy" decoding="async">

				</a>
			</div>
		</div>
		<div class="bottom">
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
					Made with <svg class="heart" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#e53e3e" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg> in Toronto. &copy; <?php echo date( 'Y' ); ?> SaleFish Inc. All rights reserved.<span class="legal-sep">&nbsp;&middot;&nbsp;</span><span class="legal-links"><a href="/terms-of-use"><?php echo esc_html( $_sf_terms ); ?></a> &nbsp;&middot;&nbsp; <a href="/privacy-policy"><?php echo esc_html( $_sf_privacy ); ?></a></span>
				</p>
			</div>
		</div>
	</div>

</footer>
<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<?php endif; ?>
<!-- jQuery and smooth-scroll are bundled into app.js via webpack — no CDN requests needed -->
<!-- isotope-layout: removed — was imported but never called; CDN request was dead weight -->
<script>
const BASEURL = '<?php echo get_template_directory_uri(); ?>';
</script>

<script type="text/javascript">
_linkedin_partner_id = "2438284";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script>
<script type="text/javascript">
(function(l) {
if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
window.lintrk.q=[]}
var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})(window.lintrk);
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=2438284&fmt=gif" />
</noscript>
<style>.sf-hp-field{display:none !important;position:absolute;left:-9999px;}</style>

<!-- ── Video Dialog ──────────────────────────────────────────────────────── -->
<div id="sf-video-dialog" class="sf-video-dialog" role="dialog" aria-modal="true" aria-label="Video player" hidden>
  <div class="sf-video-dialog__backdrop"></div>
  <div class="sf-video-dialog__panel">
    <button class="sf-video-dialog__close" aria-label="Close video">
      <i data-lucide="x"></i>
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

<!-- Pinned to a specific version so the browser can cache across visits.
     v0.468.0 is post-brand-icon removal (linkedin/instagram were removed in v0.453).
     To update: change the version number and clear the CDN cache. -->
<script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>
</body>

</html>