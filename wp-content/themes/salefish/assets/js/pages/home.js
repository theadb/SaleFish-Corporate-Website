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

    // ── Logo marquee (requestAnimationFrame) ────────────────────────────────
    // CSS animation is unreliable on webkit: the compositor drops the animated
    // layer when combined with overflow:hidden on the parent, causing the logos
    // to vanish. RAF-driven translateX is compositor-safe on all browsers.
    //
    // Root-cause of previous "restarts halfway" bug: the cloned (second) set of
    // logos was loading="lazy", so scrollWidth on first measure only counted the
    // first set. halfWidth was therefore half of what it should be, triggering
    // the seamless-reset after just one half-cycle. Both sets are now eager.
    // Additionally: init() now waits until ALL img.naturalWidth > 0 before
    // starting, so halfWidth is guaranteed correct from the first tick.
    // The reset uses x += halfWidth (not x = 0) to handle any single-frame
    // overshoot cleanly.
    (function () {
      const track = document.querySelector(".builders_track");
      if (!track) return;

      // ~0.7 px/frame @ 60 fps ≈ same visual speed as the old 28s CSS animation
      const SPEED = 0.7;
      let x = 0;
      let halfWidth = 0;
      let rafId = null;
      let everStarted = false;

      function measure() {
        // halfWidth only grows — prevents a mid-reflow shrink from corrupting
        // the loop boundary after the marquee is already running.
        var w = track.scrollWidth / 2;
        if (w > halfWidth) halfWidth = w;
      }

      function tick() {
        x -= SPEED;
        if (halfWidth > 0 && x <= -halfWidth) {
          x += halfWidth; // add halfWidth instead of resetting to 0 — handles
                          // any single-frame overshoot without a visible jump
        }
        track.style.transform = "translateX(" + x + "px)";
        rafId = requestAnimationFrame(tick);
      }

      function start() {
        if (!rafId) { everStarted = true; tick(); }
      }

      function stop() {
        if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
      }

      // The marquee is paused when (a) the tab is hidden, or (b) the
      // marquee is scrolled out of view. Safari aggressively throttles
      // long-running RAF loops when their target is off-screen — that
      // throttle manifests as the marquee "stalling" when you scroll
      // back up to it. Pausing explicitly via IntersectionObserver and
      // resuming on intersection sidesteps the throttle entirely; on
      // resume the RAF clock is fresh and the animation runs smoothly.
      var inView = true;
      document.addEventListener("visibilitychange", function () {
        if (document.hidden) { stop(); } else if (everStarted && inView) { start(); }
      });
      if ("IntersectionObserver" in window) {
        new IntersectionObserver(function (entries) {
          entries.forEach(function (e) {
            inView = e.isIntersecting;
            if (inView && !document.hidden) {
              if (everStarted) start();
            } else {
              stop();
            }
          });
        }, { threshold: 0 }).observe(track);
      }

      // Re-measure if the track ever grows (e.g. a font loads late and affects
      // logo container widths). halfWidth is only allowed to increase.
      if (typeof ResizeObserver !== "undefined") {
        new ResizeObserver(function () { measure(); }).observe(track);
      }

      // Wait until every image in the track has its natural dimensions,
      // OR until 2 seconds have passed (whichever comes first). The hard
      // timeout means the marquee always starts — even if a single logo
      // is slow or fails to load, the rest still scroll. Without this
      // fallback, lazy-loaded or 404'd logos would freeze the marquee
      // indefinitely.
      var initStarted = Date.now();
      function init() {
        var imgs = Array.from(track.querySelectorAll("img"));
        var allReady = imgs.length > 0 && imgs.every(function (img) {
          return img.complete && img.naturalWidth > 0;
        });
        var timedOut = Date.now() - initStarted > 2000;
        if (allReady || timedOut) {
          measure();
          if (halfWidth > 0) {
            start();
          } else if (!timedOut) {
            setTimeout(init, 100);
          }
        } else {
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
    let rotatorId = null;
    function startRotator() {
      if (rotatorId) return;
      rotatorId = setInterval(() => {
        if (document.hidden) return; // skip work while tab is backgrounded
        $("#app_for_home").fadeOut(400, function () {
          $(this).html(textArray[i]).fadeIn(400);
          i == 3 ? (i = 0) : i++;
        });
      }, 2000);
    }
    function stopRotator() {
      if (rotatorId) { clearInterval(rotatorId); rotatorId = null; }
    }
    document.addEventListener("visibilitychange", function () {
      if (document.hidden) stopRotator(); else startRotator();
    });
    startRotator();
  }
});
