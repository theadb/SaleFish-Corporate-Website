# SaleFish 2026 Theme Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build a new `salefish-2026` WordPress theme that ports Plinthra's iOS-inspired design system — CSS custom properties, system fonts, light/dark mode, body gradients, noise texture, 26px-radius cards, and semantic badge/chip components.

**Architecture:** Full copy of `salefish/` renamed to `salefish-2026/`, SCSS layer replaced entirely with CSS custom properties + `.dark` class toggling. PHP templates unchanged except `header.php` which receives the dark-mode inline script and a toggle button. The existing theme stays live throughout development; no visitors are affected until you manually activate the new theme in WP Admin.

**Tech Stack:** WordPress classic theme, Laravel Mix 6 (Webpack + Sass), SCSS, vanilla JS (dark mode toggle only), system font stack (zero web font requests).

---

## Before you start

The theme directory lives at:
```
wp-content/themes/salefish/
```
All new work goes into:
```
wp-content/themes/salefish-2026/
```

To build CSS run from inside `salefish-2026/`:
```bash
npm install   # first time only
npm run dev   # watch mode — recompiles on save
npm run prod  # minified production build
```

The compiled output lands in `dest/app.css` and `dest/app.js`.

BrowserSync is pre-configured to proxy `salefish.local` — if you have LocalWP running, `npm run dev` will auto-reload on save.

---

## Task 1: Scaffold the theme directory

**Files:**
- Create: `wp-content/themes/salefish-2026/` (full copy of salefish)
- Modify: `wp-content/themes/salefish-2026/style.css`

**Step 1: Copy the theme**

```bash
cp -r wp-content/themes/salefish wp-content/themes/salefish-2026
```

**Step 2: Update the theme header in `style.css`**

Replace the first block (lines 1–20) of `wp-content/themes/salefish-2026/style.css`:

```css
/*
Theme Name: SaleFish 2026
Theme URI: https://salefish.app
Author: SaleFish
Author URI: https://salefish.app
Description: SaleFish corporate marketing theme — 2026 edition. iOS-inspired design system with CSS custom properties, system fonts, light/dark mode, and ocean-themed palette.
Version: 2.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: salefish, 2026
*/
```

Everything after the comment block stays as-is.

**Step 3: Verify theme appears in WP Admin**

FTP `salefish-2026/style.css` to the server. Go to WP Admin → Appearance → Themes. You should see "SaleFish 2026" listed alongside the current theme. Do not activate it yet.

**Step 4: Commit**

```bash
git add wp-content/themes/salefish-2026/style.css
git commit -m "feat: scaffold salefish-2026 theme directory"
```

---

## Task 2: Update functions.php

**Files:**
- Modify: `wp-content/themes/salefish-2026/functions.php`

The existing `functions.php` enqueues CSS and JS but has two problems:
1. No cache-busting version on the stylesheet
2. JS loads in footer but without `defer` strategy (WP 6.3+ supports the `strategy` arg)

**Step 1: Replace `kickass_scripts()` function**

Find the `kickass_scripts` function (around line 122) and replace it entirely:

```php
function salefish_2026_scripts() {
    $theme_version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
        'salefish-2026-style',
        get_template_directory_uri() . '/dest/app.css',
        [],
        $theme_version
    );

    wp_enqueue_script(
        'salefish-2026-app',
        get_template_directory_uri() . '/dest/app.js',
        [],
        $theme_version,
        [ 'strategy' => 'defer', 'in_footer' => true ]
    );

    wp_localize_script( 'salefish-2026-app', 'salefishAjax', [
        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        'nonce'         => wp_create_nonce( 'salefish_nonce' ),
        'loadMoreNonce' => wp_create_nonce( 'salefish_load_more' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'salefish_2026_scripts' );
```

Also remove (or comment out) the old `add_action` line:
```php
// Remove this line — replaced by salefish_2026_scripts above
// add_action('wp_enqueue_scripts', 'kickass_scripts');
```

