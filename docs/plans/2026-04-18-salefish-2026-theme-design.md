# SaleFish 2026 Theme — Design Document

**Date:** 2026-04-18  
**Status:** Approved  
**Approach:** Option A — new standalone theme, copy + restyle

---

## Overview

Create a new WordPress theme `salefish-2026` that ports the Plinthra design system to the SaleFish corporate marketing site. The existing `salefish` theme remains live and untouched during development. Switch is a single click in WP Admin once the new theme is ready.

**Goal:** The site should look and feel light, fast, and cutting-edge — matching the premium iOS-inspired aesthetic of Plinthra.

---

## Theme Structure

```
wp-content/themes/salefish-2026/
  style.css                          Theme header (Theme Name: SaleFish 2026)
  functions.php                      Enqueue dest/app.css + dest/app.js (deferred)
  header.php                         + dark mode inline script before </head>
  footer.php                         + dark mode toggle button in nav
  [all other .php files]             Copied verbatim from salefish/
  assets/
    scss/
      app.scss                       Entry point — same imports, new files added
      basics/
        _variables.scss              CSS custom property declarations (:root + .dark)
        _dark-mode.scss              NEW — .dark overrides for all tokens
        _tokens.scss                 NEW — radius, shadow, elevation, noise vars
        _fonts.scss                  Updated — system font stack, no web fonts
        _general.scss                Updated — body gradient, letter spacing
        _reusable.scss               Updated — card, badge, chip, button styles
        _mixin.scss                  Unchanged
        hamburgers-settings.scss     Unchanged
      layouts/
        _header.scss                 Updated — dark mode toggle button
        _footer.scss                 Unchanged
      pages/                         All page SCSS unchanged initially
      tools/                         Unchanged
  dest/
    app.css                          Compiled output
    app.js                           Compiled output
  package.json                       Unchanged
  webpack.mix.js                     Unchanged
```

---

## 2026 WordPress Best Practices

- **CSS custom properties** for all design tokens — no hardcoded color values in component SCSS
- **CSS `@layer base, components, utilities`** for cascade management
- **Dark mode:** `.dark` class on `<html>`, set by an inline `<script>` in `<head>` before first paint (zero FOUC). Reads `localStorage('sf-theme')`, falls back to `prefers-color-scheme`.
- **No new jQuery** — dark mode toggle and any new JS is vanilla
- **`strategy: defer`** on enqueued JS bundle in `functions.php`
- **`prefers-reduced-motion`** respected in all transition/animation CSS
- **System font stack** — zero font load latency, no Google Fonts

---

## Design Tokens

### Colour — Light Mode (`:root`)

| Variable | HSL Value | Role |
|---|---|---|
| `--sf-bg` | `240 100% 99%` | Page background |
| `--sf-fg` | `0 0% 9%` | Body text |
| `--sf-primary` | `256 51% 36%` | Brand purple — CTAs, links, focus rings |
| `--sf-primary-fg` | `0 0% 100%` | Text on primary |
| `--sf-secondary` | `240 20% 94%` | Secondary surfaces |
| `--sf-secondary-fg` | `210 20% 15%` | Text on secondary |
| `--sf-muted` | `240 20% 95%` | Muted backgrounds |
| `--sf-muted-fg` | `210 15% 35%` | Muted text |
| `--sf-accent` | `270 50% 75%` | Accent purple |
| `--sf-border` | `240 20% 75%` | Borders |
| `--sf-card` | `240 100% 99%` | Card surface |
| `--sf-card-border` | `240 20% 72%` | Card border |
| `--sf-ring` | `256 51% 30%` | Focus ring |
| `--sf-destructive` | `0 84% 60%` | Error / danger |

### Ocean Palette — Light Mode

| Variable | HSL Value | Semantic |
|---|---|---|
| `--sf-ocean` | `210 100% 28%` | Info / in-progress |
| `--sf-seaweed` | `150 85% 25%` | Success / active |
| `--sf-clownfish` | `48 100% 26%` | Warning / medium |
| `--sf-starfish` | `45 100% 26%` | Pending / attention |
| `--sf-coral` | `355 95% 30%` | Danger / urgent |
| `--sf-salmon` | `10 100% 32%` | Special / homeowner |
| `--sf-seahorse` | `180 90% 25%` | Teal / scheduled |

### Colour — Dark Mode (`.dark`)

| Variable | HSL Value |
|---|---|
| `--sf-bg` | `0 0% 9%` |
| `--sf-fg` | `0 0% 95%` |
| `--sf-primary` | `252 44% 70%` |
| `--sf-primary-fg` | `0 0% 9%` |
| `--sf-secondary` | `0 0% 16%` |
| `--sf-muted` | `0 0% 13%` |
| `--sf-muted-fg` | `0 0% 60%` |
| `--sf-border` | `0 0% 25%` |
| `--sf-card` | `0 0% 9%` |
| `--sf-card-border` | `0 0% 28%` |

Ocean palette flips to light versions in dark mode (e.g. ocean → `210 80% 60%`).

### Radius

