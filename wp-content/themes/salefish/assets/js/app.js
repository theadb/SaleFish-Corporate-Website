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
(function () {
  if (
    !window.IntersectionObserver ||
    window.matchMedia('(prefers-reduced-motion: reduce)').matches
  ) {
    return;
  }

  var isMobile = window.innerWidth < 769;

  // On mobile use a simpler, quicker fade (no translate) so the page feels
  // lively but never blocked by slow reveal timings.
  var io = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var delay = parseInt(el.getAttribute('data-aos-delay') || 0, 10);

        function reveal() {
          el.classList.add('sf-in');
          // Apply custom duration if provided
          var dur = el.getAttribute('data-aos-duration');
          if (dur) el.style.transitionDuration = parseInt(dur, 10) + 'ms';
        }

        if (delay > 0) {
          setTimeout(reveal, delay);
        } else {
          reveal();
        }
        io.unobserve(el);
      });
    },
    {
      threshold: 0.01,
      // Positive bottom margin: fire when element is still 80px below viewport
      // so the reveal is underway by the time the element scrolls into full view.
      rootMargin: isMobile ? '0px 0px 40px 0px' : '0px 0px 80px 0px',
    }
  );

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-aos]').forEach(function (el) {
      // Store direction for CSS to read via a data attribute (simpler than class)
      var dir = el.getAttribute('data-aos') || 'fade-up';
      el.setAttribute('data-sf-dir', dir);
      el.classList.add('sf-anim');
      io.observe(el);
    });
  });
}());

window.addEventListener("load", function () {
  setTimeout(function () {
    $(".loading").addClass("active");
    $("footer").css("display", "block");
  }, 150);
});