**Step 2: Commit**

```bash
git add wp-content/themes/salefish-2026/functions.php
git commit -m "feat: update salefish-2026 functions.php — deferred JS, versioned CSS"
```

---

## Task 3: Build CSS token files — `_variables.scss`

**Files:**
- Modify: `wp-content/themes/salefish-2026/assets/scss/basics/_variables.scss`

This replaces the old 7-line SCSS variable file with a full CSS custom property system. All tokens match Plinthra exactly.

**Step 1: Replace `_variables.scss` entirely**

```scss
// ============================================================
// SaleFish 2026 — Design Tokens (Light Mode)
// All values sourced from Plinthra (theadb/SaleFish-Plinthra)
// ============================================================

@layer base {
  :root {
    // ── Interaction overlays ────────────────────────────────
    --sf-elevate-1: rgba(0, 0, 0, 0.02);
    --sf-elevate-2: rgba(0, 0, 0, 0.05);
    --sf-button-outline: rgba(0, 0, 0, 0.08);

    // ── Core surfaces ───────────────────────────────────────
    --sf-bg:             240 100% 99%;
    --sf-fg:             0 0% 9%;
    --sf-border:         240 20% 75%;

    // ── Card ────────────────────────────────────────────────
    --sf-card:           240 100% 99%;
    --sf-card-fg:        0 0% 9%;
    --sf-card-border:    240 20% 72%;

    // ── Primary (brand purple) ──────────────────────────────
    --sf-primary:        256 51% 36%;
    --sf-primary-fg:     0 0% 100%;

    // ── Secondary ───────────────────────────────────────────
    --sf-secondary:      240 20% 94%;
    --sf-secondary-fg:   210 20% 15%;

    // ── Muted ───────────────────────────────────────────────
    --sf-muted:          240 20% 95%;
    --sf-muted-fg:       210 15% 35%;

    // ── Accent ──────────────────────────────────────────────
    --sf-accent:         270 50% 75%;
    --sf-accent-fg:      210 20% 15%;

    // ── Destructive ─────────────────────────────────────────
    --sf-destructive:    0 84% 60%;
    --sf-destructive-fg: 0 0% 100%;

    // ── Focus ring ──────────────────────────────────────────
    --sf-ring:           256 51% 30%;

    // ── Ocean palette ───────────────────────────────────────
    --sf-ocean:          210 100% 28%;
    --sf-ocean-fg:       0 0% 100%;
    --sf-seaweed:        150 85% 25%;
    --sf-seaweed-fg:     0 0% 100%;
    --sf-clownfish:      48 100% 26%;
    --sf-clownfish-fg:   0 0% 100%;
    --sf-starfish:       45 100% 26%;
    --sf-starfish-fg:    0 0% 100%;
    --sf-coral:          355 95% 30%;
    --sf-coral-fg:       0 0% 100%;
    --sf-salmon:         10 100% 32%;
    --sf-salmon-fg:      0 0% 100%;
    --sf-seahorse:       180 90% 25%;
    --sf-seahorse-fg:    0 0% 100%;

    // ── Fonts ────────────────────────────────────────────────
    --sf-font-sans:   -apple-system, BlinkMacSystemFont, 'SF Pro Display',
                      'SF Pro Text', 'Segoe UI', Roboto, 'Helvetica Neue',
                      Arial, sans-serif;
    --sf-font-serif:  'New York', Georgia, serif;
    --sf-font-mono:   'SF Mono', Monaco, Consolas, 'Courier New', monospace;

    // ── Border radius (macOS 26 inspired) ───────────────────
    --sf-radius-sm:  0.375rem;  // 6px  — tags, inputs
    --sf-radius:     0.5rem;    // 8px  — buttons, badges
    --sf-radius-lg:  1.625rem;  // 26px — cards, modals

    // ── Shadows (light) ─────────────────────────────────────
    --sf-shadow-sm:  0 1px 2px hsl(0 0% 0% / .05),
                     0 2px 4px hsl(0 0% 0% / .08);
    --sf-shadow:     0 1px 3px hsl(0 0% 0% / .08),
                     0 4px 6px -2px hsl(0 0% 0% / .08);
    --sf-shadow-md:  0 4px 6px -1px hsl(0 0% 0% / .08),
                     0 2px 4px -2px hsl(0 0% 0% / .05);
    --sf-shadow-lg:  0 10px 15px -3px hsl(0 0% 0% / .08),
                     0 4px 6px -4px hsl(0 0% 0% / .05);

    // ── Legacy SCSS variable aliases ────────────────────────
    // These keep existing page SCSS working without rewrites.
    // They point to the nearest equivalent 2026 token.
  }
}

// Keep SCSS variables as aliases so page-level SCSS doesn't break.
// Gradually migrate usages to CSS custom props over time.
$dark_purple: hsl(var(--sf-primary));
$light_purple: hsl(270 40% 60%);
$gold:         hsl(38 80% 60%);
$black:        hsl(var(--sf-fg));
$gray:         hsl(var(--sf-secondary));
$green:        hsl(var(--sf-seaweed));
$red:          hsl(var(--sf-destructive));
```

