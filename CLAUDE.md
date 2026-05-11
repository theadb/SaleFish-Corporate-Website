# SaleFish Marketing Website — Claude Instructions

---

## Code Standards

All code written for this project must adhere to the following non-negotiable standards:

- **Latest industry standards** — use modern HTML5, CSS3/4, ES2022+ syntax; no legacy patterns without explicit reason
- **Best practices** — semantic HTML, accessible markup, clean separation of concerns, DRY/YAGNI principles
- **Security** — sanitize and escape all output, validate all input server-side, never expose credentials, use nonces on write operations, follow OWASP guidelines
- **Performance** — minimise layout thrash (avoid forced reflow in loops), use passive listeners where possible, defer non-critical work, prefer CSS over JS for visual effects, avoid memory leaks
- **No jQuery** — fully removed from the codebase. All new code must be vanilla JS. Do not re-introduce jQuery under any circumstances
- **`npm run prod` only** — never deploy `dev` builds. Dev builds are ~5× larger
- **Fix safety** — before implementing any fix, identify every handler, listener, or code path that touches the affected element or state. Confirm the fix does not break existing behaviour on desktop, mobile, and touch. If a fix to one part of the system could affect another (e.g. event propagation, shared state, CSS specificity), trace the full interaction before writing code

---

## Deployment: always use FTP via lftp (never Cyberduck)

After any build, upload changed files directly with lftp. Credentials are stored in the macOS keychain and retrieved at deploy time — never hardcoded.

### Get credentials
```bash
FTP_PASS=$(security find-internet-password -s ftp.salefish.app -a "andrewdb@salefish.app" -w)
```

### Upload files
```bash
lftp -u "andrewdb@salefish.app,$FTP_PASS" ftp://ftp.salefish.app <<'EOF'
set ftp:ssl-allow no
set net:timeout 30
put -O /public_html/path/to/remote/dir /local/path/to/file
bye
EOF
```

### Verify uploads (always do this after uploading)
```bash
lftp -u "andrewdb@salefish.app,$FTP_PASS" ftp://ftp.salefish.app <<'EOF'
set ftp:ssl-allow no
set net:timeout 30
ls /public_html/path/to/file
bye
EOF
```
Check that the timestamp is today and the byte size matches the local file (`wc -c localfile`).

### After uploading — REQUIRED: purge LiteSpeed cache

> **This step is mandatory. Skipping it means visitors see stale cached files.**

```
WP Admin → LiteSpeed Cache → Toolbox → Purge All
```

No deploy is complete until the cache is purged.

### Critical lftp rule
**NEVER use `--delete` or `mirror --delete`** — this previously wiped the live server and caused a full outage. Site had to be restored from UpdraftPlus backup.

---

## Git workflow: local → GitHub → live (never delete anything)

Every change must flow through all three in order. **Never force-push, never delete branches, never use `git clean` or `git reset --hard`.**

### Full deploy sequence
```bash
# 1. Stage and commit
git add <specific files>          # never `git add -A` — review what you're staging
git commit -m "description"

# 2. Push to GitHub (source of truth)
git push origin main

# 3. Build production assets
cd wp-content/themes/salefish && npm run prod && cd ../../..

# 4. FTP upload changed files to live server (see FTP section above)

# 5. Verify uploads (byte-size check)

# 6. Purge LiteSpeed cache  ← REQUIRED — deploy is not complete until this is done
```

### Rules
- **Commit before FTP** — GitHub is the source of truth. Local + GitHub + live must always match.
- **Never delete files** from the remote server via FTP. If a file needs removing, do it intentionally and commit the removal first.
- **Never `git reset --hard`** or `git clean` — ask if unsure.
- **Never force-push** (`git push --force`) to main.
- **Never commit sensitive files** — `.env`, `wp-config.php`, `config.local.php` are excluded and must stay excluded.
- Compiled `dest/` files ARE committed — they're served directly by WordPress with no build step on the server.

---

## Project structure

