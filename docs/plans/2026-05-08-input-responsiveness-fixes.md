# Input Responsiveness Fixes Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Fix four confirmed interaction bugs — a phone-mask that never attaches in the modal, missing instant-touch handlers on modal CTA buttons, a layout-shifting font-family swap on menu hover, and missing touch feedback on the language picker.

**Architecture:** Three files touched total. JS changes live in `general.js` (pointerdown handlers) and `footer.php` (phone mask post-clone). SCSS changes live in `header.scss`. A production build is required after SCSS edits; JS source changes feed into the same build. Deploy via FTP per project CLAUDE.md.

**Tech Stack:** Vanilla JS (ES2022+), SCSS compiled via Laravel Mix (`npm run prod`), WordPress PHP templates, FTP deploy via lftp.

---

### Task 1: Fix phone mask — apply after modal template clone

The registration modal lives in `<template id="sf-modals-template">`. The current `applyPhoneMask` call in `general.js` runs at `DOMContentLoaded` when the template is not yet in the live DOM, so it silently no-ops.

**Files:**
- Modify: `wp-content/themes/salefish/footer.php` — inside `sfEnsureModals()`, after `document.body.appendChild(frag)`

**Step 1: Locate the injection point in footer.php**

Open `footer.php`. Find the `sfEnsureModals` function. It clones the template and appends it:

```js
window.sfEnsureModals = function () {
  if (document.getElementById('sf-reg-modal')) return;
  var tpl = document.getElementById('sf-modals-template');
  if (!tpl) return;
  var frag = tpl.content.cloneNode(true);
  document.body.appendChild(frag);
  // ... sfReplaceLucide calls follow
```

**Step 2: Add the phone mask call + guard**

Immediately after `document.body.appendChild(frag)`, before the `sfReplaceLucide` calls, add:

```js
  document.body.appendChild(frag);
  if (typeof applyPhoneMask === 'function') {
    applyPhoneMask(document.getElementById('sf_reg_phone'));
  }
```

No guard variable needed — `sfEnsureModals` already short-circuits at the top with `if (document.getElementById('sf-reg-modal')) return;`, so it only ever runs once.

**Step 3: Verify the existing DOMContentLoaded call in general.js is correct**

In `general.js`, confirm lines:
```js
applyPhoneMask(document.getElementById('phone'));
applyPhoneMask(document.getElementById('sf_reg_phone'));
```

The second call (`sf_reg_phone`) will still return `null` at DOMContentLoaded — that's fine, `applyPhoneMask` guards with `if (!input) return;`. Leave it as-is; it's harmless and serves as a belt-and-braces for any future inline usage of that ID.

**Step 4: Manual test**

1. Load any page with a "Get a Demo" button.
2. Click it — modal opens.
3. Tab to the Phone Number field.
4. Type `5559120088` — field should auto-format to `555-912-0088`.
5. Previously it would accept raw digits with no formatting.

**Step 5: Commit**

```bash
git add wp-content/themes/salefish/footer.php
git commit -m "fix(modal): apply phone mask after template clone into live DOM"
```

---

### Task 2: Add `pointerdown` fast-path for modal CTA triggers

`[data-sf-modal="register"]` and `[data-sf-modal="partner"]` open their modals on `click`. On iOS, `click` fires ~300 ms after touchend. The hamburger and close buttons already use `pointerdown` for instant response — this fix adds the same pattern to the open triggers.

**Files:**
- Modify: `wp-content/themes/salefish/assets/js/general.js`

**Step 1: Find the existing click handlers for modal triggers**

In `general.js`, locate these two blocks (inside the `DOMContentLoaded` callback):

```js
document.addEventListener('click', function (e) {
  const trigger = e.target.closest('[data-sf-modal="register"]');
  if (trigger) {
    e.preventDefault();
    _sfRegTrigger = trigger;
    sfRegModalOpen(trigger.dataset.sfSection || '');
  }
});
```

and

```js
document.addEventListener('click', function (e) {
  const trigger = e.target.closest('[data-sf-modal="partner"]');
  if (trigger) {
    e.preventDefault();
    _sfPartnerTrigger = trigger;
    sfPartnerModalOpen(trigger.dataset.sfPartnerType || '', trigger.dataset.sfSection || '');
  }
});
```

**Step 2: Add `pointerdown` counterparts directly above each click handler**

Add these two blocks, each immediately before its matching `click` handler:

```js
// pointerdown: instant touch response (skips ~300 ms click delay on iOS).
// e.preventDefault() cancels the synthetic click so the click handler below
// doesn't also fire and double-open the modal.
document.addEventListener('pointerdown', function (e) {
  if (e.pointerType === 'mouse') return;
  const trigger = e.target.closest('[data-sf-modal="register"]');
  if (trigger) {
    e.preventDefault();
    _sfRegTrigger = trigger;
    sfRegModalOpen(trigger.dataset.sfSection || '');
  }
}, { capture: true });
```