**Step 2: Verify it compiles (will fail until fonts file is updated — continue to Task 4 first, then circle back)**

Note: `$PR`, `$PSB`, `$PB`, `$PL` are defined in `_fonts.scss` which we update next. The build will not compile cleanly until Tasks 3, 4, and 5 are all done.

---

## Task 4: Update `_fonts.scss` — system font stack, no web fonts

**Files:**
- Modify: `wp-content/themes/salefish-2026/assets/scss/basics/_fonts.scss`

**Step 1: Replace `_fonts.scss` entirely**

```scss
// SaleFish 2026 — Typography
// No @font-face declarations. System fonts load instantly.
// $PR / $PSB / $PB / $PL are kept as aliases so existing page
// SCSS compiles without changes.

$PR:  var(--sf-font-sans);
$PSB: var(--sf-font-sans);
$PB:  var(--sf-font-sans);
$PL:  var(--sf-font-sans);
```

**Step 2: Commit (after Tasks 3 + 4 are done together)**

```bash
git add wp-content/themes/salefish-2026/assets/scss/basics/_variables.scss
git add wp-content/themes/salefish-2026/assets/scss/basics/_fonts.scss
git commit -m "feat: replace SCSS variables with CSS custom property token system"
```

---

## Task 5: Create `_dark-mode.scss` — dark token overrides

**Files:**
- Create: `wp-content/themes/salefish-2026/assets/scss/basics/_dark-mode.scss`

**Step 1: Create the file**