| What | Local path | Remote path |
|------|-----------|-------------|
| Theme root | `wp-content/themes/salefish/` | `/public_html/wp-content/themes/salefish/` |
| Compiled CSS/JS | `wp-content/themes/salefish/dest/` | `/public_html/wp-content/themes/salefish/dest/` |
| Service worker | `sw.js` (repo root) | `/public_html/sw.js` |
| cPanel | — | `https://salefish.app:2083` |
| Remote root | — | `/public_html/` |

Never upload: `.claude/`, `node_modules/`, `.DS_Store`, `.git/`, `wp-config.php`.

### SCSS structure (`assets/scss/`)

| Directory | Contents |
|-----------|----------|
| `basics/` | `_general.scss`, `_reusable.scss`, `_sf2026-compat.scss`, `_variables.scss`, `_mixin.scss`, `_fonts.scss` |
| `layouts/` | `header.scss`, `footer.scss` |
| `pages/` | One file per page, all kebab-case: `home.scss`, `blog.scss`, `our-story.scss`, `partners.scss`, `contact-us.scss`, `single-post.scss`, `awards.scss`, `thank-you.scss`, `terms-of-use.scss` |

**Design tokens** (`_sf2026-compat.scss`): CSS custom properties bridged from SCSS variables:
- `--sf-color-gold` ← `$gold` = `#edbb85` (CTA buttons, eyebrows)
- `--sf-color-body` ← `$black` = `#484848` (body text)
- `--sf-color-primary` ← `$dark_purple`
- `$light_purple` = `#7560B8` (darkened from `#9D90D3` for WCAG AA contrast 5.1:1)
- `--sf-radius-sm: 0.375rem` / `--sf-radius: 0.5rem` / `--sf-radius-lg: 1.625rem`
- `--sf-shadow`, `--sf-shadow-sm`, `--sf-shadow-md`, `--sf-shadow-lg`

### JavaScript structure (`assets/js/`)

Entry points compiled by Laravel Mix into `dest/`:
- `app.js` → `dest/app.js` (global, all pages; includes `general.js`)
- `pages/home.js` → `dest/pages/home.js`
- `pages/blog.js` → `dest/pages/blog.js`
- `pages/contact_us.js` → `dest/pages/contact_us.js`
- `pages/content.js` → `dest/pages/content.js`
- `pages/single_post.js` → `dest/pages/single_post.js`

### Page templates

| Page | Template |
|------|----------|
| US Homepage | `page-homepage.php` |
| AU Homepage | `page-homepage-au.php` |
| DE Homepage | `page-homepage-de.php` |
| TR Homepage | `page-homepage-tr.php` |
| Our Story | `page-our-story.php` |
| Partners | `page-partners.php` |
| Blog listing | `page-blog.php` |
| Blog category filter | `page-blog-filter.php` |
| Contact | `page-contact-us.php` |
| TR Contact | `page-tr-contact.php` |
| Awards | `page-awards.php` |
| Success | `page-success.php` |
| Thank You for Registering | `page-thank-you-for-registering.php` |
| Privacy Policy | `page-privacy-policy.php` |
| Terms of Use | `page-terms-of-use.php` |
| Single Blog Post | `single-post.php` |

**Locale detection**: `$_SERVER['REQUEST_URI']` parsed in `header.php` to set `$_sf_locale` (au/de/tr); locale-specific header variant served server-side.

**Light header pages**: stored in `$_sf_light_header` array in `header.php` (e.g. thank-you page). All other pages use dark header.

---

## Build

Always build with `npm run prod` (never `dev`) before deploying:

```bash
cd "wp-content/themes/salefish"
npm run prod
```

The production build minifies everything and keeps file sizes small. Dev builds are ~5× larger and must never be deployed.

---

## WordPress functions and conventions

**Theme function prefix**: `salefish_` — the old `_pc_` prefix was legacy starter theme residue; it has been fully renamed. Do not introduce new `_pc_` functions.

