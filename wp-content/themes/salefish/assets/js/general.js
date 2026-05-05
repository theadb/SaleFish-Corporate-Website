import $ from "jquery";
// SmoothScroll removed — replaced by native `scroll-behavior: smooth` on html
// plus `scroll-margin-top: 80px` on [id] elements (see _general.scss).
// This eliminates SmoothScroll's document-level click listener which ran
// synchronously on every single click event on the page.
import parsley from "./tools/parsley";
import flowtype from "./tools/flowtype";
import mask from "./tools/jquery.mask";

$(function () {
  // Menu open/close + scroll header state are managed by the SaleFish Menu
  // Controller (vanilla JS, inline in header.php). No jQuery helpers needed
  // here. Privacy/Terms popups and modal-related close calls below use
  // the controller indirectly by toggling the same `is-active` / `is-open`
  // classes that the controller reads.

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

  $("#phone").mask("000-000-0000");
  $("#sf_reg_phone").mask("000-000-0000");

  let activeMenu = pathname.startsWith("/de") ? ".floating_menu_de"
    : pathname.startsWith("/tr") ? ".floating_menu_tr"
    : ".floating_menu_en";

  // Menu/dropdown click handling lives in an inline <head> script in
  // header.php so it runs before app.js parses (~200-500 ms earlier on
  // mobile) and clicks register instantly. The jQuery handlers that used
  // to live here would have double-toggled the same classes.

  $(".privacy_policy_menu").on("click", function () {
    $(".privacy_policy").addClass("active");
    // Close any open menu via the controller's class hooks
    document.querySelectorAll('.sf-menu-btn.is-active').forEach(function (b) {
      b.classList.remove('is-active');
      b.setAttribute('aria-expanded', 'false');
    });
    document.querySelectorAll('.floating_menu.is-open').forEach(function (m) {
      m.classList.remove('is-open');
      m.setAttribute('inert', '');
    });
    $("body").css("overflow", "hidden");
  });

  $(".close_privacy").on("click", function () {
    $(".privacy_policy").removeClass("active");
    $("body").css("overflow", "auto");
  });

  $(".terms_menu").on("click", function () {
    $(".terms_popup").addClass("active");
    document.querySelectorAll('.sf-menu-btn.is-active').forEach(function (b) {
      b.classList.remove('is-active');
      b.setAttribute('aria-expanded', 'false');
    });
    document.querySelectorAll('.floating_menu.is-open').forEach(function (m) {
      m.classList.remove('is-open');
      m.setAttribute('inert', '');
    });
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

  // Header scroll state lives in the inline controller in header.php, which
  // toggles `html.is-scrolled` once per animation frame via rAF. CSS reads
  // that single class and adjusts header height + floating-menu / sales-
  // login top offsets — no per-frame JS layout writes.
  // The inline controller also closes any open menu when a link inside
  // .floating_menu is clicked (so navigation works as expected on mobile).
  $(document).on('click', '.floating_menu a, .floating_menu .mobile', function () {
    document.querySelectorAll('.sf-menu-btn.is-active').forEach(function (b) {
      b.classList.remove('is-active');
      b.setAttribute('aria-expanded', 'false');
    });
    document.querySelectorAll('.floating_menu.is-open').forEach(function (m) {
      m.classList.remove('is-open');
      m.setAttribute('inert', '');
    });
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

  // ── Hero + platform slideshow cross-fade ──────────────────────────────────
  // Lightweight shared crossfade. Single setInterval per group, only runs
  // when (a) the tab is visible AND (b) the slideshow root is on-screen.
  // The class toggle is the only work — CSS handles the opacity transition
  // on the compositor thread. Reduced-motion users get the static first
  // slide (no JS, no transitions).
  (function () {
    if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    function bind(rootSel, slideSel, ms) {
      document.querySelectorAll(rootSel).forEach(function (root) {
        var slides = root.querySelectorAll(slideSel);
        if (slides.length < 2) return;
        var idx = 0, timer = null, inView = true;
        function tick() {
          if (document.hidden || !inView) return;
          slides[idx].classList.remove('is-active');
          idx = (idx + 1) % slides.length;
          slides[idx].classList.add('is-active');
        }
        function on()  { if (!timer) timer = setInterval(tick, ms); }
        function off() { if (timer) { clearInterval(timer); timer = null; } }
        document.addEventListener('visibilitychange', function () {
          if (document.hidden) off(); else if (inView) on();
        });
        if ('IntersectionObserver' in window) {
          new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
              inView = e.isIntersecting;
              if (inView && !document.hidden) on(); else off();
            });
          }, { threshold: 0 }).observe(root);
        } else { on(); }
      });
    }
    bind('.hero__slideshow',     '.hero__slide',           5500);
    bind('.platform__img-stack', '.platform__img-slide',   7000);
  }());

  // Inline-form Parsley init (these forms ARE in the DOM at load time on
  // contact / agent / partner pages).
  $("#reg_form").parsley();
  $("#agent_form").parsley();
  $("#partner_form").parsley();
  // sf_reg_form and sf_partner_form live inside the lazy-injected modal
  // template (see footer.php #sf-modals-template). Their Parsley init
  // happens INSIDE window.sfEnsureModals() — which runs the first time
  // a [data-sf-modal] button is hovered / touched / clicked.

  // ── Helper: show the "check your email" dialog after form submit ─────────────
  function sfShowCheckEmail(email) {
    if (email) {
      $(".sf-check-email-msg__address").text(email);
    }
    $(".sf-check-email-msg").fadeIn();
    $("body").css("overflow", "hidden");
  }

  // ── Focus trap ──────────────────────────────────────────────────────────────
  // Keeps Tab / Shift-Tab cycling inside an open modal. Returns a cleanup fn.
  function sfFocusTrap(modalEl) {
    var SEL = 'a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])';
    function getFocusable() {
      return Array.from(modalEl.querySelectorAll(SEL)).filter(function (el) {
        return el.offsetParent !== null;
      });
    }
    function handler(e) {
      if (e.key !== 'Tab') return;
      var els = getFocusable();
      if (!els.length) return;
      var first = els[0];
      var last  = els[els.length - 1];
      if (e.shiftKey) {
        if (document.activeElement === first) { e.preventDefault(); last.focus(); }
      } else {
        if (document.activeElement === last)  { e.preventDefault(); first.focus(); }
      }
    }
    modalEl.addEventListener('keydown', handler);
    var first = getFocusable()[0];
    if (first) setTimeout(function () { first.focus(); }, 60);
    return function () { modalEl.removeEventListener('keydown', handler); };
  }
  var _sfRegTrap     = null;
  var _sfPartnerTrap = null;

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

  // Render the Turnstile widget inside a freshly-opened modal.
  // Turnstile runs in explicit mode — sfTurnstileReady() skips modal widgets,
  // so we render them here once the modal is visible. Polls if the API
  // isn't ready yet (script still loading), gives up after 8 s.
  function sfRenderTurnstileIn($modal) {
    var node = $modal.find('.cf-turnstile').get(0);
    if (!node) return;
    var attempts = 0;
    function tryRender() {
      attempts++;
      if (window.turnstile && typeof window.turnstile.render === 'function') {
        // Already rendered? skip (the widget div has child iframes).
        if (node.querySelector('iframe')) return;
        try {
          window.turnstile.render(node, {
            sitekey: node.getAttribute('data-sitekey'),
            theme:   node.getAttribute('data-theme') || 'auto',
          });
        } catch (e) { /* turnstile may double-render — safe to ignore */ }
        return;
      }
      // API not loaded yet — retry up to ~8 s, then bail.
      if (attempts < 80) setTimeout(tryRender, 100);
    }
    tryRender();
  }

  function sfRegModalOpen(section) {
    // Ensure the modal HTML is injected into the DOM. The footer's eager
    // listeners normally do this on first hover — but if this is called
    // programmatically (e.g. via direct JS API) the modal might not yet
    // exist. Calling sfEnsureModals() is idempotent and synchronous.
    if (window.sfEnsureModals) window.sfEnsureModals();
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
    $("#sf-reg-modal").fadeIn(200, function () {
      sfRenderTurnstileIn($("#sf-reg-modal"));
      _sfRegTrap = sfFocusTrap(document.getElementById("sf-reg-modal"));
    });
  }

  function sfRegModalClose() {
    if (_sfRegTrap) { _sfRegTrap(); _sfRegTrap = null; }
    $("html, body").css("overflow", "");
    $("body, header").css("padding-right", "");
    $("#sf-reg-modal").fadeOut(200, function () {
      var form = document.getElementById("sf_reg_form");
      if (form) form.reset();
      $("#sf-reg-form-error").remove();
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

  // REG MODAL FORM SUBMIT — delegated through document so it still works
  // when the form is lazy-injected into the DOM by sfEnsureModals().
  $(document).on("submit", "#sf_reg_form", function (e) {
    e.preventDefault();
    var $form = $(this);
    var $btn  = $form.find(".submit");
    var origVal = $btn.val();
    $btn.val("Submitting…").prop("disabled", true);
    $("#sf-reg-form-error").remove();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $form.serialize() + "&action=salefish_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          sfRegModalClose();
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : "");
        } else {
          var msg = (res.data && res.data.message) ? res.data.message : "Something went wrong — please try again.";
          $form.find(".row:last-child").before('<p id="sf-reg-form-error" class="sf-form-error" role="alert">' + msg + "</p>");
          $btn.val(origVal).prop("disabled", false);
        }
      },
      error: function () {
        $form.find(".row:last-child").before('<p id="sf-reg-form-error" class="sf-form-error" role="alert">Connection error — please try again.</p>');
        $btn.val(origVal).prop("disabled", false);
      },
    });
  });

  // ── PARTNER REGISTRATION MODAL ─────────────────────────────────────────────
  // Opened by [data-sf-modal="partner"] links. An optional data-sf-partner-type
  // attribute pre-selects the "What do you want to do?" dropdown so the user
  // lands on the relevant option for the card they clicked.

  function sfPartnerModalOpen(partnerType, section) {
    if (window.sfEnsureModals) window.sfEnsureModals();
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
    $("#sf-partner-modal").fadeIn(200, function () {
      sfRenderTurnstileIn($("#sf-partner-modal"));
      _sfPartnerTrap = sfFocusTrap(document.getElementById("sf-partner-modal"));
    });
  }

  function sfPartnerModalClose() {
    if (_sfPartnerTrap) { _sfPartnerTrap(); _sfPartnerTrap = null; }
    $("html, body").css("overflow", "");
    $("body, header").css("padding-right", "");
    $("#sf-partner-modal").fadeOut(200, function () {
      var form = document.getElementById("sf_partner_form");
      if (form) form.reset();
      $("#sf-partner-form-error").remove();
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

  // PARTNER MODAL FORM SUBMIT — delegated through document so it still
  // works when the form is lazy-injected into the DOM by sfEnsureModals().
  $(document).on("submit", "#sf_partner_form", function (e) {
    e.preventDefault();
    var $form = $(this);
    var $btn  = $form.find(".submit");
    var origVal = $btn.val();
    $btn.val("Submitting…").prop("disabled", true);
    $("#sf-partner-form-error").remove();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $form.serialize() + "&action=partner_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          sfPartnerModalClose();
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : "");
        } else {
          var msg = (res.data && res.data.message) ? res.data.message : "Something went wrong — please try again.";
          $form.find(".row:last-child").before('<p id="sf-partner-form-error" class="sf-form-error" role="alert">' + msg + "</p>");
          $btn.val(origVal).prop("disabled", false);
        }
      },
      error: function () {
        $form.find(".row:last-child").before('<p id="sf-partner-form-error" class="sf-form-error" role="alert">Connection error — please try again.</p>');
        $btn.val(origVal).prop("disabled", false);
      },
    });
  });

  // Escape key handling for modals only — the menu controller in
  // header.php already handles Esc for hamburger / languages / sales-login.
  $(document).on("keydown", function (e) {
    if (e.key !== "Escape") return;
    if ($("#sf-reg-modal").is(":visible"))     sfRegModalClose();
    if ($("#sf-partner-modal").is(":visible")) sfPartnerModalClose();
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

// ── Scroll-to-top button ──────────────────────────────────────────────────────
// rAF-throttled visibility toggle. Class-based show/hide (instead of `hidden`
// attribute) allows a CSS opacity+transform transition — Safari renders this
// on the compositor without a paint, so it never causes scroll jank.
(function () {
  var btn = document.getElementById('sf-scroll-top');
  if (!btn) return;
  btn.removeAttribute('hidden');
  var isShown = false;
  var ticking = false;
  function update() {
    ticking = false;
    var shouldShow = window.scrollY > 400;
    if (shouldShow !== isShown) {
      isShown = shouldShow;
      btn.classList.toggle('is-visible', shouldShow);
    }
  }
  update();
  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; requestAnimationFrame(update); }
  }, { passive: true });
  btn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}());