```scss
// SaleFish 2026 — Dark Mode Token Overrides
// Applied when <html class="dark"> is present.
// Toggled by the inline script in header.php + dark mode button.

@layer base {
  .dark {
    --sf-elevate-1: rgba(255, 255, 255, 0.04);
    --sf-elevate-2: rgba(255, 255, 255, 0.10);
    --sf-button-outline: rgba(255, 255, 255, 0);

    --sf-bg:             0 0% 9%;
    --sf-fg:             0 0% 95%;
    --sf-border:         0 0% 25%;

    --sf-card:           0 0% 9%;
    --sf-card-fg:        0 0% 95%;
    --sf-card-border:    0 0% 28%;

    --sf-primary:        252 44% 70%;
    --sf-primary-fg:     0 0% 9%;

    --sf-secondary:      0 0% 16%;
    --sf-secondary-fg:   0 0% 90%;

    --sf-muted:          0 0% 13%;
    --sf-muted-fg:       0 0% 60%;

    --sf-accent:         270 50% 80%;
    --sf-accent-fg:      0 0% 9%;

    --sf-destructive:    0 84% 60%;
    --sf-destructive-fg: 0 0% 100%;

    --sf-ring:           252 44% 75%;

    // Ocean palette — lighter for dark backgrounds
    --sf-ocean:          210 80% 60%;
    --sf-ocean-fg:       0 0% 9%;
    --sf-seaweed:        150 45% 55%;
    --sf-seaweed-fg:     0 0% 9%;
    --sf-clownfish:      48 90% 62%;
    --sf-clownfish-fg:   0 0% 9%;
    --sf-starfish:       45 85% 62%;
    --sf-starfish-fg:    0 0% 9%;
    --sf-coral:          355 70% 65%;
    --sf-coral-fg:       0 0% 9%;
    --sf-salmon:         10 80% 68%;
    --sf-salmon-fg:      0 0% 9%;
    --sf-seahorse:       180 55% 58%;
    --sf-seahorse-fg:    0 0% 9%;

    // Dark shadows are more opaque
    --sf-shadow-sm:  0 1px 2px hsl(0 0% 0% / .40),
                     0 2px 4px hsl(0 0% 0% / .50);
    --sf-shadow:     0 1px 3px hsl(0 0% 0% / .50),
                     0 4px 6px -2px hsl(0 0% 0% / .50);
    --sf-shadow-md:  0 4px 6px -1px hsl(0 0% 0% / .50),
                     0 2px 4px -2px hsl(0 0% 0% / .40);
    --sf-shadow-lg:  0 10px 15px -3px hsl(0 0% 0% / .50),
                     0 4px 6px -4px hsl(0 0% 0% / .40);
  }
}
```

**Step 2: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/basics/_dark-mode.scss
git commit -m "feat: add dark mode token overrides"
```

---

## Task 6: Create `_tokens.scss` — noise texture + gradient helpers

**Files:**
- Create: `wp-content/themes/salefish-2026/assets/scss/basics/_tokens.scss`

**Step 1: Create the file**

```scss
// SaleFish 2026 — Gradient & Texture Helpers
// These are component-level CSS classes (not tokens) for reuse
// across page templates.

@layer components {

  // ── Body gradient (applied in _general.scss on body) ────
  // Defined as custom props so dark mode override is clean.
  :root {
    --sf-body-gradient: radial-gradient(
      ellipse at 50% 0%,
      hsl(240 100% 100%) 0%,
      hsl(240 20% 96%) 100%
    );
    --sf-card-gradient: linear-gradient(
      180deg,
      hsl(240 100% 100%) 0%,
      hsl(240 20% 96%) 100%
    );
    --sf-popover-gradient: linear-gradient(
      180deg,
      hsl(0 0% 100%) 0%,
      hsl(240 20% 97%) 100%
    );
  }

  .dark {
    --sf-body-gradient: radial-gradient(
      ellipse at 50% 0%,
      hsl(0 0% 11%) 0%,
      hsl(0 0% 8%) 100%
    );
    --sf-card-gradient: linear-gradient(
      180deg,
      hsl(0 0% 12%) 0%,
      hsl(0 0% 9%) 100%
    );
    --sf-popover-gradient: linear-gradient(
      180deg,
      hsl(0 0% 14%) 0%,
      hsl(0 0% 10%) 100%
    );
  }

  // ── Gradient utility classes ─────────────────────────────
  .sf-gradient-primary {
    background: linear-gradient(
      135deg,
      hsl(var(--sf-primary)) 0%,
      hsl(var(--sf-accent)) 100%
    );
    color: hsl(var(--sf-primary-fg));
  }

  .sf-gradient-ocean {
    background: linear-gradient(
      135deg,
      hsl(var(--sf-ocean)) 0%,
      hsl(var(--sf-seahorse)) 100%
    );
    color: hsl(var(--sf-ocean-fg));
  }

  .sf-gradient-hero {
    background: linear-gradient(
      160deg,
      hsl(var(--sf-primary) / 0.08) 0%,
      hsl(var(--sf-accent) / 0.05) 50%,
      transparent 100%
    );
  }
}
```

**Step 2: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/basics/_tokens.scss
git commit -m "feat: add gradient and texture token helpers"
```