**Key custom functions** (`functions.php`):
- `sf_picture($url, $args)` — **use this for all images**. Generates `<picture>` with AVIF srcset (`-320w`, `-800w`, `-1280w`, `-1920w`, `-2560w` variants). Defaults to `loading="lazy"`. Supports `fetchpriority`.
- `sf_avif_valid($path)` — filters out HEIC-branded `.avif` files from iOS uploads (broken images).
- `sf_preload_hero_image($url, $sizes)` — emits `<link rel="preload" as="image" type="image/avif">` in `<head>` for LCP images. Called on homepage, our-story, partners, awards, success pages.
- `load_more_post()` — AJAX handler for blog Load More. **No nonce** (intentional — public read-only endpoint; nonce breaks with cached HTML older than 12–24h). Registered on both `wp_ajax_` and `wp_ajax_nopriv_`.
- HTTP security headers via `send_headers` hook: `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy`.

**Always use `WP_Query` for paginated queries** — not `get_posts()`. `WP_Query` returns `max_num_pages`; `get_posts()` does not.

**Never use relative `include_once("partials/...")` paths** — always use `get_template_part()`.

---

## Modal system

Both modals (registration + partner) live in `<template id="sf-modals-template">` in `header.php`. The template is **not** in the live DOM — `sfEnsureModals()` in `general.js` clones it on first user interaction.

After cloning, `sfReplaceLucide(root)` must be called on the modal root to render SVG close buttons.

Parsley validation initialises lazily when the modal first opens — **not** at `DOMContentLoaded`.

Form submit handlers are document-delegated so they work on freshly-injected forms.

**Close handlers** are registered with `{ capture: true }` to fire before Cloudflare Turnstile's capture-phase listeners.

**Key JS functions**:
- `sfRegModalOpen()` / `sfRegModalClose()` — includes focus trap; returns focus to trigger on close
- `sfPartnerModalOpen()` / `sfPartnerModalClose()`
- `sfTrackConversion(type, location)` — fires GA4 `generate_lead` + LinkedIn pixel. `type`: `demo_request` / `partner_inquiry` / `agent_inquiry`. `location`: `modal` / `inline_form`

---

## Cloudflare Turnstile

- **Invisible mode** — no visible checkbox, token generated silently
- Lazy-loaded: script not fetched until user hovers/touches/focuses a `[data-sf-modal]` button
- Auto-load on contact form pages only (when `.cf-turnstile` exists outside modals)
- `<link rel="preconnect" href="https://challenges.cloudflare.com">` in `<head>`
- Server-side validation of `cf-turnstile-response` is required on all form POST handlers

---

## Performance — what to do

- **`touch-action: manipulation`** on all interactive elements — removes 300ms iOS tap delay globally (already in `_general.scss` for `a`, `button`, `.button`; add to custom divs/spans that handle touch)
- **`pointerdown` handlers** for instant-response on mobile — hamburger, modal close buttons, and language picker all use `pointerdown` + `click` dual-handler pattern; guard with `if (e.pointerType === 'mouse') return;`
- **Scroll-lock pattern**: `position: fixed; top: -${scrollY}px; width: 100%` — **not** `overflow: hidden` on body (iOS Safari resets `scrollY` to 0 on unlock)
- **`MutationObserver`** over `setInterval` for waiting on dynamic DOM elements (e.g. Tidio iframe injection)
- **`IntersectionObserver`** to pause off-screen animations (hero slideshows, text rotator)
- **`prefers-reduced-motion`** — skip JS-driven animations entirely when user has reduced motion enabled
- **Hero images**: use `sf_picture()` + `sf_preload_hero_image()` + `fetchpriority="high"` + `sizes="100vw"` for LCP images
- **AVIF encoding**: use `avifenc` — never `ffmpeg libsvtav1` for images with transparency (ffmpeg strips alpha channel, produces black backgrounds)
- **GTM** deferred to 4 seconds after `window.load` (not inline)
- **Image skeleton shimmer**: CSS shimmer on `img[loading="lazy"]:not(.sf-img-loaded)` — JS adds `.sf-img-loaded` on `load` event
- **Builder logo marquee**: pure CSS `@keyframes` (no RAF). `will-change: transform` + `backface-visibility: hidden` on `.builders_track`; `contain: paint` on `.builders` section
- **`will-change` policy**: only on continuously-animating elements (e.g. marquee track). Never on hover-only elements — wastes GPU compositor memory. Use `will-change: auto` to cancel an inherited rule

---

## Performance — what never to do

