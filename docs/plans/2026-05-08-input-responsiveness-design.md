# Input Responsiveness Fixes — Design

**Date:** 2026-05-08
**Scope:** Option A — targeted fixes for confirmed bugs and highest-impact responsiveness gaps

---

## Problems

### 1. Phone mask never applied in registration modal

`applyPhoneMask(document.getElementById('sf_reg_phone'))` runs at `DOMContentLoaded`. The modal lives inside `<template id="sf-modals-template">` and is not cloned into the live DOM until `sfEnsureModals()` fires on first user interaction. `getElementById` returns `null`; the call is silently dropped. The modal phone field accepts unformatted input.

### 2. No `pointerdown` fast-path for modal triggers

The hamburger (`.sf-menu-btn`) and language picker (`.languages`) already have `pointerdown` handlers for instant touch response. `[data-sf-modal]` buttons ("Get a Demo", "Book a Demo") only listen on `click`, which fires ~300 ms after touchend on some iOS configurations.

### 3. Floating menu hover swaps `font-family` — causes layout shift

`.floating_menu .top ul li a:hover { font-family: $PSB }` switches the font file from Poppins Light to Poppins SemiBold. Because the two weights have different character widths, every hover shifts the widths of adjacent menu items — a full layout reflow per `mouseover`.

### 4. Language picker dropdown has no `:active` touch state

`li:hover { background: $light_purple }` fires on desktop only. Touch users see no visual change between tapping and navigation — the element feels unresponsive.

---

## Fixes

### Fix 1 — Phone mask applied after template clone

**File:** `footer.php` (inside `sfEnsureModals`), `general.js` (DOMContentLoaded block)

- In `sfEnsureModals()` in `footer.php`, after the template fragment is appended to `document.body`, call `applyPhoneMask(document.getElementById('sf_reg_phone'))`.
- Guard with a module-scoped boolean (`_sfModalsMaskApplied`) so subsequent calls to `sfEnsureModals()` (on re-open) don't re-attach duplicate `input` listeners.
- The existing `applyPhoneMask(document.getElementById('phone'))` call in `general.js` (for the inline contact form) stays unchanged.

### Fix 2 — `pointerdown` fast-path for modal triggers

**File:** `general.js`

Add a delegated `pointerdown` listener on `document` for `[data-sf-modal="register"]` and `[data-sf-modal="partner"]`:

```js
document.addEventListener('pointerdown', function (e) {
  if (e.pointerType === 'mouse') return;
  const trigger = e.target.closest('[data-sf-modal="register"]');
  if (trigger) {
    e.preventDefault();
    _sfRegTrigger = trigger;
    sfRegModalOpen(trigger.dataset.sfSection || '');
  }
}, { capture: true });

document.addEventListener('pointerdown', function (e) {
  if (e.pointerType === 'mouse') return;
  const trigger = e.target.closest('[data-sf-modal="partner"]');
  if (trigger) {
    e.preventDefault();
    _sfPartnerTrigger = trigger;
    sfPartnerModalOpen(trigger.dataset.sfPartnerType || '', trigger.dataset.sfSection || '');
  }
}, { capture: true });
```

`e.preventDefault()` cancels the synthetic `click` so the existing `click` delegated handler does not double-fire. `capture: true` matches the pattern already used for close-button handlers.

### Fix 3 — Remove `font-family` swap from floating menu hover

**File:** `assets/scss/layouts/header.scss`

In `.floating_menu .top ul li a`:

```scss
// Before
a {
  &:hover { color: $dark_purple; font-family: $PSB; }
}

// After
a {
  &:hover { color: $dark_purple; }
}
```

The active-page rule (`font-family: $PSB; color: $dark_purple`) is on a separate selector and is unaffected.

### Fix 4 — Language picker `:active` touch feedback

**File:** `assets/scss/layouts/header.scss`

In `.languages_option ul`:

```scss
// Before
li:hover {
  background: $light_purple;
}

// After
li:hover,
li:active {
  background: $light_purple;
}

li:hover a,
li:active a {
  color: #fff;
}
```

Gives an immediate purple flash + white text on touch, matching the hover intent.

---

## Files changed

| File | Fix(es) |
|---|---|
| `assets/js/general.js` | Fix 2 (pointerdown for modal triggers) |
| `footer.php` | Fix 1 (apply phone mask after template clone) |
| `assets/scss/layouts/header.scss` | Fix 3 (remove font-family hover swap) + Fix 4 (language :active state) |

No new files. No changes to build config, service worker, or PHP page templates.

## Build & deploy

1. `npm run prod` (SCSS changes require a build)
2. FTP upload: `dest/app.css`, `dest/app.js`, `dest/pages/home.js` (if affected), `footer.php`, `header.scss` (source only — not served directly)
3. Verify byte sizes match local files
4. Purge LiteSpeed cache