---

## Task 7: Update `_general.scss` — body, headings, noise texture

**Files:**
- Modify: `wp-content/themes/salefish-2026/assets/scss/basics/_general.scss`

**Step 1: Replace `_general.scss` entirely**

```scss
// SaleFish 2026 — Base element styles

@layer base {
  html {
    scroll-behavior: smooth;
    scrollbar-gutter: stable;
  }

  body {
    color: hsl(var(--sf-fg));
    background: var(--sf-body-gradient);
    background-color: hsl(var(--sf-bg)); // fallback
    font-family: var(--sf-font-sans);
    letter-spacing: -0.011em;
    font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
    overflow-x: hidden;
    position: relative;
    transition: background-color 0.2s ease, color 0.2s ease;
  }

  // Noise texture overlay — premium material feel
  body::before {
    content: '';
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 9998; // below modals (9999) but above everything else
    opacity: 0.025;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 200px 200px;
  }

  .dark body::before {
    opacity: 0.035;
    mix-blend-mode: overlay;
  }

  img,
  svg {
    image-rendering: -webkit-optimize-contrast;
  }

  input {
    -webkit-appearance: none;
    border-radius: 0;
  }

  // ── Headings ──────────────────────────────────────────────
  h1 {
    font-family: var(--sf-font-sans);
    font-weight: 700;
    font-size: 4em;
    line-height: 1.1;
    letter-spacing: -0.01em;

    @include rs550 { font-size: 2.7em; }
  }

  h2 {
    font-family: var(--sf-font-sans);
    font-weight: 700;
    font-size: 3.75em;
    line-height: 1.2;
    letter-spacing: -0.01em;

    @include rs550 { font-size: 2.2em; }
  }

  h3 {
    font-family: var(--sf-font-sans);
    font-weight: 600;
    font-size: 1.05em;
    line-height: 1.5;

    @include rs550 { font-size: 1.1em; }
  }

  p {
    font-family: var(--sf-font-sans);
    font-size: 1.75em;
    line-height: 1.6;

    @include rs550 { font-size: 1.85em; }
  }

  a {
    color: hsl(var(--sf-primary));
    text-decoration: none;
    transition: color 0.15s ease;

    &:hover { color: hsl(var(--sf-primary) / 0.8); }
  }

  // ── Focus ring ────────────────────────────────────────────
  *:focus-visible {
    outline: 2px solid hsl(var(--sf-ring));
    outline-offset: 2px;
    border-radius: 4px;
  }
}

// ── Layout helpers ─────────────────────────────────────────
.max_wrapper {
  max-width: 1800px;
  margin: 0 auto;
}

main {
  overflow-x: hidden;
}

// ── Loading screen ─────────────────────────────────────────
.loading {
  position: fixed;
  top: 0;
  width: 100%;
  height: 100%;
  background: hsl(var(--sf-primary));
  transition: 500ms all ease-in-out;
  z-index: 9999;
  opacity: 1;
  visibility: visible;

  &.active {
    opacity: 0;
    visibility: hidden;
  }
}

// ── Reduced motion ─────────────────────────────────────────
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
```

**Step 2: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/basics/_general.scss
git commit -m "feat: update _general.scss — body gradient, noise texture, system type scale"
```

---

## Task 8: Update `_reusable.scss` — cards, badges, button

**Files:**
- Modify: `wp-content/themes/salefish-2026/assets/scss/basics/_reusable.scss`

**Step 1: Prepend the new component layer to the TOP of `_reusable.scss`**

Add this block before the existing `.button { ... }` rule at line 1:

```scss
// ============================================================
// SaleFish 2026 — Reusable Components
// ============================================================

