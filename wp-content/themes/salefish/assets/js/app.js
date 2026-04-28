import { general } from "./general";
import { home } from "./pages/home";
import { content } from "./pages/content";
import { contact_us } from "./pages/contact_us";
import { blog } from "./pages/blog";
import { single_post } from "./pages/single_post";

// ── Image fade-in (apple-style premium polish) ──────────────────────────────
// Every image starts at opacity:0 with a skeleton-shimmer background (see
// _general.scss). Once the image's `load` event fires we add `.sf-loaded`,
// which opacity-fades it in over 420 ms with the canonical iOS easing curve
// cubic-bezier(0.4, 0, 0.2, 1).
//
// LCP-critical images (those marked fetchpriority="high") are excluded by
// the CSS so they paint at full opacity instantly and don't delay LCP.
//
// We attach via event delegation on document so newly-inserted images
// (e.g. swiper clones) also fade in. We also do an initial sweep on
// DOMContentLoaded to catch images that already completed loading from
// the HTTP cache before our handler attached.
(function () {
  function markLoaded(img) {
    if (!img || img.classList.contains('sf-loaded')) return;
    img.classList.add('sf-loaded');
  }
  // Capture-phase listener — `load` doesn't bubble, so we have to use capture
  // to delegate via document.
  document.addEventListener('load', function (e) {
    if (e.target && e.target.tagName === 'IMG') markLoaded(e.target);
  }, true);
  // Initial sweep — covers cached images that loaded before the listener.
  function sweep() {
    document.querySelectorAll('img').forEach(function (img) {
      if (img.complete && img.naturalWidth > 0) markLoaded(img);
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', sweep);
  } else {
    sweep();
  }
  // Re-sweep after window.load too, in case any image just finished.
  window.addEventListener('load', sweep);
}());