| Variable | Value | Use |
|---|---|---|
| `--sf-radius-sm` | `6px` | Tags, small inputs |
| `--sf-radius` | `8px` | Buttons, badges, chips |
| `--sf-radius-lg` | `26px` | Cards, modals, panels |

### Shadows (light / dark variants)

```css
--sf-shadow-sm:  0 1px 2px hsl(0 0% 0% / .05), 0 2px 4px hsl(0 0% 0% / .08);
--sf-shadow:     0 1px 3px hsl(0 0% 0% / .08), 0 4px 6px -2px hsl(0 0% 0% / .08);
--sf-shadow-md:  0 4px 6px -1px hsl(0 0% 0% / .08), 0 2px 4px -2px hsl(0 0% 0% / .05);
--sf-shadow-lg:  0 10px 15px -3px hsl(0 0% 0% / .08), 0 4px 6px -4px hsl(0 0% 0% / .05);
```

Dark mode shadows use `0.4–0.5` opacity instead of `0.05–0.08`.

---

## Typography

```css
--sf-font-sans:    -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI',
                   Roboto, 'Helvetica Neue', Arial, sans-serif;
--sf-font-serif:   'New York', Georgia, serif;
--sf-font-mono:    'SF Mono', Monaco, Consolas, 'Courier New', monospace;
```

- Body: `font-family: var(--sf-font-sans)`, `letter-spacing: -0.011em`, `font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11'`
- Headings `h1–h3`: `letter-spacing: -0.01em`, `font-weight: 700`
- Zero external font requests → instant text render

---

## Gradients & Texture

### Body background
```css
/* Light */
background: radial-gradient(ellipse at 50% 0%, hsl(240 100% 100%) 0%, hsl(240 20% 96%) 100%);

/* Dark */
background: radial-gradient(ellipse at 50% 0%, hsl(0 0% 11%) 0%, hsl(0 0% 8%) 100%);
```

### Noise texture overlay
SVG `fractalNoise` data URI applied via `body::before`, `position: fixed`, `pointer-events: none`, opacity `0.025` (light) / `0.035` (dark). Creates premium material feel.

### Card gradient
```css
/* Light */
background: linear-gradient(180deg, hsl(240 100% 100%) 0%, hsl(240 20% 96%) 100%);

/* Dark */
background: linear-gradient(180deg, hsl(0 0% 12%) 0%, hsl(0 0% 9%) 100%);
```

---

## Card Component

```scss
.sf-card {
  border-radius: var(--sf-radius-lg);        // 26px
  background: var(--sf-card-gradient);
  box-shadow: inset 0 1px 0 hsl(0 0% 100% / 0.7), var(--sf-shadow-sm);  // inner highlight
  border: none;
  transition: transform 150ms ease, box-shadow 150ms ease;

  &:hover {
    transform: translateY(-1px);
    box-shadow: inset 0 1px 0 hsl(0 0% 100% / 0.7), var(--sf-shadow-md);
  }
}

.dark .sf-card {
  box-shadow: inset 0 1px 0 hsl(0 0% 100% / 0.03), var(--sf-shadow-sm);
}
```

---

## Chip / Badge Component

```scss
.sf-badge {
  display: inline-flex;
  align-items: center;
  border-radius: var(--sf-radius);           // 8px
  font-size: 12px;
  font-weight: 500;
  padding: 2px 10px;
  white-space: nowrap;
  border: 1px solid transparent;

  // Default — primary gradient
  background: linear-gradient(180deg, hsl(var(--sf-primary)) 0%, hsl(var(--sf-primary) / .85) 100%);
  color: hsl(var(--sf-primary-fg));
}

// Semantic colour modifiers
@each $name in ocean, seaweed, clownfish, starfish, coral, salmon, seahorse {
  .sf-badge--#{$name} {
    background: hsl(var(--sf-#{$name}) / 0.1);
    color: hsl(var(--sf-#{$name}));
    border-color: hsl(var(--sf-#{$name}) / 0.3);
  }
}
```

---

## Dark Mode Toggle

### Inline script in `header.php` (before `</head>`, not enqueued)
```html
<script>
(function(){
  var t = localStorage.getItem('sf-theme');
  if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
  }
})();
</script>
```

### Toggle button in nav
- Sun/moon inline SVG icon
- Vanilla JS click handler: flips `.dark` on `<html>`, writes to `localStorage`
- `aria-label` updated dynamically

---

## Preview Workflow

1. Build locally: `cd wp-content/themes/salefish-2026 && npm install && npm run dev`
2. FTP the `salefish-2026/` folder to the server (same credentials as `salefish/`)
3. WP Admin → Appearance → Themes → SaleFish 2026 → **Live Preview** (only admin sees it)
4. Iterate: edit SCSS → `npm run dev` → FTP `dest/app.css` → refresh preview
5. Go live: Appearance → Themes → Activate SaleFish 2026

---

## Out of Scope (this phase)

- Changes to page content or WordPress page templates
- Full Site Editing / block theme conversion
- New page layouts or sections
- JS animations beyond what SCSS handles
