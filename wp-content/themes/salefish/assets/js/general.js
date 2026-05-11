// SmoothScroll removed — replaced by native `scroll-behavior: smooth` on html
// Parsley / jquery.mask / flowtype removed — replaced by vanilla JS equivalents

// ── Scroll-lock helpers ───────────────────────────────────────────────────────
// Strategy depends on the platform:
//
//   iOS Safari — uses overlay scrollbars (no gutter) but has a bug where
//   `overflow:hidden` on <body> doesn't prevent background scrolling. Fix:
//   `position:fixed; top:-Ypx` freezes the body at the saved scroll position.
//   On close we restore scrollY. No scrollbar gutter to worry about on iOS.
//
//   All other browsers — `html { scrollbar-gutter:stable }` permanently
//   reserves gutter space, so the visible layout never shifts when a scrollbar
//   appears or disappears. We do NOT use `position:fixed` here because that
//   stretches body to the full viewport width (past the gutter), causing a
//   15 px shift in the opposite direction. Instead we use `overflow:hidden` on
//   body only (html stays overflow:auto so the gutter stays active).
//   No padding-right compensation is needed — the gutter handles it.
//
// All overlays (modals, privacy, terms, check-email, thank-you) call these
// helpers so the page never jumps on open/close regardless of which overlay
// opened.
let _sfScrollLockDepth = 0;   // nested open count (privacy inside modal, etc.)
let _sfScrollLockY     = 0;   // scroll position saved at first lock
const _sfScrollIsIOS   = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

function sfScrollLock() {
  if (_sfScrollLockDepth === 0) {
    _sfScrollLockY = window.scrollY || 0;
    if (_sfScrollIsIOS) {
      // iOS: position:fixed trick to freeze background scroll.
      // Overlay scrollbars mean no gutter shift to compensate for.
      document.body.style.top      = '-' + _sfScrollLockY + 'px';
      document.body.style.position = 'fixed';
      document.body.style.width    = '100%';
    } else {
      // Desktop / non-iOS: overflow:hidden on body only.
      // Keeps html overflow:auto so scrollbar-gutter:stable stays active
      // and the 15 px gutter space is permanently reserved — zero layout shift.
      document.body.style.overflow = 'hidden';
    }
  }
  _sfScrollLockDepth++;
}

function sfScrollUnlock() {
  _sfScrollLockDepth = Math.max(0, _sfScrollLockDepth - 1);
  if (_sfScrollLockDepth > 0) return; // another overlay is still open
  const y = _sfScrollLockY;
  if (_sfScrollIsIOS) {
    document.body.style.position = '';
    document.body.style.top      = '';
    document.body.style.width    = '';
    window.scrollTo({ top: y, behavior: 'instant' });
  } else {
    document.body.style.overflow = '';
  }
  _sfScrollLockY = 0;
}

// ── Conversion tracking ───────────────────────────────────────────────────────
// Central helper called on every successful form submission.
// Pushes a GA4-standard `generate_lead` event to the GTM dataLayer and fires
// the LinkedIn conversion pixel (when a conversion_id is configured).
//
// GA4 setup: in GTM, create a Custom Event trigger matching `generate_lead`
// and attach it to a GA4 Event tag. The `lead_type` and `form_location`
// parameters flow through as GA4 event parameters automatically.
//
// LinkedIn setup: obtain a numeric Conversion ID from LinkedIn Campaign Manager
// → Conversions → Create Conversion, then paste it into LI_CONVERSION_ID below.
const SF_LI_CONVERSION_ID = 512664862; // LinkedIn Conversion ID — Demo Request

