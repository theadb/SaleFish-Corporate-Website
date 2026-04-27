import { general } from "./general";
import { home } from "./pages/home";
import { content } from "./pages/content";
import { contact_us } from "./pages/contact_us";
import { blog } from "./pages/blog";
import { single_post } from "./pages/single_post";

// ── Scroll-reveal: lightweight IntersectionObserver ─────────────────────────
// Elements are always visible on mobile (< 769px) or if JS is unavailable.
// On desktop: [data-aos] elements get .sf-anim (initial hidden state) then
// .sf-in (revealed) when they approach the viewport.
// Supports data-aos direction variants: fade-up (default), fade-down,
//   fade-left, fade-right, fade-in (opacity only, no translate).
// Supports data-aos-delay (ms) and data-aos-duration (ms, default 520).
//
// Fast-scroll resilience: two complementary strategies
//   1. Large rootMargin (350 px desktop / 160 px mobile) — elements are
//      queued for reveal well before they reach the viewport, so even at
//      high scroll velocity the IO callback has time to fire.
//   2. Scroll-stop sweep — 120 ms after scrolling ceases, any .sf-anim
//      element already inside the viewport is revealed immediately (no
//      transition delay) so nothing stays hidden after a fast scroll.
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
    // When called from the sweep (skipDelay=true) don't honour data-aos-delay
    // so elements that were already past the trigger zone appear instantly.
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
      // Large look-ahead: start the reveal animation well before the element
      // reaches the visible area, giving the IO callback time to fire even
      // when the user scrolls quickly.
      rootMargin: isMobile ? '0px 0px 160px 0px' : '0px 0px 350px 0px',
    }
  );

  // Sweep: after scrolling stops, immediately reveal any .sf-anim element
  // whose top edge is already inside (or above) the viewport.
  var sweepTimer;
  function sweep() {
    var vh = window.innerHeight;
    document.querySelectorAll('.sf-anim:not(.sf-in)').forEach(function (el) {
      if (el.getBoundingClientRect().top < vh) {
        revealEl(el, true);
        io.unobserve(el);
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-aos]').forEach(function (el) {
      // Store direction for CSS to read via a data attribute (simpler than class)
      var dir = el.getAttribute('data-aos') || 'fade-up';
      el.setAttribute('data-sf-dir', dir);
      el.classList.add('sf-anim');
      io.observe(el);
    });

    window.addEventListener('scroll', function () {
      clearTimeout(sweepTimer);
      sweepTimer = setTimeout(sweep, 120);
    }, { passive: true });
  });
}());

// ── Loading overlay & footer visibility ──────────────────────────────────────
//
// Two separate concerns, two separate timers:
//
// 1. Overlay — dismissed at DOMContentLoaded + 100 ms so the page content is
//    visible as soon as the HTML and CSS are parsed. We do NOT wait for
//    window.load because third-party scripts (Tidio, GTM, Turnstile) can delay
//    that event by 2–5 s, causing a blank-screen experience the user sees as
//    the site being broken.
//
// 2. Footer — shown at window.load + 150 ms (keeps footer hidden during the
//    brief period before layout stabilises). Safety cap at 3 s so a hung
//    third-party script never hides the footer indefinitely.
//
var _sfOverlayDone = false;
function _sfDismissOverlay() {
  if (_sfOverlayDone) return;
  _sfOverlayDone = true;
  $(".loading").addClass("active");
}

var _sfFooterShown = false;
function _sfShowFooter() {
  if (_sfFooterShown) return;
  _sfFooterShown = true;
  _sfDismissOverlay(); // belt-and-suspenders: ensure overlay is gone
  $("footer").css("display", "block");
}

// Dismiss overlay as soon as the DOM is ready
document.addEventListener('DOMContentLoaded', function () {
  setTimeout(_sfDismissOverlay, 100);
});
// Absolute safety cap for overlay (e.g. slow device where DOMContentLoaded
// is already in the past by the time this script executes)
setTimeout(_sfDismissOverlay, 1500);

// Show footer once all page resources have loaded
window.addEventListener("load", function () {
  setTimeout(_sfShowFooter, 150);
});
// Safety cap: always show footer within 3 s regardless of load state.
setTimeout(_sfShowFooter, 3000);