| Anti-pattern | Why |
|---|---|
| `backdrop-filter: blur()` on `position: fixed` | Chrome blanks out content on fast upward scroll (known compositor bug) |
| `transition: height` on the header | Full layout reflow on every scroll tick — snap instantly |
| `will-change` on hover-only elements | Permanently promotes GPU layer, wastes memory on every page |
| `text-wrap: balance` on h2–h6 | O(log n) layout iterations per heading repaint — causes scroll/interaction freeze on content-heavy pages |
| `overflow: hidden` on `body` for scroll-lock | iOS Safari resets `window.scrollY` to 0 on unlock |
| `Promise.prototype.finally()` in inline scripts | Unsupported in iOS Safari < 11.1; leaves `isLoading` permanently stuck |
| `content-visibility: auto` on the footer | Conflicts with reveal animations — previously tried and removed |
| Initialising Parsley at `DOMContentLoaded` | Parses all forms eagerly; initialise lazily when each modal first opens |
| `get_posts()` for paginated blog queries | No `max_num_pages` available |
| Animated orbs / infinite keyframe animations in background | Use static gradients instead (see `thank-you.scss`, `terms-of-use.scss`) |
| `setInterval` polling for dynamic elements | Use `MutationObserver` |

---

## CSS conventions

**Two button classes cover all CTAs**:
- `.button` — primary gold CTA (`<a>`, `<button>`, `<input type="submit">`)
- `.sf-cta-link` — secondary text-link arrow CTA

**Hover standard for `.button`**: `translateY(-2px)` lift + `var(--sf-shadow-sm)` + `brightness(1.06)`

**Arrow CTAs**: `::after` pseudo with `transform: translateX(4px)` on hover, `transition: transform 0.2s ease`. Pseudo must be `display: inline-block` for `transform` to work.

**Card hover**: `translateY(-3px)` lift. Do not use `scale()` on cards — `overflow: hidden` parents clip it. Scale only `img` inside containers with room to expand.

**Expanding underline nav links**: `::after scaleX(0) → scaleX(1)`, `transform-origin: center`.

**No `text-transform: uppercase` in HTML** — use CSS only. Acronyms (ACF, URL, etc.) are preserved as-is.

**Heading hierarchy**: eyebrow labels use `<p class="eyebrow">`, not `<h3>`. One `<h1>` per page.

---

## Scroll-reveal / AOS — current state (important)

The reveal system has been fully disabled. Key facts:

- **No CSS hides anything by default** — all elements render at natural opacity from first paint
- `[data-aos]`, `[data-reveal]`, `.sf-fade-pre` attributes/classes still exist in HTML but are all inert
- `_general.scss` overrides: `[data-aos] { opacity: 1 !important; transform: none !important; transition: none !important; }` and `[data-reveal] { opacity: 1 !important; visibility: visible !important; transform: none !important; }`
- **Do not add new `data-aos`, `data-aos-delay`, `data-fade-dir` attributes** expecting animation — they will do nothing
- `header.php` has a `DOMContentLoaded` stub that strips `.sf-fade-pre` from stale cached HTML

---

## Accessibility

- All pages: `<main id="main-content">` matches skip link `<a href="#main-content">`
- Modals: `role="dialog" aria-modal="true" aria-labelledby`; focus trapped; returned to trigger on close
- Blog filter buttons: `aria-pressed`
- Blog card links: `aria-label="{{ post title }}"`
- Social links with `target="_blank"`: `aria-label` includes "(opens in new tab)"
- DE/TR floating menus: `lang="de"` / `lang="tr"`
- Non-active header variants: `aria-hidden="true"`
- `.sr-only` utility class available in `_reusable.scss`
- `outline: none` on inputs must always have a `:focus-visible` fallback
- **PA11y CI**: `.github/workflows/accessibility.yml` runs on every push to main, scans 6 primary marketing pages against WCAG2AA. Requires `SITE_URL` repo secret. Hero eyebrow exempted in `.pa11yci.json` (false positive from gradient overlay).

---

## Header variants

`header.php` contains four header variants simultaneously in the DOM:
1. Default (dark)
2. `salefish-2026` (light — for light-background pages listed in `$_sf_light_header`)
3. DE variant (German)
4. TR variant (Turkish)

Non-active variants have `aria-hidden="true"`.