function sfTrackConversion(leadType, formLocation) {
  // GA4 via GTM dataLayer — works even if pushed before GTM loads;
  // GTM replays queued events on init. At form-submit time GTM is
  // always loaded (fires 4s after window.load), so this is belt-and-braces.
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({ event: null }); // flush previous event field
  window.dataLayer.push({
    event:         'generate_lead',
    lead_type:     leadType,
    form_location: formLocation,
  });

  // Direct GA4 via gtag — belt-and-braces in case GTM is not configured
  // with a generate_lead tag. gtag() is defined in header.php and the
  // gtag.js library loads on window.load, well before any form submission.
  if (typeof window.gtag === 'function') {
    window.gtag('event', 'generate_lead', {
      lead_type:     leadType,
      form_location: formLocation,
    });
  }

  // LinkedIn conversion — fires if the pixel script has been loaded
  // (it loads when any [data-sf-modal] is clicked, which is always before
  // the form is submitted). Silently skipped until SF_LI_CONVERSION_ID is set.
  if (SF_LI_CONVERSION_ID && window.lintrk) {
    window.lintrk('track', { conversion_id: SF_LI_CONVERSION_ID });
  }

  // Microsoft Clarity — tag the session so recordings are filterable by lead
  // type (demo_request / partner_inquiry / agent_inquiry) and form location
  // (modal / inline_form). The generate_lead API event also acts as the final
  // step in a conversion funnel (modal_open → generate_lead).
  if (window.clarity) {
    window.clarity('set', 'lead_type', leadType);
    window.clarity('set', 'form_location', formLocation);
    window.clarity('event', 'generate_lead');
  }
}

// ── Phone mask: format as xxx-xxx-xxxx ───────────────────────────────────────
function applyPhoneMask(input) {
  if (!input) return;
  input.addEventListener('input', function () {
    const digits = this.value.replace(/\D/g, '').slice(0, 10);
    if (digits.length >= 7) {
      this.value = digits.slice(0, 3) + '-' + digits.slice(3, 6) + '-' + digits.slice(6);
    } else if (digits.length >= 4) {
      this.value = digits.slice(0, 3) + '-' + digits.slice(3);
    } else {
      this.value = digits;
    }
  });
}
window.applyPhoneMask = applyPhoneMask;

// ── Fade helpers (replace jQuery fadeIn / fadeOut) ────────────────────────────
function sfFadeIn(el, duration, callback) {
  if (!el) return;
  el.style.opacity = '0';
  el.style.display = 'block';
  el.style.transition = 'opacity ' + (duration || 400) + 'ms ease';
  requestAnimationFrame(function () {
    requestAnimationFrame(function () {
      el.style.opacity = '1';
      if (callback) setTimeout(callback, duration || 400);
    });
  });
}

function sfFadeOut(el, duration, callback) {
  if (!el) return;
  el.style.transition = 'opacity ' + (duration || 400) + 'ms ease';
  el.style.opacity = '0';
  setTimeout(function () {
    el.style.display = 'none';
    el.style.opacity = '';
    el.style.transition = '';
    if (callback) callback();
  }, duration || 400);
}

// ── Serialize form to URL-encoded string ─────────────────────────────────────
function serializeForm(form) {
  return new URLSearchParams(new FormData(form)).toString();
}

