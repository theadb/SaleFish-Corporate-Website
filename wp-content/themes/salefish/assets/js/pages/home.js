import Swiper, { Navigation } from "swiper";
import { CountUp } from "countup.js";

document.addEventListener('DOMContentLoaded', function () {
  var page = (document.querySelector('main') || {}).className || '';
  if (page === 'home') {
    var count1El = document.getElementById('count_1');
    var count2El = document.getElementById('count_2');
    var count3El = document.getElementById('count_3');

    var count_1 = new CountUp('count_1', count1El ? Number(count1El.dataset.number) : 0, { startVal: 0 });
    var count_2 = new CountUp('count_2', count2El ? Number(count2El.dataset.number) : 0, { startVal: 0 });
    var count_3 = new CountUp('count_3', count3El ? Number(count3El.dataset.number) : 0, { startVal: 0, decimalPlaces: 1 });

    // One-shot IntersectionObserver replaces the previous resize+scroll
    // listener that called .offset() on every scroll event. The old handler
    // forced a layout read on every scroll tick and was a major Safari jank
    // source on the homepage. The observer fires once when the contact
    // section enters the viewport, then disconnects.
    var contactTrigger = document.querySelector('.contact .content .col');
    if (contactTrigger && 'IntersectionObserver' in window) {
      var countStarted = false;
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting && !countStarted) {
            countStarted = true;
            count_1.start();
            count_2.start();
            count_3.start();
            io.disconnect();
          }
        });
      }, { rootMargin: '0px 0px -10% 0px' });
      io.observe(contactTrigger);
    } else if (contactTrigger) {
      count_1.start(); count_2.start(); count_3.start();
    }

    new Swiper('.pillarsSwiper', {
      modules: [Navigation],
      slidesPerView: 1,
      spaceBetween: 50,
      loop: true,
      navigation: {
        nextEl: '.pillarsSwiper .right_arrow',
        prevEl: '.pillarsSwiper .left_arrow',
      },
      breakpoints: {
        769: {
          enabled: false,
          loop: false,
        },
      },
    });

    // ── Logo marquee — JS RAF replaced with pure CSS ────────────────────────
    // Pure CSS keyframe on .builders_track translates by -50% over 28s.
    // Markup duplicates the logo set so -50% reaches the start of the second
    // set — a seamless infinite loop. No JS, no RAF throttling, no measurement
    // race conditions.

    new Swiper('.numbersSwiper', {
      modules: [Navigation],
      loop: true,
      navigation: {
        nextEl: '.numbersSwiperArrow .right_arrow',
        prevEl: '.numbersSwiperArrow .left_arrow',
      },
    });
  }

  // ── Hero text rotator (lightweight rebuild) ─────────────────────────────
  // All textArray words are pre-rendered into the #app_for_home span as
  // stacked grid items. The cycle is just a classList swap — CSS opacity
  // transitions (compositor-only) handle the crossfade. Paused when the
  // hero scrolls off-screen (IntersectionObserver) or tab is hidden.
  (function () {
    var el = document.getElementById('app_for_home');
    if (!el) return;
    var words = (typeof textArray !== 'undefined' && Array.isArray(textArray)) ? textArray.slice() : [];
    if (!words.length) {
      var t = (el.textContent || '').trim();
      if (t) words = [t];
    }
    if (words.length < 2) return;
    if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    function esc(s) {
      return String(s).replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
      });
    }
    el.classList.add('sf-rotator');
    el.innerHTML = words.map(function (w, i) {
      return '<span' + (i === 0 ? ' class="is-active"' : '') + '>' + esc(w) + '</span>';
    }).join('');

    var spans = el.querySelectorAll('span');
    var idx = 0, timer = null, inView = true;
    function tick() {
      if (document.hidden || !inView) return;
      spans[idx].classList.remove('is-active');
      idx = (idx + 1) % spans.length;
      spans[idx].classList.add('is-active');
    }
    function on()  { if (!timer) timer = setInterval(tick, 3500); }
    function off() { if (timer)  { clearInterval(timer); timer = null; } }
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) off(); else if (inView) on();
    });
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          inView = e.isIntersecting;
          if (inView && !document.hidden) on(); else off();
        });
      }, { threshold: 0 }).observe(el);
    } else { on(); }
  }());
});
