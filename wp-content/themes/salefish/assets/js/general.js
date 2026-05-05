// SmoothScroll removed — replaced by native `scroll-behavior: smooth` on html
// Parsley / jquery.mask / flowtype removed — replaced by vanilla JS equivalents

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

  // ── Verified email redirect banner ──────────────────────────────────────────
  if (new URLSearchParams(window.location.search).get('salefish_verified') === '1') {
    const thankYouMsg = document.querySelector('.thank_you_msg');
    if (thankYouMsg) sfFadeIn(thankYouMsg);
    document.body.style.overflow = 'hidden';
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
      document.querySelectorAll('.sf-menu-btn.is-active').forEach(function (b) {
        b.classList.remove('is-active');
        b.setAttribute('aria-expanded', 'false');
      });
      document.querySelectorAll('.floating_menu.is-open').forEach(function (m) {
        m.classList.remove('is-open');
        m.setAttribute('inert', '');
      });
      document.body.style.overflow = 'hidden';
    });
  });

  document.querySelectorAll('.close_privacy').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const pp = document.querySelector('.privacy_policy');
      if (pp) pp.classList.remove('active');
      document.body.style.overflow = '';
    });
  });

  // ── Terms popup ───────────────────────────────────────────────────────────────
  document.querySelectorAll('.terms_menu, .terms_menu_footer').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const tp = document.querySelector('.terms_popup');
      if (tp) tp.classList.add('active');
      document.querySelectorAll('.sf-menu-btn.is-active').forEach(function (b) {
        b.classList.remove('is-active');
        b.setAttribute('aria-expanded', 'false');
      });
      document.querySelectorAll('.floating_menu.is-open').forEach(function (m) {
        m.classList.remove('is-open');
        m.setAttribute('inert', '');
      });
      document.body.style.overflow = 'hidden';
    });
  });

  document.querySelectorAll('.close_terms').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const tp = document.querySelector('.terms_popup');
      if (tp) tp.classList.remove('active');
      document.body.style.overflow = '';
    });
  });

  // ── Close floating menu when a link inside it is clicked ─────────────────────
  document.addEventListener('click', function (e) {
    if (e.target.closest('.floating_menu a, .floating_menu .mobile')) {
      document.querySelectorAll('.sf-menu-btn.is-active').forEach(function (b) {
        b.classList.remove('is-active');
        b.setAttribute('aria-expanded', 'false');
      });
      document.querySelectorAll('.floating_menu.is-open').forEach(function (m) {
        m.classList.remove('is-open');
        m.setAttribute('inert', '');
      });
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
    document.body.style.overflow = 'hidden';
  }

  // ── Focus trap ────────────────────────────────────────────────────────────────
  function sfFocusTrap(modalEl) {
    const SEL = 'a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])';
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
        if (res.success) sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
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
        if (res.success) sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
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
        if (res.success) sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
      }, null);
    });
  }

  // ── CLOSE CHECK-EMAIL DIALOG ──────────────────────────────────────────────────
  document.querySelectorAll('.sf-check-email-close').forEach(function (btn) {
    btn.addEventListener('click', function () {
      sfFadeOut(document.querySelector('.sf-check-email-msg'));
      document.body.style.overflow = '';
    });
  });

  // ── CLOSE THANK YOU MESSAGE ───────────────────────────────────────────────────
  document.querySelectorAll('.close_thank_you_msg').forEach(function (btn) {
    btn.addEventListener('click', function () {
      sfFadeOut(document.querySelector('.thank_you_msg'));
      document.body.style.overflow = '';
    });
  });

  // ── Render Turnstile widget inside a freshly-opened modal ────────────────────
  function sfRenderTurnstileIn(modalEl) {
    if (!modalEl) return;
    const node = modalEl.querySelector('.cf-turnstile');
    if (!node) return;
    let attempts = 0;
    function tryRender() {
      attempts++;
      if (window.turnstile && typeof window.turnstile.render === 'function') {
        if (node.querySelector('iframe')) return;
        try {
          window.turnstile.render(node, {
            sitekey: node.getAttribute('data-sitekey'),
            theme:   node.getAttribute('data-theme') || 'auto',
          });
        } catch (e) { /* turnstile not ready yet */ }
        return;
      }
      if (attempts < 80) setTimeout(tryRender, 100);
    }
    tryRender();
  }

  // ── REGISTRATION MODAL ────────────────────────────────────────────────────────
  function sfRegModalOpen(section) {
    if (window.sfEnsureModals) window.sfEnsureModals();
    const scrollbarW = window.innerWidth - document.documentElement.clientWidth;
    const regSection = document.getElementById('sf_reg_section');
    if (regSection) regSection.value = section || '';
    if (scrollbarW > 0) {
      document.body.style.paddingRight = scrollbarW + 'px';
      document.querySelectorAll('header').forEach(function (h) { h.style.paddingRight = scrollbarW + 'px'; });
    }
    document.documentElement.style.overflow = 'hidden';
    document.body.style.overflow = 'hidden';
    const modal = document.getElementById('sf-reg-modal');
    sfFadeIn(modal, 200, function () {
      sfRenderTurnstileIn(modal);
      _sfRegTrap = sfFocusTrap(modal);
    });
  }

  function sfRegModalClose() {
    if (_sfRegTrap) { _sfRegTrap(); _sfRegTrap = null; }
    document.documentElement.style.overflow = '';
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.querySelectorAll('header').forEach(function (h) { h.style.paddingRight = ''; });
    const returnFocus = _sfRegTrigger;
    _sfRegTrigger = null;
    sfFadeOut(document.getElementById('sf-reg-modal'), 200, function () {
      const form = document.getElementById('sf_reg_form');
      if (form) form.reset();
      const err = document.getElementById('sf-reg-form-error');
      if (err) err.remove();
      if (window.turnstile && typeof window.turnstile.reset === 'function') window.turnstile.reset();
      if (returnFocus) returnFocus.focus();
    });
  }

  document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-sf-modal="register"]');
    if (trigger) {
      e.preventDefault();
      _sfRegTrigger = trigger;
      sfRegModalOpen(trigger.dataset.sfSection || '');
    }
  });

  document.addEventListener('click', function (e) {
    if (e.target.closest('#sf-reg-modal .sf-reg-modal__backdrop, #sf-reg-modal .sf-reg-modal__close')) {
      sfRegModalClose();
    }
  });

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
        sfRegModalClose();
        sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
      } else {
        const msg = (res.data && res.data.message) ? res.data.message : 'Something went wrong — please try again.';
        const lastRow = form.querySelector('.row:last-child');
        if (lastRow) lastRow.insertAdjacentHTML('beforebegin', '<p id="sf-reg-form-error" class="sf-form-error" role="alert">' + msg + '</p>');
        if (btn) { btn.value = origVal; btn.disabled = false; }
      }
    }, function () {
      const lastRow = form.querySelector('.row:last-child');
      if (lastRow) lastRow.insertAdjacentHTML('beforebegin', '<p id="sf-reg-form-error" class="sf-form-error" role="alert">Connection error — please try again.</p>');
      if (btn) { btn.value = origVal; btn.disabled = false; }
    });
  });

  // ── PARTNER REGISTRATION MODAL ────────────────────────────────────────────────
  function sfPartnerModalOpen(partnerType, _section) {
    if (window.sfEnsureModals) window.sfEnsureModals();
    const scrollbarW = window.innerWidth - document.documentElement.clientWidth;
    if (scrollbarW > 0) {
      document.body.style.paddingRight = scrollbarW + 'px';
      document.querySelectorAll('header').forEach(function (h) { h.style.paddingRight = scrollbarW + 'px'; });
    }
    if (partnerType) {
      const sel = document.getElementById('sf_partner_want_to_do');
      if (sel) sel.value = partnerType;
    }
    document.documentElement.style.overflow = 'hidden';
    document.body.style.overflow = 'hidden';
    const modal = document.getElementById('sf-partner-modal');
    sfFadeIn(modal, 200, function () {
      sfRenderTurnstileIn(modal);
      _sfPartnerTrap = sfFocusTrap(modal);
    });
  }

  function sfPartnerModalClose() {
    if (_sfPartnerTrap) { _sfPartnerTrap(); _sfPartnerTrap = null; }
    document.documentElement.style.overflow = '';
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.querySelectorAll('header').forEach(function (h) { h.style.paddingRight = ''; });
    const returnFocus = _sfPartnerTrigger;
    _sfPartnerTrigger = null;
    sfFadeOut(document.getElementById('sf-partner-modal'), 200, function () {
      const form = document.getElementById('sf_partner_form');
      if (form) form.reset();
      const err = document.getElementById('sf-partner-form-error');
      if (err) err.remove();
      if (window.turnstile && typeof window.turnstile.reset === 'function') window.turnstile.reset();
      if (returnFocus) returnFocus.focus();
    });
  }

  document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-sf-modal="partner"]');
    if (trigger) {
      e.preventDefault();
      _sfPartnerTrigger = trigger;
      sfPartnerModalOpen(trigger.dataset.sfPartnerType || '', trigger.dataset.sfSection || '');
    }
  });

  document.addEventListener('click', function (e) {
    if (e.target.closest('#sf-partner-modal .sf-partner-modal__backdrop, #sf-partner-modal .sf-partner-modal__close')) {
      sfPartnerModalClose();
    }
  });

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
        sfPartnerModalClose();
        sfShowCheckEmail(res.data && res.data.email ? res.data.email : '');
      } else {
        const msg = (res.data && res.data.message) ? res.data.message : 'Something went wrong — please try again.';
        const lastRow = form.querySelector('.row:last-child');
        if (lastRow) lastRow.insertAdjacentHTML('beforebegin', '<p id="sf-partner-form-error" class="sf-form-error" role="alert">' + msg + '</p>');
        if (btn) { btn.value = origVal; btn.disabled = false; }
      }
    }, function () {
      const lastRow = form.querySelector('.row:last-child');
      if (lastRow) lastRow.insertAdjacentHTML('beforebegin', '<p id="sf-partner-form-error" class="sf-form-error" role="alert">Connection error — please try again.</p>');
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
  const poll = setInterval(function () {
    const iframe = document.getElementById('tidio-chat-iframe');
    if (iframe) { clearInterval(poll); applyTidioRing(iframe); }
  }, 500);
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