document.addEventListener('DOMContentLoaded', function () {
  // ── One-shot scroll reveal ─────────────────────────────────────────────────
  // Templates already carry data-aos attributes. This tiny controller replaces
  // the old AOS dependency with a Safari-friendly IntersectionObserver path:
  // below-fold elements are staged only after JS runs, then revealed once and
  // unobserved. Scrolling back up never replays the animation.
  (function () {
    const items = Array.prototype.slice.call(document.querySelectorAll('[data-aos]')).filter(function (el) {
      return !el.closest('header, .floating_menu, template, .sf-reg-modal, .sf-partner-modal, .thank_you_msg, .sf-check-email-msg');
    });
    if (!items.length) return;

    const reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduced || !('IntersectionObserver' in window)) {
      items.forEach(function (el) { el.classList.add('sf-reveal-done'); });
      return;
    }

    const vh = window.innerHeight || document.documentElement.clientHeight || 800;
    const pending = [];
    items.forEach(function (el) {
      const rect = el.getBoundingClientRect();
      if (rect.top < vh * 0.82) {
        el.classList.add('sf-reveal-done');
        return;
      }
      const delay = parseInt(el.getAttribute('data-aos-delay') || '0', 10);
      if (delay > 0) el.style.transitionDelay = Math.min(delay, 550) + 'ms';
      el.classList.add('sf-reveal-pending');
      pending.push(el);
    });
    if (!pending.length) return;

    function cleanup(el) {
      el.classList.remove('sf-reveal-pending', 'sf-reveal-in');
      el.classList.add('sf-reveal-done');
      el.style.transitionDelay = '';
    }
    function reveal(el) {
      if (el.dataset.sfRevealed === '1') return;
      el.dataset.sfRevealed = '1';
      requestAnimationFrame(function () {
        el.classList.add('sf-reveal-in');
        let done = false;
        function finish() {
          if (done) return;
          done = true;
          cleanup(el);
        }
        el.addEventListener('transitionend', finish, { once: true });
        setTimeout(finish, 1100);
      });
    }

    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        reveal(entry.target);
        io.unobserve(entry.target);
      });
    }, {
      root: null,
      // 300px positive bottom margin so elements are revealed before they reach
      // the viewport edge — prevents iOS Safari fast-fling leaving them hidden.
      rootMargin: '0px 0px 300px 0px',
      threshold: 0,
    });
    pending.forEach(function (el) { io.observe(el); });

    // Scroll-stop sweep: iOS Safari batches IO callbacks aggressively during
    // fast flings and may never fire for elements that passed through quickly.
    // After scrolling pauses, force-reveal anything already in (or near) view.
    let _sfRevealTimer;
    window.addEventListener('scroll', function () {
      clearTimeout(_sfRevealTimer);
      _sfRevealTimer = setTimeout(function () {
        const winH = window.innerHeight || document.documentElement.clientHeight;
        pending.forEach(function (el) {
          if (el.dataset.sfRevealed === '1') return;
          const rect = el.getBoundingClientRect();
          if (rect.top < winH + 100) {
            reveal(el);
            io.unobserve(el);
          }
        });
      }, 120);
    }, { passive: true });
  }());

  // ── Verified email redirect banner ──────────────────────────────────────────
  if (new URLSearchParams(window.location.search).get('salefish_verified') === '1') {
    const thankYouMsg = document.querySelector('.thank_you_msg');
    if (thankYouMsg) sfFadeIn(thankYouMsg);
    sfScrollLock();
    history.replaceState(null, '', window.location.pathname);
  }

  // ── Phone masks ──────────────────────────────────────────────────────────────
  applyPhoneMask(document.getElementById('phone'));
  applyPhoneMask(document.getElementById('sf_reg_phone'));

  // ── Privacy policy popup ─────────────────────────────────────────────────────
  document.querySelectorAll('.privacy_policy_menu, .privacy_policy_menu_footer').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const pp = document.querySelector('.privacy_policy');
      if (pp) pp.classList.add('active');
      if (window.sfMenuClose) window.sfMenuClose();
      sfScrollLock();
    });
  });

  document.querySelectorAll('.close_privacy').forEach(function (btn) {
    function doClosePrivacy() {
      const pp = document.querySelector('.privacy_policy');
      if (pp) pp.classList.remove('active');
      sfScrollUnlock();
    }
    btn.addEventListener('pointerdown', function (e) {
      if (e.pointerType === 'mouse') return;
      e.preventDefault();
      doClosePrivacy();
    });
    btn.addEventListener('click', doClosePrivacy);
  });

  // ── Terms popup ───────────────────────────────────────────────────────────────
  document.querySelectorAll('.terms_menu, .terms_menu_footer').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const tp = document.querySelector('.terms_popup');
      if (tp) tp.classList.add('active');
      if (window.sfMenuClose) window.sfMenuClose();
      sfScrollLock();
    });
  });

  document.querySelectorAll('.close_terms').forEach(function (btn) {
    function doCloseTerms() {
      const tp = document.querySelector('.terms_popup');
      if (tp) tp.classList.remove('active');
      sfScrollUnlock();
    }
    btn.addEventListener('pointerdown', function (e) {
      if (e.pointerType === 'mouse') return;
      e.preventDefault();
      doCloseTerms();
    });
    btn.addEventListener('click', doCloseTerms);
  });

  // ── Close floating menu when a link inside it is clicked ─────────────────────
  document.addEventListener('click', function (e) {
    if (e.target.closest('.floating_menu a, .floating_menu .mobile')) {
      if (window.sfMenuClose) window.sfMenuClose();
    }
  });

  const pathname = window.location.pathname;

  // ── Features nav click highlight ──────────────────────────────────────────────
  document.querySelectorAll('.features_nav a, .features_li a').forEach(function (a) {
    a.addEventListener('click', function () {
      document.querySelectorAll('header nav > ul > li > a, header nav > ul > li > span').forEach(function (el) {
        el.classList.remove('active');
      });
      this.classList.add('active');
    });
  });
  if (pathname === '/' && window.location.hash === '#features') {
    document.querySelectorAll('.features_nav a, .features_li a').forEach(function (a) {
      a.classList.add('active');
    });
  }

  // ── Hero + platform slideshow cross-fade ──────────────────────────────────────
  (function () {
    if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    function bind(rootSel, slideSel, ms) {
      document.querySelectorAll(rootSel).forEach(function (root) {
        const slides = root.querySelectorAll(slideSel);
        if (slides.length < 2) return;
        let idx = 0, timer = null, inView = true;
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
        on();
        if ('IntersectionObserver' in window) {
          new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
              inView = e.isIntersecting;
              if (inView && !document.hidden) on(); else off();
            });
          }, { threshold: 0 }).observe(root);
        }
      });
    }
    bind('.hero__slideshow',     '.hero__slide',         5500);
    bind('.platform__img-stack', '.platform__img-slide', 7000);
  }());

  // ── Inline form Parsley init replaced by native HTML5 validation ──────────────
  // All form fields already carry `required` and `type` attributes.
  // Phone fields carry `pattern` for format validation.
  ['phone', 'sf_reg_phone'].forEach(function (id) {
    const input = document.getElementById(id);
    if (input) {
      input.setAttribute('pattern', '\\d{3}-\\d{3}-\\d{4}');
      input.setAttribute('title', 'Please enter a valid 10-digit phone number (e.g. 555-912-0088)');
    }
  });

  // ── Helper: show "check your email" dialog after form submit ─────────────────
  function sfShowCheckEmail(email) {
    const msg = document.querySelector('.sf-check-email-msg');
    if (!msg) return;
    if (email) {
      const addrEl = msg.querySelector('.sf-check-email-msg__address');
      if (addrEl) addrEl.textContent = email;
    }
    sfFadeIn(msg);
    sfScrollLock();
  }

  // ── Focus trap ────────────────────────────────────────────────────────────────
  function sfFocusTrap(modalEl) {
    const SEL = 'a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled])';
    function getFocusable() {
      return Array.from(modalEl.querySelectorAll(SEL)).filter(function (el) {
        return el.offsetParent !== null;
      });
    }
    function handler(e) {
      if (e.key !== 'Tab') return;
      const els = getFocusable();
      if (!els.length) return;
      const first = els[0];
      const last  = els[els.length - 1];
      if (e.shiftKey) {
        if (document.activeElement === first) { e.preventDefault(); last.focus(); }
      } else {
        if (document.activeElement === last)  { e.preventDefault(); first.focus(); }
      }
    }
    modalEl.addEventListener('keydown', handler);
    const firstFocusable = getFocusable()[0];
    if (firstFocusable) setTimeout(function () { firstFocusable.focus(); }, 60);
    return function () { modalEl.removeEventListener('keydown', handler); };
  }
  let _sfRegTrap     = null;
  let _sfPartnerTrap = null;
  let _sfRegTrigger     = null;
  let _sfPartnerTrigger = null;

  // ── AJAX helper using fetch ───────────────────────────────────────────────────
  function sfAjax(data, onSuccess, onError) {
    if (typeof salefishAjax === 'undefined') { if (onError) onError(new Error('salefishAjax not defined')); return; }
    fetch(salefishAjax.ajaxurl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: data,
    })
      .then(function (res) { return res.json(); })
      .then(onSuccess)
      .catch(onError);
  }

  // ── REG FORM (inline on contact pages) ───────────────────────────────────────
  const regForm = document.getElementById('reg_form');
  if (regForm) {
    regForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const params = serializeForm(this) + '&action=salefish_register&nonce=' + salefishAjax.nonce;
      sfAjax(params, function (res) {
        if (res.success) {
          sfTrackConversion('demo_request', 'inline_form');
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
        }
      }, null);
    });
  }

  // ── AGENT FORM ────────────────────────────────────────────────────────────────
  const agentForm = document.getElementById('agent_form');
  if (agentForm) {
    agentForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const params = serializeForm(this) + '&action=agents_register&nonce=' + salefishAjax.nonce;
      sfAjax(params, function (res) {
        if (res.success) {
          sfTrackConversion('agent_inquiry', 'inline_form');
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
        }
      }, null);
    });
  }

  // ── PARTNER FORM (inline on partners page) ────────────────────────────────────
  const partnerForm = document.getElementById('partner_form');
  if (partnerForm) {
    partnerForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const params = serializeForm(this) + '&action=partner_register&nonce=' + salefishAjax.nonce;
      sfAjax(params, function (res) {
        if (res.success) {
          sfTrackConversion('partner_inquiry', 'inline_form');
          sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
        }
      }, null);
    });
  }

  // ── CLOSE CHECK-EMAIL DIALOG ──────────────────────────────────────────────────
  // pointerdown fires the instant the finger contacts the screen (zero
  // perceptible lag on iOS). e.preventDefault() cancels the synthetic
  // click so the click handler doesn't also fire 300 ms later.
  // The click handler stays as a fallback for mouse and keyboard users.
  document.querySelectorAll('.sf-check-email-close').forEach(function (btn) {
    function doCloseCheckEmail() {
      sfFadeOut(document.querySelector('.sf-check-email-msg'));
      sfScrollUnlock();
    }
    btn.addEventListener('pointerdown', function (e) {
      if (e.pointerType === 'mouse') return;
      e.preventDefault();
      doCloseCheckEmail();
    });
    btn.addEventListener('click', doCloseCheckEmail);
  });

  // ── CLOSE THANK YOU MESSAGE ───────────────────────────────────────────────────
  document.querySelectorAll('.close_thank_you_msg').forEach(function (btn) {
    function doCloseThankYou() {
      sfFadeOut(document.querySelector('.thank_you_msg'));
      sfScrollUnlock();
    }
    btn.addEventListener('pointerdown', function (e) {
      if (e.pointerType === 'mouse') return;
      e.preventDefault();
      doCloseThankYou();
    });
    btn.addEventListener('click', doCloseThankYou);
  });

  // ── REGISTRATION MODAL ────────────────────────────────────────────────────────
  function sfRegModalOpen(section) {
    const modal = document.getElementById('sf-reg-modal');
    if (modal && modal.style.display === 'block') return;
    if (window.sfEnsureModals) window.sfEnsureModals();
    const regSection = document.getElementById('sf_reg_section');
    if (regSection) regSection.value = section || '';
    sfScrollLock();
    if (window.clarity) {
      window.clarity('set', 'modal_type', 'registration');
      window.clarity('event', 'modal_open');
    }
    sfFadeIn(modal, 200, function () {
      _sfRegTrap = sfFocusTrap(modal);
    });
  }

  function sfRegModalClose() {
    if (_sfRegTrap) { _sfRegTrap(); _sfRegTrap = null; }
    sfScrollUnlock();
    const returnFocus = _sfRegTrigger;
    _sfRegTrigger = null;
    sfFadeOut(document.getElementById('sf-reg-modal'), 0, function () {
      const form = document.getElementById('sf_reg_form');
      if (form) form.reset();
      const err = document.getElementById('sf-reg-form-error');
      if (err) err.remove();
      if (returnFocus) returnFocus.focus();
    });
  }

  // pointerdown fast-path — fires instantly on touch; e.preventDefault()
  // cancels the synthetic click so the handler below doesn't also fire.
  document.addEventListener('pointerdown', function (e) {
    if (e.pointerType === 'mouse') return;
    const trigger = e.target.closest('[data-sf-modal="register"]');
    if (trigger) {
      e.preventDefault();
      if (window.sfMenuClose) window.sfMenuClose();
      _sfRegTrigger = trigger;
      sfRegModalOpen(trigger.dataset.sfSection || '');
    }
  }, { capture: true });

  document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-sf-modal="register"]');
    if (trigger) {
      e.preventDefault();
      if (window.sfMenuClose) window.sfMenuClose();
      _sfRegTrigger = trigger;
      sfRegModalOpen(trigger.dataset.sfSection || '');
    }
  });

  document.addEventListener('click', function (e) {
    if (e.target.closest('#sf-reg-modal .sf-reg-modal__backdrop, #sf-reg-modal .sf-reg-modal__close')) {
      sfRegModalClose();
    }
  }, { capture: true });

  // pointerdown counterpart — fires instantly on touch, e.preventDefault()
  // cancels the synthetic click so the handler above doesn't also fire.
  document.addEventListener('pointerdown', function (e) {
    if (e.pointerType === 'mouse') return;
    if (e.target.closest('#sf-reg-modal .sf-reg-modal__backdrop, #sf-reg-modal .sf-reg-modal__close')) {
      e.preventDefault();
      sfRegModalClose();
    }
  }, { capture: true });

  function sfInsertFormError(form, id, msg) {
    const lastRow = form.querySelector('.row:last-child');
    if (!lastRow) return;
    const p = document.createElement('p');
    p.id = id;
    p.className = 'sf-form-error';
    p.setAttribute('role', 'alert');
    p.textContent = msg;
    lastRow.before(p);
  }

  // REG MODAL FORM SUBMIT — delegated so it works after lazy injection
  document.addEventListener('submit', function (e) {
    if (!e.target.matches('#sf_reg_form')) return;
    e.preventDefault();
    const form   = e.target;
    const btn    = form.querySelector('[type="submit"]');
    const origVal = btn ? btn.value : '';
    if (btn) { btn.value = 'Submitting…'; btn.disabled = true; }
    const errEl = document.getElementById('sf-reg-form-error');
    if (errEl) errEl.remove();
    const params = serializeForm(form) + '&action=salefish_register&nonce=' + salefishAjax.nonce;
    sfAjax(params, function (res) {
      if (res.success) {
        sfTrackConversion('demo_request', 'modal');
        sfRegModalClose();
        sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
      } else {
        const msg = (res.data && res.data.message) ? res.data.message : 'Something went wrong — please try again.';
        sfInsertFormError(form, 'sf-reg-form-error', msg);
        if (btn) { btn.value = origVal; btn.disabled = false; }
      }
    }, function () {
      sfInsertFormError(form, 'sf-reg-form-error', 'Connection error — please try again.');
      if (btn) { btn.value = origVal; btn.disabled = false; }
    });
  });

  // ── PARTNER REGISTRATION MODAL ────────────────────────────────────────────────
  function sfPartnerModalOpen(partnerType, _section) {
    const modal = document.getElementById('sf-partner-modal');
    if (modal && modal.style.display === 'block') return;
    if (window.sfEnsureModals) window.sfEnsureModals();
    sfScrollLock();
    if (window.clarity) {
      window.clarity('set', 'modal_type', 'partner');
      window.clarity('event', 'modal_open');
    }
    if (partnerType) {
      const sel = document.getElementById('sf_partner_want_to_do');
      if (sel) sel.value = partnerType;
    }
    sfFadeIn(modal, 200, function () {
      _sfPartnerTrap = sfFocusTrap(modal);
    });
  }

  function sfPartnerModalClose() {
    if (_sfPartnerTrap) { _sfPartnerTrap(); _sfPartnerTrap = null; }
    sfScrollUnlock();
    const returnFocus = _sfPartnerTrigger;
    _sfPartnerTrigger = null;
    sfFadeOut(document.getElementById('sf-partner-modal'), 0, function () {
      const form = document.getElementById('sf_partner_form');
      if (form) form.reset();
      const err = document.getElementById('sf-partner-form-error');
      if (err) err.remove();
      if (returnFocus) returnFocus.focus();
    });
  }

  // pointerdown fast-path — fires instantly on touch; e.preventDefault()
  // cancels the synthetic click so the handler below doesn't also fire.
  document.addEventListener('pointerdown', function (e) {
    if (e.pointerType === 'mouse') return;
    const trigger = e.target.closest('[data-sf-modal="partner"]');
    if (trigger) {
      e.preventDefault();
      if (window.sfMenuClose) window.sfMenuClose();
      _sfPartnerTrigger = trigger;
      sfPartnerModalOpen(trigger.dataset.sfPartnerType || '', trigger.dataset.sfSection || '');
    }
  }, { capture: true });

  document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-sf-modal="partner"]');
    if (trigger) {
      e.preventDefault();
      if (window.sfMenuClose) window.sfMenuClose();
      _sfPartnerTrigger = trigger;
      sfPartnerModalOpen(trigger.dataset.sfPartnerType || '', trigger.dataset.sfSection || '');
    }
  });

  document.addEventListener('click', function (e) {
    if (e.target.closest('#sf-partner-modal .sf-partner-modal__backdrop, #sf-partner-modal .sf-partner-modal__close')) {
      sfPartnerModalClose();
    }
  }, { capture: true });

  document.addEventListener('pointerdown', function (e) {
    if (e.pointerType === 'mouse') return;
    if (e.target.closest('#sf-partner-modal .sf-partner-modal__backdrop, #sf-partner-modal .sf-partner-modal__close')) {
      e.preventDefault();
      sfPartnerModalClose();
    }
  }, { capture: true });

  // PARTNER MODAL FORM SUBMIT — delegated so it works after lazy injection
  document.addEventListener('submit', function (e) {
    if (!e.target.matches('#sf_partner_form')) return;
    e.preventDefault();
    const form    = e.target;
    const btn     = form.querySelector('[type="submit"]');
    const origVal = btn ? btn.value : '';
    if (btn) { btn.value = 'Submitting…'; btn.disabled = true; }
    const errEl = document.getElementById('sf-partner-form-error');
    if (errEl) errEl.remove();
    const params = serializeForm(form) + '&action=partner_register&nonce=' + salefishAjax.nonce;
    sfAjax(params, function (res) {
      if (res.success) {
        sfTrackConversion('partner_inquiry', 'modal');
        sfPartnerModalClose();
        sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
      } else {
        const msg = (res.data && res.data.message) ? res.data.message : 'Something went wrong — please try again.';
        sfInsertFormError(form, 'sf-partner-form-error', msg);
        if (btn) { btn.value = origVal; btn.disabled = false; }
      }
    }, function () {
      sfInsertFormError(form, 'sf-partner-form-error', 'Connection error — please try again.');
      if (btn) { btn.value = origVal; btn.disabled = false; }
    });
  });

  // ── Escape key closes open modals ─────────────────────────────────────────────
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    const regModal     = document.getElementById('sf-reg-modal');
    const partnerModal = document.getElementById('sf-partner-modal');
    if (regModal && regModal.style.display !== 'none' && regModal.style.opacity !== '0') sfRegModalClose();
    if (partnerModal && partnerModal.style.display !== 'none' && partnerModal.style.opacity !== '0') sfPartnerModalClose();
  });

});

