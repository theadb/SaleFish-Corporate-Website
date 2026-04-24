import Swiper, { Navigation, Autoplay } from "swiper";
import { CountUp } from "countup.js";

$(function () {
  let page = $("main").attr("class");
  let pathname = $(location).attr("pathname");
  if (page === "home") {
    $.fn.isInViewport = function () {
      var elementTop = $(this).offset().top;
      var elementBottom = elementTop + $(this).outerHeight();

      var viewportTop = $(window).scrollTop();
      var viewportBottom = viewportTop + $(window).height();

      return elementBottom > viewportTop && elementTop < viewportBottom;
    };

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
    $(window).on("resize scroll", function () {
      if ($(".contact .content .col").isInViewport()) {
        setTimeout(() => {
          count_1.start();
          count_2.start();
          count_3.start();
        }, 500);
      } else {
        // do something else
      }
    });
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

    // ── Logo marquee (requestAnimationFrame) ────────────────────────────────
    // CSS animation is unreliable on webkit: the compositor drops the animated
    // layer when combined with overflow:hidden on the parent, causing the logos
    // to vanish. RAF-driven translateX is compositor-safe on all browsers.
    (function () {
      const track = document.querySelector(".builders_track");
      if (!track) return;

      // ~0.7 px/frame @ 60 fps ≈ same visual speed as the old 28s CSS animation
      const SPEED = 0.7;
      let x = 0;
      let halfWidth = 0;
      let rafId = null;

      function measure() {
        // scrollWidth includes all cloned logos; half = one full set
        halfWidth = track.scrollWidth / 2;
      }

      function tick() {
        x -= SPEED;
        if (halfWidth > 0 && Math.abs(x) >= halfWidth) {
          x = 0; // seamless jump — visually identical because logos repeat
        }
        track.style.transform = "translateX(" + x + "px)";
        rafId = requestAnimationFrame(tick);
      }

      function start() {
        if (!rafId) tick();
      }

      function stop() {
        if (rafId) {
          cancelAnimationFrame(rafId);
          rafId = null;
        }
      }

      // Pause when tab is hidden; resume when visible — prevents position
      // drift accumulating while the RAF was paused by the browser
      document.addEventListener("visibilitychange", function () {
        document.hidden ? stop() : start();
      });

      // Re-measure whenever the track's width changes (lazy images loading in).
      // This keeps halfWidth accurate so the seamless-reset fires at the right
      // point even if some logos were still loading when init() first ran.
      if (typeof ResizeObserver !== "undefined") {
        new ResizeObserver(function () {
          measure();
        }).observe(track);
      }

      // Measure after all images have loaded so scrollWidth is accurate,
      // then kick off the loop
      function init() {
        measure();
        if (halfWidth > 0) {
          start();
        } else {
          // Images still loading — retry until they settle
          setTimeout(init, 100);
        }
      }

      if (document.readyState === "complete") {
        init();
      } else {
        window.addEventListener("load", init);
      }
    })();
    // ── End logo marquee ────────────────────────────────────────────────────

    let numbersSwiper = new Swiper(".numbersSwiper", {
      modules: [Navigation],
      loop: true,
      navigation: {
        nextEl: ".numbersSwiperArrow .right_arrow",
        prevEl: ".numbersSwiperArrow .left_arrow",
      },
    });



  }

  if (pathname === "/" || pathname === "/au/") {
    let i = 0;
    setInterval(() => {
      $("#app_for_home").fadeOut(400, function () {
        $(this).html(textArray[i]).fadeIn(400);
        i == 3 ? (i = 0) : i++;
      });
    }, 2000);
  }
});