---

## Blog system

**Blog card field order** (all card types must follow this):
1. Chips / category badges (`.blog-card__badges`)
2. Meta line: `Date · Author · Read Time` (`.blog-card__meta`)
3. Title
4. CTA (`.blog-card__link`)

**Card types**: `.blog-card` (grid), `.blog-featured__main`, `.blog-featured__side`, `.blog-sticky__card`

**Read time**: calculated at 200 WPM from word count.

**Pre-footer featured posts strip** (`template-parts/footer-featured-posts.php`): included in `footer.php`, skipped on blog listing/filter pages, DE/TR pages, and single-post pages.

---

## Internationalization

Three locales: **AU** (English), **DE** (German), **TR** (Turkish).
- AU uses the same English content as US with a different contact form
- DE + TR nav: shows only Features link (translated) + CTA button; all other items hidden
- DE + TR footer: platform links + Plus Group column only
- Legal line on contact forms: translated for DE and TR
- Modal forms: translated for DE and TR
- DE + TR pages: no pre-footer Featured Blog Posts strip

---

## Analytics and tracking

| Tool | ID / Notes |
|------|-----------|
| GA4 | `G-RPV5YBTN35`, via `gtag.js` in `header.php`, deferred 4s after `window.load` |
| GTM | `GTM-5CX687F`; `<noscript>` iframe fallback in `header.php` |
| Microsoft Clarity | Project ID `wmwzqm0oha` in `footer.php` |
| LinkedIn Pixel | `lintrk('track', ...)` via `sfTrackConversion()` on form success; `SF_LI_CONVERSION_ID` const in `general.js` |
| Tidio | Live chat widget; `MutationObserver` watches for `tidio-chat-iframe` injection (90s timeout) |

**`sfTrackConversion(type, location)`**: fires GA4 `generate_lead` + LinkedIn pixel. Call on all form success events.

---

## Service worker

Cache name format: `salefish-vNN-YYYY-MM-DD-description`. Bump on every deploy that changes CSS/JS/HTML.

Only caches theme assets under `/wp-content/themes/salefish/` — **not** WordPress media uploads (would bloat Safari Cache Storage and trigger memory warnings).

---

## Active plugins

| Plugin | Purpose |
|--------|---------|
| `advanced-custom-fields-pro` | All custom fields (`page_locale`, hero images, etc.) |
| `custom-post-type-ui` | Custom post types |
| `google-site-kit` | GA4 integration |
| `litespeed-cache` | Server-side HTML caching |
| `media-sync` | Media library sync |
| `redirection` | URL redirects |
| `tidio-live-chat` | Live chat widget |
| `tracking-code-manager` | GTM |
| `updraftplus` | Backups |
| `wordpress-seo` | Yoast SEO |
| `404-to-homepage` | Redirects 404s |
| `Salefish` | Custom SaleFish plugin (always deployed from local) |

**Deleted plugins — do not re-add**: `wp-super-cache` (replaced by litespeed-cache), `jetpack-boost`, `insert-headers-and-footers` / WPCode Lite, `leadin`, `post-types-order`, `worker`.

**Plugin update rule**: Third-party plugins are updated via WP Admin only. Never FTP-deploy older plugin versions from local over live. The custom `Salefish` plugin is the only exception — always deploy it from local.

---

## Server / hosting notes

- **Port 22 (SSH) may be blocked** by fail2ban if too many failed auth attempts — use FTP (port 21) with lftp instead
- **`wp-config.php`** is excluded from git and FTP deploys — never overwrite it
- **`advanced-cache.php`**: was an orphan from deleted WP Super Cache and was deleted. If it reappears, it causes critical errors — delete it again
- **`Cache-Control: no-store`** injected by Imunify360/ModSecurity at a level below Apache — cannot be fixed from `.htaccess` alone. Workaround: `session.cache_limiter ""` via `php_value` in `.htaccess`. Full fix requires Cloudflare proxy (orange cloud) with Browser TTL Override
- **LiteSpeed Cache config**: public TTL 1 week, browser cache 1 year, Cache Logged-in Users OFF, Cache Mobile OFF, Cache Login Page OFF, all Page Optimization OFF
