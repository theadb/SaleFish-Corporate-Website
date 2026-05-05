document.addEventListener('DOMContentLoaded', function () {
	var page = (document.querySelector('main') || {}).className || '';
	if (page === 'single_post') {
		var logoEls   = document.querySelectorAll('header .salefish_logo');
		var arrowEls  = document.querySelectorAll('.down_arrow');
		var DARK_LOGO = BASEURL + '/img/dark_salefish_logo.png';
		var LITE_LOGO = BASEURL + '/img/salefish_logo.png';
		var DARK_ARR  = BASEURL + '/img/down_arrow.svg';
		var LITE_ARR  = BASEURL + '/img/purple_down_arrow.svg';

		function setLogos(scrolled) {
			var logoSrc  = scrolled ? LITE_LOGO : DARK_LOGO;
			var arrowSrc = scrolled ? DARK_ARR  : LITE_ARR;
			logoEls.forEach(function (el)  { if (el.getAttribute('src') !== logoSrc)  el.setAttribute('src',  logoSrc);  });
			arrowEls.forEach(function (el) { if (el.getAttribute('src') !== arrowSrc) el.setAttribute('src', arrowSrc); });
		}

		var lastScrolled = null;
		var ticking = false;
		function check() {
			ticking = false;
			var scrolled = window.scrollY > 1;
			if (scrolled !== lastScrolled) {
				lastScrolled = scrolled;
				setLogos(scrolled);
			}
		}
		setLogos(false);
		check();
		window.addEventListener('scroll', function () {
			if (!ticking) { ticking = true; requestAnimationFrame(check); }
		}, { passive: true });
	}
});