// ── Tidio chat button: white outline ring ─────────────────────────────────────
// MutationObserver replaces the previous setInterval that fired every 500 ms
// for up to 60 seconds (120 main-thread wake-ups on every page load). The
// observer fires exactly once when Tidio injects its iframe — zero polling,
// zero recurring cost.
(function () {
  function applyTidioRing(iframe) {
    function update() {
      const isButton = iframe.offsetWidth <= 160 && iframe.offsetHeight <= 160;
      iframe.style.borderRadius = isButton ? '50%' : '';
      iframe.style.boxShadow   = isButton ? '0 0 0 3px rgba(255,255,255,0.85), 0 2px 12px rgba(0,0,0,0.25)' : '';
    }
    update();
    if (window.ResizeObserver) new ResizeObserver(update).observe(iframe);
  }

  // If Tidio is already in the DOM (e.g. bfcache restore), apply immediately.
  const existing = document.getElementById('tidio-chat-iframe');
  if (existing) { applyTidioRing(existing); return; }

  // Otherwise watch for it to be inserted.
  const observer = new MutationObserver(function () {
    const iframe = document.getElementById('tidio-chat-iframe');
    if (iframe) { observer.disconnect(); applyTidioRing(iframe); }
  });
  observer.observe(document.body, { childList: true, subtree: true });

  // Safety: disconnect after 90 s in case Tidio never loads (e.g. ad-blocker).
  setTimeout(function () { observer.disconnect(); }, 90000);
}());

// ── Scroll-to-top button ──────────────────────────────────────────────────────
(function () {
  const btn = document.getElementById('sf-scroll-top');
  if (!btn) return;
  btn.removeAttribute('hidden');
  let isShown = false;
  let ticking = false;
  function update() {
    ticking = false;
    const shouldShow = window.scrollY > 400;
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