@layer components {

  // ── Card ───────────────────────────────────────────────────
  .sf-card {
    border-radius: var(--sf-radius-lg);
    background: var(--sf-card-gradient);
    background-color: hsl(var(--sf-card)); // fallback
    // Inner top highlight creates subtle 3D depth
    box-shadow: inset 0 1px 0 hsl(0 0% 100% / 0.7), var(--sf-shadow-sm);
    border: 1px solid hsl(var(--sf-card-border));
    color: hsl(var(--sf-card-fg));
    transition: transform 150ms ease, box-shadow 150ms ease;

    &:hover {
      transform: translateY(-1px);
      box-shadow: inset 0 1px 0 hsl(0 0% 100% / 0.7), var(--sf-shadow-md);
    }
  }

  .dark .sf-card {
    box-shadow: inset 0 1px 0 hsl(0 0% 100% / 0.03), var(--sf-shadow-sm);

    &:hover {
      box-shadow: inset 0 1px 0 hsl(0 0% 100% / 0.03), var(--sf-shadow-md);
    }
  }

  // ── Badge / Chip ───────────────────────────────────────────
  // Usage: <span class="sf-badge">Label</span>
  //        <span class="sf-badge sf-badge--ocean">Info</span>
  .sf-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border-radius: var(--sf-radius);   // 8px
    font-family: var(--sf-font-sans);
    font-size: 12px;
    font-weight: 500;
    line-height: 1;
    padding: 3px 10px;
    white-space: nowrap;
    border: 1px solid transparent;
    transition: opacity 0.15s ease;

    // Default — primary gradient pill
    background: linear-gradient(
      180deg,
      hsl(var(--sf-primary)) 0%,
      hsl(var(--sf-primary) / 0.85) 100%
    );
    color: hsl(var(--sf-primary-fg));
  }

  // Semantic colour variants — auto-adapt to light/dark
  // Pattern: translucent background + matching border tint
  $sf-badge-colors: ocean, seaweed, clownfish, starfish, coral, salmon, seahorse;

  @each $color in $sf-badge-colors {
    .sf-badge--#{$color} {
      background: hsl(var(--sf-#{$color}) / 0.1);
      color: hsl(var(--sf-#{$color}));
      border-color: hsl(var(--sf-#{$color}) / 0.3);
    }
  }

  // Outline variant (no background fill)
  .sf-badge--outline {
    background: transparent;
    color: hsl(var(--sf-fg));
    border-color: hsl(var(--sf-border));
  }

  // Muted / neutral
  .sf-badge--muted {
    background: hsl(var(--sf-muted) / 0.5);
    color: hsl(var(--sf-muted-fg));
    border-color: hsl(var(--sf-border) / 0.5);
  }

  // ── Button (2026 edition) ──────────────────────────────────
  // The old .button class is kept below for backwards compat.
  // New templates should use .sf-btn.
  .sf-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-radius: var(--sf-radius);
    font-family: var(--sf-font-sans);
    font-size: 0.875rem;
    font-weight: 700;
    line-height: 1;
    padding: 10px 20px;
    border: 1px solid transparent;
    cursor: pointer;
    white-space: nowrap;
    text-decoration: none;
    transition: transform 0.1s ease, box-shadow 0.15s ease, opacity 0.15s ease;

    &:active { transform: scale(0.98); }
    &:disabled { opacity: 0.5; pointer-events: none; }

    // Primary
    &,
    &--primary {
      background: hsl(var(--sf-primary));
      color: hsl(var(--sf-primary-fg));
      border-color: hsl(var(--sf-primary) / 1.06);
    }

    &:hover {
      opacity: 0.92;
    }

    // Secondary
    &--secondary {
      background: hsl(var(--sf-secondary));
      color: hsl(var(--sf-secondary-fg));
      border-color: hsl(var(--sf-border));
    }

    // Ghost
    &--ghost {
      background: transparent;
      color: hsl(var(--sf-fg));
      border-color: transparent;

      &:hover {
        background: hsl(var(--sf-muted));
        opacity: 1;
      }
    }

    // Destructive
    &--destructive {
      background: hsl(var(--sf-destructive));
      color: hsl(var(--sf-destructive-fg));
    }
  }

} // end @layer components

// ── Legacy .button ─────────────────────────────────────────
// Kept verbatim so existing templates don't break.
```

The existing `.button { ... }` rule and everything below it stays untouched.

**Step 2: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/basics/_reusable.scss
git commit -m "feat: add sf-card, sf-badge, and sf-btn components to _reusable.scss"
```

---

## Task 9: Update `layouts/header.scss` — dark mode toggle button

**Files:**
- Modify: `wp-content/themes/salefish-2026/assets/scss/layouts/header.scss`

**Step 1: Append to the END of `header.scss`**

```scss
// ── Dark mode toggle ───────────────────────────────────────
.sf-theme-toggle {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: var(--sf-radius);
  border: 1px solid hsl(var(--sf-border) / 0.5);
  background: hsl(var(--sf-secondary) / 0.6);
  color: hsl(var(--sf-fg));
  cursor: pointer;
  flex-shrink: 0;
  transition: background 0.2s ease, border-color 0.2s ease;

  svg {
    width: 18px;
    height: 18px;
    pointer-events: none;
    transition: transform 0.3s ease;
  }

  &:hover {
    background: hsl(var(--sf-muted));
    border-color: hsl(var(--sf-border));
  }

  &:active svg {
    transform: rotate(20deg);
  }

  // Hide the icon that doesn't match the current mode
  .sf-icon-moon { display: block; }
  .sf-icon-sun  { display: none; }
}

.dark .sf-theme-toggle {
  .sf-icon-moon { display: none; }
  .sf-icon-sun  { display: block; }
}
```

**Step 2: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/layouts/header.scss
git commit -m "feat: add dark mode toggle button styles to header"
```

---

## Task 10: Update `app.scss` — wire in new files

**Files:**
- Modify: `wp-content/themes/salefish-2026/assets/scss/app.scss`

**Step 1: Replace the `// Tools` and `// Basics` block**

The current `app.scss` starts with:
```scss
// Tools
@import "tools/reset";
...
// Basics
@import "basics/mixin";
@import "basics/fonts";
@import "basics/variables";
@import "basics/general";
@import "basics/reusable";
```

Replace the `// Basics` section with:
```scss
// Basics
@import "basics/mixin";
@import "basics/fonts";
@import "basics/variables";
@import "basics/dark-mode";
@import "basics/tokens";
@import "basics/general";
@import "basics/reusable";
```

The `// Tools`, `// Layouts`, and `// Pages` sections stay unchanged.

**Step 2: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/app.scss
git commit -m "feat: add dark-mode and tokens imports to app.scss"
```

---

## Task 11: Update `header.php` — dark mode script + toggle button

**Files:**
- Modify: `wp-content/themes/salefish-2026/header.php`

**Step 1: Remove the hardcoded CSS link**

Find and delete this line (around line 28 in header.php) — the CSS is now enqueued by `functions.php`:
```html
<link rel="stylesheet"
    href="<?php bloginfo('template_directory'); ?>/dest/app.css">
```

**Step 2: Add the dark mode inline script**

Immediately before `</head>`, add:
```html
	<script>
		// SaleFish 2026 — dark mode init (must run before first paint to prevent flash)
		(function () {
			var saved = localStorage.getItem('sf-theme');
			var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
			if (saved === 'dark' || (!saved && prefersDark)) {
				document.documentElement.classList.add('dark');
			}
		}());
	</script>
</head>
```

**Step 3: Add the toggle button to the nav**

In the `<nav>` element, find the `<div class="menu">` block (around line 137) and add the toggle button just before it:

```html
			<button
				class="sf-theme-toggle"
				id="sf-theme-toggle"
				aria-label="Toggle dark mode"
				type="button">
				<!-- Moon icon (shown in light mode) -->
				<svg class="sf-icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
				</svg>
				<!-- Sun icon (shown in dark mode) -->
				<svg class="sf-icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="5"/>
					<line x1="12" y1="1" x2="12" y2="3"/>
					<line x1="12" y1="21" x2="12" y2="23"/>
					<line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
					<line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
					<line x1="1" y1="12" x2="3" y2="12"/>
					<line x1="21" y1="12" x2="23" y2="12"/>
					<line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
					<line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
				</svg>
			</button>
```

**Step 4: Add the toggle JS at the bottom of `header.php`**

After the closing `</header>` tag, add:

```html
<script>
	(function () {
		var btn = document.getElementById('sf-theme-toggle');
		if (!btn) return;
		btn.addEventListener('click', function () {
			var html = document.documentElement;
			var isDark = html.classList.toggle('dark');
			localStorage.setItem('sf-theme', isDark ? 'dark' : 'light');
			btn.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
		});
	}());
</script>
```

**Step 5: Commit**

```bash
git add wp-content/themes/salefish-2026/header.php
git commit -m "feat: add dark mode inline init script and toggle button to header.php"
```

---

## Task 12: Build and verify

**Step 1: Install dependencies (first time only)**

```bash
cd wp-content/themes/salefish-2026
npm install
```

**Step 2: Run a development build**

```bash
npm run dev
```

Expected: No errors. `dest/app.css` and `dest/app.js` are updated. Watch for any SCSS compile errors — the most common is an undefined SCSS variable. If you see `Undefined variable: "$PB"`, check that `_fonts.scss` is imported before `_general.scss` in `app.scss`.

**Step 3: Run a production build**

```bash
npm run prod
```

Expected: Minified `dest/app.css` with no errors.

**Step 4: FTP the theme to the server**

Using the FTP credentials stored in Keychain (see memory: reference_ftp_credentials.md), upload the entire `salefish-2026/` folder.

**Step 5: Preview in WP Admin**

1. Go to WP Admin → Appearance → Themes
2. Hover over "SaleFish 2026" → click "Live Preview"
3. Check the following in the preview:
   - [ ] Body has the faint radial gradient (subtle, not garish)
   - [ ] Headings render in a clean system sans-serif (not Poppins)
   - [ ] Dark mode toggle button appears in the nav (moon icon)
   - [ ] Clicking the toggle switches to dark — background goes dark, text goes light
   - [ ] Refreshing while in dark mode stays dark (localStorage is working)
   - [ ] No flash of unstyled white on page load in dark mode (inline script is working)
   - [ ] `.sf-card` elements have 26px radius and inner highlight (test on newsroom/blog pages)
   - [ ] `.sf-badge` elements display correctly (check any page using the old `.button` class — it should still work)

**Step 6: Commit the compiled assets**

```bash
cd wp-content/themes/salefish-2026
git add dest/app.css dest/app.js
git commit -m "build: compile SaleFish 2026 theme assets"
```

---

## Task 13: Go live

Only when the Live Preview looks correct in all checks above:

1. WP Admin → Appearance → Themes → SaleFish 2026 → **Activate**
2. Visit the live site and repeat the visual checks from Task 12 Step 5
3. Check the browser console for JS errors (none expected)
4. Verify dark mode toggle works for a logged-out visitor (open incognito)

---

## Notes for future phases

- Page-level SCSS (`pages/home.scss`, `pages/our_story.scss`, etc.) still uses old `$dark_purple`, `$gold`, etc. These are aliased to 2026 tokens in `_variables.scss` so they compile — but page-by-page migration to `hsl(var(--sf-*))` syntax is the next phase.
- The legacy `.button` class in `_reusable.scss` is kept intact. Migrate page templates to `.sf-btn` over time.
- Consider adding `prefers-color-scheme` media query to the dark mode SCSS as a secondary mechanism for users without JS.
