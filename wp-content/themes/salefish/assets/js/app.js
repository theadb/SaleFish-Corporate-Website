import { general } from "./general";
import { home } from "./pages/home";
import { content } from "./pages/content";
import { contact_us } from "./pages/contact_us";
import { blog } from "./pages/blog";
import { single_post } from "./pages/single_post";

// ── Scroll-reveal: content-first IntersectionObserver ───────────────────────
// Architecture (rewritten 2026-04-28 to fix Safari/iOS slow-loading feel):
//
//   1. Content is visible by default — we never hide an element unless we are
//      certain it is BELOW the current viewport. That eliminates the previous
//      pattern's "flash of visible → flash of hidden → fade in" stutter that
//      Safari users experienced on every navigation.
//
//   2. The CSS hide/reveal rules live under `html.sf-has-anim` (set inline in
//      <head>). If JS never runs, the class is never added and content stays
//      visible — guaranteed graceful degradation.
//
//   3. A 1.5 s hard safety timer in <head> sets `html.sf-revealed`, which
//      force-reveals every animated element regardless of IO state. Hidden
//      content can never outlast 1.5 s of the page lifecycle.
//
//   4. Below-the-fold elements still fade in as they scroll into view; the
//      animation is preserved for visual polish but no longer gates first
//      paint.
(function () {
  if (
    !window.IntersectionObserver ||
    window.matchMedia('(prefers-reduced-motion: reduce)').matches
  ) {
    return;
  }

  var isMobile = window.innerWidth < 769;

  function revealEl(el, skipDelay) {
    el.classList.add('sf-in');
    var dur = el.getAttribute('data-aos-duration');
    if (dur) el.style.transitionDuration = parseInt(dur, 10) + 'ms';
    if (skipDelay) el.style.transitionDelay = '0ms';
  }

  var io = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var delay = parseInt(el.getAttribute('data-aos-delay') || 0, 10);
        if (delay > 0) {
          setTimeout(function () { revealEl(el, false); }, delay);
        } else {
          revealEl(el, false);
        }
        io.unobserve(el);
      });
    },
    {
      threshold: 0,
      rootMargin: isMobile ? '0px 0px 160px 0px' : '0px 0px 350px 0px',
    }
  );

  function init() {
    // Scroll position at the moment we initialise. If the user is at the top
    // (the common case), every element with top < viewport-height is treated
    // as above-the-fold and stays visible without animation. If the user
    // navigated to a hash deep in the page, the same logic still works.
    var vh = window.innerHeight;
    var triggerLine = vh; // any element with top < this is already visible

    document.querySelectorAll('[data-aos]').forEach(function (el) {
      var top = el.getBoundingClientRect().top;

      // Above the fold — render visible immediately, no animation, no class.
      // This is the key change: we don't add .sf-anim to elements that are
      // already on screen, so they never flash hidden.
      if (top < triggerLine) return;

      // Below the fold — set up the fade-in animation
      var dir = el.getAttribute('data-aos') || 'fade-up';
      el.setAttribute('data-sf-dir', dir);
      el.classList.add('sf-anim');
      io.observe(el);
    });

    // Sweep: 120 ms after every scroll-stop, reveal any .sf-anim element whose
    // top edge has crossed the viewport. Catches IO callback races during
    // fast scrolls or Safari's conservative scheduling.
    var sweepTimer;
    function sweep() {
      var vhNow = window.innerHeight;
      document.querySelectorAll('.sf-anim:not(.sf-in)').forEach(function (el) {
        if (el.getBoundingClientRect().top < vhNow) {
          revealEl(el, true);
          io.unobserve(el);
        }
      });
    }
    window.addEventListener('scroll', function () {
      clearTimeout(sweepTimer);
      sweepTimer = setTimeout(sweep, 120);
    }, { passive: true });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
}());
