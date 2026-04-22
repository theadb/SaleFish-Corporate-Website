import { general } from "./general";
import { home } from "./pages/home";
import { content } from "./pages/content";
import { contact_us } from "./pages/contact_us";
import { blog } from "./pages/blog";
import { single_post } from "./pages/single_post";

// ── Scroll-reveal: lightweight IntersectionObserver replacing AOS ───────────
// Elements are always visible on mobile or if JS fails.
// Desktop only: elements with [data-aos] get .sf-anim (hidden) then .sf-in (visible).
(function () {
  // Skip on mobile, reduced-motion preference, or no IntersectionObserver support
  if (
    window.innerWidth < 769 ||
    !window.IntersectionObserver ||
    window.matchMedia('(prefers-reduced-motion: reduce)').matches
  ) {
    return;
  }

  var io = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var delay = parseInt(el.getAttribute('data-aos-delay') || 0, 10);
        if (delay > 0) {
          setTimeout(function () { el.classList.add('sf-in'); }, delay);
        } else {
          el.classList.add('sf-in');
        }
        io.unobserve(el);
      });
    },
    { threshold: 0.01, rootMargin: '0px 0px -24px 0px' }
  );

  // Run after DOM is fully parsed; add .sf-anim to prep initial hidden state
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-aos]').forEach(function (el) {
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
