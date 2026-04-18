# SaleFish 2026 Theme — Full Redesign Design

**Goal:** Rebuild every PHP template and SCSS file in the salefish-2026 theme so the site visually matches Plinthra (glass cards, gradient backgrounds, Plinthra typography and colour tokens) while keeping all ACF field calls identical.

**Approach:** Option B — full template + SCSS rebuild. Replace all clip-path colour bands, legacy `$dark_purple`/`$gold` variables, and old layout patterns with Plinthra's glass card sections and design token system. PHP templates change structurally; ACF field names stay the same.

**Stack:** WordPress classic theme, PHP templates, ACF, Laravel Mix 6 (Sass + webpack), CSS custom properties (hsl channel format), `lucide` SVG icons inlined in PHP.

---

## Design Tokens (already in place)

CSS custom properties on `:root` and `.dark` are already defined in `_variables.scss` and `_dark-mode.scss`. All new markup uses only these tokens — no legacy `$gold`, `$dark_purple`, `$light_purple`, etc.

Key tokens used throughout:
- `--sf-primary` / `--sf-primary-fg` — brand indigo
- `--sf-accent` — teal accent
- `--sf-body-gradient` / `--sf-card-gradient` — gradient backgrounds
- `--sf-card` / `--sf-card-border` / `--sf-card-fg` — glass card surface
- `--sf-fg` / `--sf-muted-fg` — text
- `--sf-border` / `--sf-input` / `--sf-ring` — borders and form elements
- `--sf-radius` / `--sf-radius-lg` — border radii
- `--sf-shadow-sm` / `--sf-shadow-md` — shadows
- Semantic palette: `--sf-ocean`, `--sf-seaweed`, `--sf-clownfish`, `--sf-coral`, `--sf-salmon`, `--sf-seahorse`, `--sf-starfish`

---

## Section 1: Visual Architecture

- **Background:** `var(--sf-body-gradient)` on `<body>`. No full-page colour bands.
- **Sections:** Self-contained rectangular blocks with generous padding (`80px 40px` desktop, `40px 20px` mobile). No `clip-path` polygons anywhere.
- **Container:** `max-width: 1200px; margin: 0 auto` on all inner content.
- **Dark mode:** System preference via `prefers-color-scheme` on first load; user override persisted to `localStorage('sf-theme')`. `.dark` class toggled on `<html>`. Inline `<script>` before `</head>` prevents FOUC.
- **Typography:** `var(--sf-font-sans)` (Inter/system stack) for all UI. Legacy font variables (`$PSB`, `$PL`, etc.) replaced entirely.
- **No legacy remnants:** All `$gold`, `$dark_purple`, `$light_purple`, `$gray` usages removed from templates and SCSS.

---

## Section 2: Header / Navigation

**Structure (left → right):**
```
[SaleFish Logo]   [Features · Newsroom · Partners · Contact]   [Login]   [── Languages icon · SunMoon icon ──]
```

- Sticky header, `backdrop-filter: blur(12px)`, `background: hsl(var(--sf-background) / 0.85)`
- Bottom edge: `1px solid hsl(var(--sf-border))`
- Light mode: translucent white. Dark mode: translucent dark surface.
- Login: `sf-btn--ghost` or styled anchor, before the icon cluster
- Lang + theme cluster: small bordered pill group, far right, visually separated from Login

**Icons (matching Plinthra exactly):**
- Language selector: `Languages` icon from lucide (SVG inlined, 20×20px)
- Theme toggle: `SunMoon` icon from lucide (single icon for both states)

**Theme toggle behaviour:**
- Click → toggle `.dark` on `<html>` + persist to `localStorage('sf-theme')`
- No page reload

**Mobile (≤768px):**
- Hamburger collapses nav links into a slide-in drawer
- Logo + Login + lang/theme cluster remain in top bar
- Drawer background: `hsl(var(--sf-card))` with border

---

## Section 3: Homepage

**Hero**
- Full-width, `var(--sf-body-gradient)` background + subtle noise texture overlay
- Large heading (`font-size: clamp(2rem, 5vw, 3.5rem)`), subhead, two CTAs: `sf-btn` primary + `sf-btn--ghost`
- No clip-path; clean rectangular with `padding: 100px 40px`

**Feature cards row**
- 3-column grid desktop → 1-column mobile
- Each: `.sf-card` (glass gradient, border, hover lift), badge chip at top, heading, body copy
- ACF fields: same field names, new markup wrapper

**Stats strip**
- Full-width `hsl(var(--sf-primary))` background, 3–4 large numbers with labels
- No cards, white text, bold numerals

**Product sections**
- Alternating image-left/text-right at desktop, stacking on mobile
- `var(--sf-card-gradient)` background per section block, `border-radius: var(--sf-radius-lg)`
- Replaces all clip-path diagonal colour bands

**Testimonials**
- Horizontal scroll row of `.sf-card` quotes
- Avatar, quote text, name/title, `.sf-badge` chip for role/company

**CTA strip**
- `sf-gradient-primary` background, centred heading + single `sf-btn`
- Replaces old dark purple footer CTA band

**Container:** `max-width: 1200px; margin: 0 auto; padding: 80px 40px` on all sections (40px on mobile).

---

## Section 4: Inner Pages

**Features page**
- Smaller hero (same pattern, less padding)
- Feature sections: alternating image/text layout
- Feature detail cards: `.sf-card` grid

**Newsroom archive**
- Card grid: 3 columns desktop → 1 mobile
- Each post: `.sf-card`, featured image with rounded top, category `.sf-badge`, heading, excerpt, date
- Pagination: `sf-btn--ghost`

**Single post**
- Centred single-column (`max-width: 720px`)
- Header: category badge + title + date + author
- Body: prose styles — headings, blockquotes styled with `border-left: 3px solid hsl(var(--sf-primary))`
- No sidebar

**Partners page**
- Logo grid: `opacity: 0.6`, full opacity on hover
- Intro hero, optional partner tiers as card rows

**Contact page**
- Two-column layout: contact info `.sf-card` left, form `.sf-card` right
- Inputs: `hsl(var(--sf-input))` background, `hsl(var(--sf-border))` border, focus ring `hsl(var(--sf-ring))`
- Submit: `sf-btn` primary
- Replaces old clip-path purple form

**Terms / Privacy**
- Same as single post: centred prose column, no sidebar

**Footer**
- `hsl(var(--sf-primary))` background
- 4-column links grid + logo + social icons + copyright
- Replaces current footer styling
