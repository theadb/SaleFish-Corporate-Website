import Swiper, { Navigation, Autoplay } from "swiper";
import { CountUp } from "countup.js";

$(function () {
  let page = $("main").attr("class");
  let pathname = $(location).attr("pathname");
  if (page === "home") {
    const options = {
      startVal: 0,
    };
    const options_2 = {
      startVal: 0,
      decimalPlaces: 1,
    };
    let count_1 = new CountUp("count_1", $("#count_1").data("number"), options);
    let count_2 = new CountUp("count_2", $("#count_2").data("number"), options);
    let count_3 = new CountUp(
      "count_3",
      $("#count_3").data("number"),
      options_2
    );
    // One-shot IntersectionObserver replaces the previous resize+scroll
    // listener that called .offset() on every scroll event. The old handler
    // forced a layout read on every scroll tick and was a major Safari jank
    // source on the homepage. The observer fires once when the contact
    // section enters the viewport, then disconnects.
    var contactTrigger = document.querySelector(".contact .content .col");
    if (contactTrigger && "IntersectionObserver" in window) {
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
      }, { rootMargin: "0px 0px -10% 0px" });
      io.observe(contactTrigger);
    } else if (contactTrigger) {
      count_1.start(); count_2.start(); count_3.start();
    }
    let pillarsSwiper = new Swiper(".pillarsSwiper", {
      modules: [Navigation],
      slidesPerView: 1,
      spaceBetween: 50,
      loop: true,
      navigation: {
        nextEl: ".pillarsSwiper .right_arrow",
        prevEl: ".pillarsSwiper .left_arrow",
      },
      breakpoints: {
        769: {
          enabled: false,
          loop: false,
        },
      },
    });

    // ── Logo marquee — JS RAF replaced with pure CSS ────────────────────────
    // The previous RAF-driven implementation kept stalling on iOS Safari
    // (Safari throttles long-running off-screen RAF, and even with
    // IntersectionObserver pause/resume the resume timing was fragile).
    // It also depended on every logo image being loaded before measure()
    // could compute the correct halfWidth — a flaky chain of conditions.
    //
    // The new implementation is pure CSS. The .builders_track has a
    // CSS keyframe animation that translates the track by -50% over 28s,
    // looping forever. Because the markup duplicates the logo set, the
    // -50% shift exactly reaches the start of the second set — a
    // seamless infinite loop.
    //
    // Why CSS works here despite the old comment claiming it didn't:
    //   • will-change: transform on .builders_track keeps the GPU
    //     compositor layer alive permanently (was the original failure
    //     mode — Safari dropped the layer when off-screen)
    //   • backface-visibility: hidden reinforces the layer
    //   • The animation is purely transform-based, so it runs entirely
    //     on the compositor thread without ever touching layout
    //
    // Net result: zero JS, no RAF throttling concerns, no measurement
    // race conditions, no IO pause/resume edge cases. The marquee just
    // works.

    let numbersSwiper = new Swiper(".numbersSwiper", {
      modules: [Navigation],
      loop: true,
      navigation: {
        nextEl: ".numbersSwiperArrow .right_arrow",
        prevEl: ".numbersSwiperArrow .left_arrow",
      },
    });



  }

  // ── Hero text rotator (lightweight rebuild) ─────────────────────────────
  // Old version: setInterval(2000) + jQuery fadeOut(400) + .html() swap +
  // fadeIn(400). jQuery animations are setInterval-based (~16ms ticks for
  // 400ms each), and the .html() rewrite forced a re-parse on every cycle.
  //
  // New version (this code): all textArray words are pre-rendered into the
  // #app_for_home span as stacked grid items. The cycle is just a
  // classList swap — CSS opacity transitions (compositor-only) handle the
  // crossfade. Cycle slowed from 2 s to 3.5 s to halve the work. Paused
  // when the hero scrolls off-screen (IntersectionObserver) or the tab
  // is hidden (visibilitychange).
  (function () {
    var el = document.getElementById('app_for_home');
    if (!el) return;
    var words = (typeof textArray !== 'undefined' && Array.isArray(textArray)) ? textArray.slice() : [];
    if (!words.length) {
      var t = (el.textContent || '').trim();
      if (t) words = [t];
    }
    if (words.length < 2) return; // nothing to rotate
    if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    function esc(s) {
      return String(s).replace(/[&<>"']/g, function (c) {
        return { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' }[c];
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
