<?php
/**
 * Template Name: Contact Us Page
 * The template for displaying the contact us page
 */

get_header();
?>

<main class="contact_us" id="main-content">

	<!-- INFO -->
	<section class="cu-info">
		<div class="cu-info__wrap">

			<div class="cu-info__lead">
				<span class="cu-eyebrow" data-aos="fade-up" data-aos-delay="100">We'd love to hear from you</span>
				<h1 data-aos="fade-up" data-aos-delay="250">Get In Touch</h1>
				<p data-aos="fade-up" data-aos-delay="400">Whether you have questions, want a walkthrough, or simply want to learn what SaleFish can do for your next project — our team is here.</p>
				<div class="cu-socials" data-aos="fade-up" data-aos-delay="550">
					<a href="https://www.linkedin.com/company/salefishapp/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn (opens in new tab)">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
					</a>
					<a href="https://www.instagram.com/salefishapp/" target="_blank" rel="noopener noreferrer" aria-label="Instagram (opens in new tab)">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
					</a>
					<a href="https://www.threads.com/@salefishapp" target="_blank" rel="noopener noreferrer" aria-label="Threads (opens in new tab)">
						<svg width="20" height="20" viewBox="0 0 192 192" fill="currentColor" aria-hidden="true"><path d="M141.537 88.9883C140.71 88.5919 139.87 88.2104 139.019 87.8451C137.537 60.5382 122.616 44.905 97.5619 44.745C97.4484 44.7443 97.3355 44.7443 97.222 44.7443C82.2364 44.7443 69.7731 51.1421 62.102 62.7807L75.881 72.2328C81.6116 63.5383 90.6052 61.6848 97.2286 61.6848C97.3051 61.6848 97.3819 61.6848 97.4576 61.6855C105.707 61.7381 111.932 64.1366 115.961 68.814C118.893 72.2193 120.854 76.925 121.825 82.8638C114.511 81.6207 106.601 81.2385 98.145 81.7233C74.3247 83.0954 59.0111 96.9879 60.0396 116.292C60.5615 126.084 65.4397 134.508 73.775 140.011C80.8224 144.663 89.899 146.938 99.3323 146.423C111.79 145.74 121.563 140.987 128.381 132.296C133.559 125.696 136.834 117.143 138.28 106.366C144.217 109.949 148.617 114.664 151.047 120.332C155.179 129.967 155.42 145.8 142.501 158.708C131.182 170.016 117.576 174.908 97.0135 175.059C74.2042 174.89 56.9538 167.575 45.7381 153.317C35.2355 139.966 29.8077 120.682 29.6052 96C29.8077 71.3178 35.2355 52.0336 45.7381 38.6827C56.9538 24.4249 74.2039 17.11 97.0132 16.9405C119.988 17.1113 137.539 24.4614 149.184 38.788C154.894 45.8136 159.199 54.6488 162.037 64.9503L178.184 60.6422C174.744 47.9622 169.331 37.0357 161.965 27.974C147.036 9.60668 125.202 0.195148 97.0695 0H96.9569C68.8816 0.19447 47.2921 9.6418 32.7883 28.0793C19.8819 44.4864 13.2244 67.3157 13.0007 95.9325L13 96L13.0007 96.0675C13.2244 124.684 19.8819 147.514 32.7883 163.921C47.2921 182.358 68.8816 191.806 96.9569 192H97.0695C122.03 191.827 139.624 185.292 154.118 170.811C173.081 151.866 172.51 128.119 166.26 113.541C161.776 103.087 153.227 94.5962 141.537 88.9883ZM98.4405 129.507C88.0005 130.095 77.1544 125.409 76.6196 115.372C76.2232 107.93 81.9158 99.626 99.0812 98.6368C101.047 98.5234 102.976 98.468 104.871 98.468C111.106 98.468 116.939 99.0737 122.242 100.233C120.264 124.935 108.662 128.946 98.4405 129.507Z"/></svg>
					</a>
				</div>
			</div>

			<div class="cu-info__cards" data-aos="fade-left">

				<div class="cu-tile">
					<div class="cu-tile__icon">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
					</div>
					<div class="cu-tile__body">
						<strong>Live Chat Support</strong>
						<a href="https://chatting.page/salefish" target="_blank" rel="noopener noreferrer">Chat with our team<span class="sr-only"> (opens in new tab)</span></a>
					</div>
				</div>

				<div class="cu-tile">
					<div class="cu-tile__icon">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
					</div>
					<div class="cu-tile__body">
						<strong>Email Us</strong>
						<a href="mailto:hello@salefish.app">hello@salefish.app</a>
					</div>
				</div>

				<div class="cu-tile">
					<div class="cu-tile__icon">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.6 5.02 2 2 0 0 1 3.59 2.84h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 10.09a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 17.5z"/></svg>
					</div>
					<div class="cu-tile__body">
						<strong>Call Us</strong>
						<a href="tel:+18778927741">1.877.892.7741</a>
						<a href="tel:+19057615364">1.905.761.5364</a>
					</div>
				</div>

				<div class="cu-tile">
					<div class="cu-tile__icon">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
					</div>
					<div class="cu-tile__body">
						<strong>Our Office</strong>
						<a href="https://www.google.com/maps/place/SaleFish/@43.81222,-79.5303027,17z/data=!3m2!4b1!5s0x882b2f0f9f26ad5d:0x5d7ceb7d2cfbb7ab!4m6!3m5!1s0x882b2f0fa479e625:0xb7be17627d10ba9f!8m2!3d43.81222!4d-79.5277278!16s%2Fg%2F11bxfkmkzj?entry=ttu&g_ep=EgoyMDI2MDQxOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener noreferrer">
							8395 Jane Street, Suite 202<br>
							Vaughan, ON, L4K 5Y2<br>
							Canada<span class="sr-only"> (opens in new tab)</span>
						</a>
					</div>
				</div>

			</div>
		</div>
	</section>
	<!-- END INFO -->

	<!-- MAP -->
	<div class="cu-map-wrap">
		<div id="map" class="cu-map" role="region" aria-label="Office location map"></div>

		<a class="cu-hq-card"
		   href="https://www.google.com/maps/place/SaleFish/@43.81222,-79.5303027,17z/data=!3m2!4b1!5s0x882b2f0f9f26ad5d:0x5d7ceb7d2cfbb7ab!4m6!3m5!1s0x882b2f0fa479e625:0xb7be17627d10ba9f!8m2!3d43.81222!4d-79.5277278!16s%2Fg%2F11bxfkmkzj?entry=ttu"
		   target="_blank" rel="noopener noreferrer"
		   aria-label="SaleFish HQ — 8395 Jane Street, Vaughan — Get Directions (opens in new tab)">

			<div class="cu-hq-card__header">
				<div class="cu-hq-card__pin">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
				</div>
				<span class="cu-hq-card__label">SaleFish HQ</span>
			</div>

			<div class="cu-hq-card__body">
				<p class="cu-hq-card__addr">
					8395 Jane Street, Suite 202<br>
					Vaughan, ON&nbsp;&nbsp;L4K 5Y2<br>
					Canada
				</p>
				<span class="cu-hq-card__cta">
					Get Directions
					<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
				</span>
			</div>

		</a>
	</div>
	<!-- END MAP -->

	<!-- FORM -->
	<section class="contact">
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<?php get_template_part('/partials/contact-general'); ?>
	</section>
	<!-- END FORM -->


</main>

<?php get_footer(); ?>
<!-- Google Maps loader. The HTML `async` attribute makes the script load
     non-blocking. We tried adding `loading=async` to the URL too (per Google's
     newer best-practice guidance) but it caused the map tiles to stop
     rendering — likely a conflict with the legacy initMap callback pattern.
     The remaining "loaded directly without loading=async" warning is purely
     informational and accepted as a tradeoff for a working map. -->
<script async src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr( defined( 'GOOGLE_MAPS_API_KEY' ) ? GOOGLE_MAPS_API_KEY : '' ); ?>&callback=initMap"></script>
