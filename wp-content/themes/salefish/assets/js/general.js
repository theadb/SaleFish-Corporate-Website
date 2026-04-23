import $ from "jquery";
import parsley from "./tools/parsley";
import flowtype from "./tools/flowtype";
import mask from "./tools/jquery.mask";

$(function () {
  if ( new URLSearchParams( window.location.search ).get( 'salefish_verified' ) === '1' ) {
    $(".thank_you_msg").fadeIn();
    $("body").css("overflow", "hidden");
    history.replaceState( null, '', window.location.pathname );
  }

  let page = $("main").attr("class");

  $.fn.isInViewport = function () {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();

    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
  };

  let pathname = $(location).attr("pathname");

  let scroll = new SmoothScroll('a[href*="#"]', {
    offset: function (anchor, toggle) {
      return 70;
    },
  });

  $("#phone").mask("000-000-0000");
  $("#sf_reg_phone").mask("000-000-0000");

  let activeMenu = pathname.startsWith("/de") ? ".floating_menu_de"
    : pathname.startsWith("/tr") ? ".floating_menu_tr"
    : ".floating_menu_en";

  $(".sf-menu-btn").on("click", function () {
    $(this).toggleClass("is-active");
    var expanded = $(this).hasClass("is-active");
    $(".sf-menu-btn").attr("aria-expanded", expanded ? "true" : "false");
    $(activeMenu).fadeToggle();
  });
  $(".sales_login").on("click", function () {
    $(".sales_login_menu").fadeToggle();
  });
  $(".languages").on("click", function () {
    $(".languages .down_arrow").toggleClass("active");
    $(".languages_option").fadeToggle();
  });
  $(document).on("click", function (e) {
    if ($(e.target).closest(".languages").length === 0) {
      $(".languages_option").fadeOut();
      $(".languages .down_arrow").removeClass("active");
    }
    if ($(e.target).closest(".sales_login").length === 0) {
      $(".sales_login_menu").fadeOut();
    }
    // ── Close hamburger menu when clicking outside ──────────────────────────
    if (
      $(e.target).closest(".floating_menu").length === 0 &&
      $(e.target).closest(".sf-menu-btn").length === 0
    ) {
      if ($(".sf-menu-btn").hasClass("is-active")) {
        $(".sf-menu-btn").removeClass("is-active").attr("aria-expanded", "false");
        $(activeMenu).fadeOut();
      }
    }
  });

  $(".privacy_policy_menu").on("click", function () {
    $(".privacy_policy").addClass("active");
    $(".sf-menu-btn").toggleClass("is-active");
    $(activeMenu).fadeToggle();
    $("body").css("overflow", "hidden");
  });

  $(".close_privacy").on("click", function () {
    $(".privacy_policy").removeClass("active");
    $("body").css("overflow", "auto");
  });

  $(".terms_menu").on("click", function () {
    $(".terms_popup").addClass("active");
    $(".sf-menu-btn").toggleClass("is-active");
    $(activeMenu).fadeToggle();
    $("body").css("overflow", "hidden");
  });

  $(".close_terms").on("click", function () {
    $(".terms_popup").removeClass("active");
    $("body").css("overflow", "auto");
  });

  $(".privacy_policy_menu_footer").on("click", function () {
    $(".privacy_policy").addClass("active");
    $("body").css("overflow", "hidden");
  });
  $(".terms_menu_footer").on("click", function () {
    $(".terms_popup").addClass("active");
    $("body").css("overflow", "hidden");
  });

  $(window).on("scroll", function () {
    var isMobile = $(window).width() <= 768;
    var menuTop  = isMobile ? "80px" : ($(window).scrollTop() > 1 ? "60px" : "70px");

    if ($(window).scrollTop() > 1) {
      $("header").addClass("active");
      $(".floating_menu").css("top", menuTop);
      $(".sales_login_menu").css("top", menuTop);
      $(".privacy_policy").css({
        top: "70px",
        height: "calc(100% - 70px)",
      });
      $(".terms_popup").css({
        top: "70px",
        height: "calc(100% - 70px)",
      });
    } else {
      $("header").removeClass("active");
      $(".floating_menu").css("top", menuTop);
      $(".sales_login_menu").css("top", menuTop);
      $(".privacy_policy").css({
        top: "100px",
        height: "calc(100% - 100px)",
      });
      $(".terms_popup").css({
        top: "100px",
        height: "calc(100% - 100px)",
      });
    }
  });

  $(".floating_menu .mobile").on("click", function () {
    $(".sf-menu-btn").toggleClass("is-active");
    var expanded = $(".sf-menu-btn").hasClass("is-active");
    $(".sf-menu-btn").attr("aria-expanded", expanded ? "true" : "false");
    $(activeMenu).fadeToggle();
  });

  switch (pathname) {
    case "/au/":
      $(".flag_active").html(`<span class="flag">🇦🇺</span>`);
      $(".languages_option").html(`
        <ul>
          <li><a href="/" aria-label="Canada &amp; USA (English)"><span class="flag">🇨🇦🇺🇸</span></a></li>
          <li><a href="/de" aria-label="Germany (Deutsch)"><span class="flag">🇩🇪</span></a></li>
          <li><a href="/tr" aria-label="Turkey (Türkçe)"><span class="flag">🇹🇷</span></a></li>
        </ul>
      `);
      break;
    case "/tr/":
      $(".flag_active").html(`<span class="flag">🇹🇷</span>`);
      $(".languages_option").html(`
        <ul>
          <li><a href="/" aria-label="Canada &amp; USA (English)"><span class="flag">🇨🇦🇺🇸</span></a></li>
          <li><a href="/au" aria-label="Australia (English)"><span class="flag">🇦🇺</span></a></li>
          <li><a href="/de" aria-label="Germany (Deutsch)"><span class="flag">🇩🇪</span></a></li>
        </ul>
      `);
      break;
    case "/de/":
      $(".flag_active").html(`<span class="flag">🇩🇪</span>`);
      $(".languages_option").html(`
        <ul>
          <li><a href="/" aria-label="Canada &amp; USA (English)"><span class="flag">🇨🇦🇺🇸</span></a></li>
          <li><a href="/au" aria-label="Australia (English)"><span class="flag">🇦🇺</span></a></li>
          <li><a href="/tr" aria-label="Turkey (Türkçe)"><span class="flag">🇹🇷</span></a></li>
        </ul>
      `);
      break;
    default:
      $(".flag_active").html(`<span class="flag">🇨🇦🇺🇸</span>`);
      $(".languages_option").html(`
        <ul>
          <li><a href="/au" aria-label="Australia (English)"><span class="flag">🇦🇺</span></a></li>
          <li><a href="/de" aria-label="Germany (Deutsch)"><span class="flag">🇩🇪</span></a></li>
          <li><a href="/tr" aria-label="Turkey (Türkçe)"><span class="flag">🇹🇷</span></a></li>
        </ul>
      `);
      break;
  }

  switch (pathname) {
    case "/our-story/":
      $(".our_story_nav a").addClass("active");
      break;
    case "/blog/":
    case "/blog":
      $(".blog_nav a").addClass("active");
      break;
    default:
      if (
        pathname.startsWith("/blog") ||
        document.body.classList.contains("single-post") ||
        document.body.classList.contains("category") ||
        document.body.classList.contains("archive")
      ) {
        $(".blog_nav a").addClass("active");
      }
      break;
    case "/contact-us/":
      $(".contact_us_nav a").addClass("active");
      break;
    case "/terms-of-use/":
      $(".terms_of_use_nav a").addClass("active");
      break;
    case "/privacy-policy/":
      $(".privacy_policy_nav a").addClass("active");
      break;
    case "/features/":
      $(".features_nav a").addClass("active");
      break;
    case "/partners/":
      $(".partners_nav a").addClass("active");
      break;
    case "/awards/":
      $(".awards_nav a").addClass("active");
      break;
  }

  // Features is a hash anchor on the homepage — highlight it on load if hash matches
  if (pathname === "/" && window.location.hash === "#features") {
    $(".features_nav a, .features_li a").addClass("active");
  }

  // Force header to solid state on short pages where there is nothing to scroll
  if (pathname === "/thank-you-for-registering/") {
    $("header").addClass("active");
  }

  // On click: immediately mark Features as active and clear other nav highlights
  $(".features_nav a, .features_li a").on("click", function () {
    $("header nav > ul > li > a, header nav > ul > li > span").removeClass("active");
    $(this).addClass("active");
  });

  // ── Hero background slideshow ──────────────────────────────────────────────
  // Cross-fades between slides every 5.5 s with a subtle Ken Burns zoom.
  // Runs only when the user has not requested reduced motion.
  (function () {
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    document.querySelectorAll('.hero__slideshow').forEach(function (slideshow) {
      var slides = slideshow.querySelectorAll('.hero__slide');
      if (slides.length < 2) return;
      // First slide already carries is-active in HTML for instant display.
      if (reducedMotion) return; // leave first slide permanently visible
      var idx = 0;
      setInterval(function () {
        slides[idx].classList.remove('is-active');
        idx = (idx + 1) % slides.length;
        slides[idx].classList.add('is-active');
      }, 5500);
    });
  }());

  // ── Platform showcase image crossfade ─────────────────────────────────────
  (function () {
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    document.querySelectorAll('.platform__img-stack').forEach(function (stack) {
      var slides = stack.querySelectorAll('.platform__img-slide');
      if (slides.length < 2 || reducedMotion) return;
      var idx = 0;
      setInterval(function () {
        slides[idx].classList.remove('is-active');
        idx = (idx + 1) % slides.length;
        slides[idx].classList.add('is-active');
      }, 5500);
    });
  }());

  $("#reg_form").parsley();
  $("#agent_form").parsley();
  $("#partner_form").parsley();
  $("#sf_reg_form").parsley();
  $("#sf_partner_form").parsley();

  // ── Helper: show the "check your email" dialog after form submit ─────────────
  function sfShowCheckEmail(email) {
    if (email) {
      $(".sf-check-email-msg__address").text(email);
    }
    $(".sf-check-email-msg").fadeIn();
    $("body").css("overflow", "hidden");
  }

  // REG FORM
  $("#reg_form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $(this).serialize() + "&action=salefish_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : "");
        }
      },
    });
  });

  // AGENT FORM
  $("#agent_form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $(this).serialize() + "&action=agents_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : "");
        }
      },
    });
  });

  // PARTNER FORM
  $("#partner_form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $(this).serialize() + "&action=partner_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : "");
        }
      },
    });
  });

  // CLOSE CHECK-EMAIL DIALOG
  $(".sf-check-email-close").on("click", function () {
    $(".sf-check-email-msg").fadeOut();
    $("body").css("overflow", "");
  });

  // CLOSE THANK YOU MESSAGE
  $(".close_thank_you_msg").on("click", function () {
    $(".thank_you_msg").fadeOut();
    $("body").css("overflow", "");
  });

  // ── REGISTRATION MODAL ─────────────────────────────────────────────────────
  // All [data-sf-modal="register"] links open the inline registration form.
  // The data-sf-section attribute on the clicked link identifies which CTA
  // triggered the modal so it can be tracked in the admin notification email.

  function sfRegModalOpen(section) {
    // Measure scrollbar width BEFORE hiding overflow so we can compensate.
    // When overflow:hidden removes the scrollbar the viewport widens by exactly
    // this amount — adding the same width as padding keeps the layout still.
    var scrollbarW = window.innerWidth - document.documentElement.clientWidth;
    $("#sf_reg_section").val(section || "");
    if (scrollbarW > 0) {
      $("body").css("padding-right", scrollbarW + "px");
      $("header").css("padding-right", scrollbarW + "px");
    }
    $("html, body").css("overflow", "hidden");
    $("#sf-reg-modal").fadeIn(200);
  }

  function sfRegModalClose() {
    $("html, body").css("overflow", "");
    $("body, header").css("padding-right", "");
    $("#sf-reg-modal").fadeOut(200, function () {
      var form = document.getElementById("sf_reg_form");
      if (form) form.reset();
      if (window.turnstile && typeof window.turnstile.reset === "function") {
        window.turnstile.reset();
      }
    });
  }

  $(document).on("click", '[data-sf-modal="register"]', function (e) {
    e.preventDefault();
    sfRegModalOpen($(this).data("sf-section") || "");
  });

  $(document).on(
    "click",
    "#sf-reg-modal .sf-reg-modal__backdrop, #sf-reg-modal .sf-reg-modal__close",
    sfRegModalClose
  );

  // REG MODAL FORM SUBMIT
  $("#sf_reg_form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $(this).serialize() + "&action=salefish_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          sfRegModalClose();
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : "");
        }
      },
    });
  });

  // ── PARTNER REGISTRATION MODAL ─────────────────────────────────────────────
  // Opened by [data-sf-modal="partner"] links. An optional data-sf-partner-type
  // attribute pre-selects the "What do you want to do?" dropdown so the user
  // lands on the relevant option for the card they clicked.

  function sfPartnerModalOpen(partnerType, section) {
    var scrollbarW = window.innerWidth - document.documentElement.clientWidth;
    if (scrollbarW > 0) {
      $("body").css("padding-right", scrollbarW + "px");
      $("header").css("padding-right", scrollbarW + "px");
    }
    // Pre-select the dropdown if a type was passed via data attribute
    if (partnerType) {
      $("#sf_partner_want_to_do").val(partnerType);
    }
    $("html, body").css("overflow", "hidden");
    $("#sf-partner-modal").fadeIn(200);
  }

  function sfPartnerModalClose() {
    $("html, body").css("overflow", "");
    $("body, header").css("padding-right", "");
    $("#sf-partner-modal").fadeOut(200, function () {
      var form = document.getElementById("sf_partner_form");
      if (form) form.reset();
      if (window.turnstile && typeof window.turnstile.reset === "function") {
        window.turnstile.reset();
      }
    });
  }

  $(document).on("click", '[data-sf-modal="partner"]', function (e) {
    e.preventDefault();
    sfPartnerModalOpen(
      $(this).data("sf-partner-type") || "",
      $(this).data("sf-section") || ""
    );
  });

  $(document).on(
    "click",
    "#sf-partner-modal .sf-partner-modal__backdrop, #sf-partner-modal .sf-partner-modal__close",
    sfPartnerModalClose
  );

  // PARTNER MODAL FORM SUBMIT
  $("#sf_partner_form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $(this).serialize() + "&action=partner_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          sfPartnerModalClose();
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : "");
        }
      },
    });
  });

  $(document).on("keydown", function (e) {
    if (e.key === "Escape") {
      if ($("#sf-reg-modal").is(":visible")) sfRegModalClose();
      if ($("#sf-partner-modal").is(":visible")) sfPartnerModalClose();
    }
  });

});

// ── Tidio chat button: white outline ring ─────────────────────────────────────
// The Tidio button renders inside a cross-origin iframe so we can't reach the
// circular button element directly with CSS. Instead we apply border-radius +
// box-shadow to the iframe element itself — which gives the same visual result
// when the iframe is small (button mode). When the chat panel opens, Tidio
// resizes the iframe to a large panel; we detect that and strip the ring so the
// open panel isn't clipped to an oval.
(function () {
  function applyTidioRing(iframe) {
    function update() {
      var isButton = iframe.offsetWidth <= 160 && iframe.offsetHeight <= 160;
      iframe.style.borderRadius = isButton ? '50%' : '';
      iframe.style.boxShadow   = isButton ? '0 0 0 3px rgba(255,255,255,0.85), 0 2px 12px rgba(0,0,0,0.25)' : '';
    }

    update();

    if (window.ResizeObserver) {
      new ResizeObserver(update).observe(iframe);
    }
  }

  // Tidio loads asynchronously — poll until its iframe appears in the DOM
  var poll = setInterval(function () {
    var iframe = document.getElementById('tidio-chat-iframe');
    if (iframe) {
      clearInterval(poll);
      applyTidioRing(iframe);
    }
  }, 500);
})();