```js
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

**Why `capture: true`?** Matches the pattern already used on close-button `pointerdown` handlers (lines 524–530 and 611–617). Capture phase fires before Cloudflare Turnstile's own listeners.

**Step 3: Manual test — mobile (use DevTools device emulation or real device)**

1. Open the homepage on a touch device or with DevTools "Touch" simulation.
2. Tap "Get a Demo" — modal should open with zero perceptible delay.
3. Tap the backdrop to close.
4. Tap a `[data-sf-modal="partner"]` trigger (Partners page) — partner modal opens instantly.
5. On desktop (mouse), confirm both modals still open normally via click.

**Step 4: Commit**

```bash
git add wp-content/themes/salefish/assets/js/general.js
git commit -m "fix(touch): add pointerdown fast-path for modal open triggers"
```

---

### Task 3: Remove `font-family` swap from floating menu hover

Switching `font-family` on hover changes character widths, forcing a layout reflow and shifting sibling menu items every time the cursor moves over a link.

**Files:**
- Modify: `wp-content/themes/salefish/assets/scss/layouts/header.scss`

**Step 1: Find the hover rule**

In `header.scss`, find this block inside `.floating_menu .top ul li`:

```scss
a {
  &:hover { color: $dark_purple; font-family: $PSB; }

  &.active {
    font-family: $PSB;
    color: $dark_purple;
  }
}
```

**Step 2: Remove `font-family: $PSB` from the hover rule only**

```scss
a {
  &:hover { color: $dark_purple; }

  &.active {
    font-family: $PSB;
    color: $dark_purple;
  }
}
```

The `.active` rule is untouched — the current-page link correctly stays semibold.

**Step 3: Manual test**

1. Open the floating menu on desktop.
2. Hover over each nav link — links should change colour to `$dark_purple` with no width shift on adjacent items.
3. Confirm the current-page link still appears semibold (`.active` rule).

**Step 4: Build**

```bash
cd wp-content/themes/salefish && npm run prod && cd ../../..
```

(Hold off on deploying until Task 4 is also done — both are SCSS changes, one build covers both.)

---

### Task 4: Add `:active` touch feedback to language picker dropdown

`li:hover` fires on desktop only. Touch users tapping a language flag see no state change — the element appears unresponsive until navigation occurs.

**Files:**
- Modify: `wp-content/themes/salefish/assets/scss/layouts/header.scss`

**Step 1: Find the language option hover rule**

In `header.scss`, find `.languages_option ul`:

```scss
li:hover {
  background: $light_purple;
}
```

**Step 2: Add `:active` and link colour**

```scss
li:hover,
li:active {
  background: $light_purple;
}

li:hover a,
li:active a {
  color: #fff;
}
```

**Why colour the `a` too?** The default link colour is `$black`. On `$light_purple` background, black text has adequate contrast, but inverting to white matches the visual weight of the hover state and gives clearer tap confirmation.

**Step 3: Manual test — touch device**

1. Open the language picker on a touch device.
2. Tap a flag — the row should flash purple + white text before navigating.

**Step 4: Build**

```bash
cd wp-content/themes/salefish && npm run prod && cd ../../..
```

**Step 5: Commit SCSS source + built files**

```bash
git add wp-content/themes/salefish/assets/scss/layouts/header.scss \
        wp-content/themes/salefish/dest/app.css \
        wp-content/themes/salefish/dest/app.js
git commit -m "fix(ux): remove font-family hover shift in menu; add :active state to language picker"
```

---

### Task 5: Build JS bundle and commit

Task 2's JS changes also feed into the build. Run prod once to cover both.

**Step 1: Build**

```bash
cd wp-content/themes/salefish && npm run prod && cd ../../..
```

**Step 2: Commit built JS**

```bash
git add wp-content/themes/salefish/dest/app.js
git commit -m "build: prod bundle with modal pointerdown and phone mask fixes"
```

---

### Task 6: FTP deploy and verify

**Step 1: Get credentials**

```bash
FTP_PASS=$(security find-internet-password -s ftp.salefish.app -a "andrewdb@salefish.app" -w)
```

**Step 2: Upload changed files**

```bash
lftp -u "andrewdb@salefish.app,$FTP_PASS" ftp://ftp.salefish.app <<'EOF'
set ftp:ssl-allow no
set net:timeout 30
put -O /public_html/wp-content/themes/salefish/dest /Users/andrewdavidblair/Documents/Claude/Projects/🐠\ SaleFish/SaleFish\ Marketing\ Website/wp-content/themes/salefish/dest/app.css
put -O /public_html/wp-content/themes/salefish/dest /Users/andrewdavidblair/Documents/Claude/Projects/🐠\ SaleFish/SaleFish\ Marketing\ Website/wp-content/themes/salefish/dest/app.js
put -O /public_html/wp-content/themes/salefish /Users/andrewdavidblair/Documents/Claude/Projects/🐠\ SaleFish/SaleFish\ Marketing\ Website/wp-content/themes/salefish/footer.php
bye
EOF
```

**Step 3: Verify uploads**

```bash
lftp -u "andrewdb@salefish.app,$FTP_PASS" ftp://ftp.salefish.app <<'EOF'
set ftp:ssl-allow no
set net:timeout 30
ls /public_html/wp-content/themes/salefish/dest/app.css
ls /public_html/wp-content/themes/salefish/dest/app.js
ls /public_html/wp-content/themes/salefish/footer.php
bye
EOF
```

Check that timestamps are today and byte sizes match local files:

```bash
wc -c wp-content/themes/salefish/dest/app.css
wc -c wp-content/themes/salefish/dest/app.js
wc -c wp-content/themes/salefish/footer.php
```

**Step 4: Purge LiteSpeed cache**

WP Admin → LiteSpeed Cache → Toolbox → Purge All

**Step 5: Smoke test on live site**

- Mobile: tap "Get a Demo" — modal opens instantly, phone field formats on input
- Mobile: tap language picker — flags flash purple on tap
- Desktop: hover floating menu links — no sibling shift on hover
