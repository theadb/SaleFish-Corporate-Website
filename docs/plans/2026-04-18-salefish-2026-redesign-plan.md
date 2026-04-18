# SaleFish 2026 Theme — Full Redesign Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development to implement this plan task-by-task.

**Goal:** Rebuild every PHP template and SCSS page file in the salefish-2026 theme so the site visually matches Plinthra — glass cards, gradient backgrounds, Plinthra typography and colour tokens — while keeping all ACF field calls and JS hooks identical.

**Architecture:** Option B full rebuild. PHP template structure changes to use `.sf-section` / `.sf-container` layout and `.sf-card` components; all ACF `get_field()` calls, Swiper class names, counter IDs, and `#contact_us` anchor stay identical so existing app.js continues to work. SCSS page files are replaced entirely; partials (salefish-features.php, contact-general.php) are styled via CSS overrides, not modified.

**Tech Stack:** WordPress classic theme, PHP 8, ACF, Laravel Mix 6 (Sass + webpack). Build: `cd wp-content/themes/salefish-2026 && npm run dev`. Output: `dest/app.css`, `dest/app.js`.

---

## Context for each task

- Theme root: `wp-content/themes/salefish-2026/`
- SCSS entry: `assets/scss/app.scss` (already imports all files below)
- Compiled CSS: `dest/app.css`
- Design tokens already defined in `basics/_variables.scss` and `basics/_dark-mode.scss` — use only `var(--sf-*)` tokens, never legacy `$gold`, `$dark_purple`, `$light_purple`
- Existing reusable components in `basics/_reusable.scss`: `.sf-card`, `.sf-badge`, `.sf-btn`, `.sf-gradient-primary`
- Breakpoint mixins: `@include rs768` (≤768px), `@include rs550` (≤550px), `@include rs990` (≤990px)
- Do NOT modify: `partials/`, `assets/js/`, `functions.php`, `assets/scss/app.scss`, `assets/scss/basics/_variables.scss`, `assets/scss/basics/_dark-mode.scss`, `assets/scss/basics/_tokens.scss`, `assets/scss/basics/_reusable.scss`

---

## Task 1: Add layout utilities + fix paragraph font-size in `_general.scss`

**Files:**
- Modify: `assets/scss/basics/_general.scss`

**Step 1: Add `.sf-section`, `.sf-container`, `.sf-prose` utilities and fix `p` font-size**

Replace the `p` rule and append utilities after the closing `}` of the `@layer base` block. The full updated file:

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
    background-color: hsl(var(--sf-bg));
    font-family: var(--sf-font-sans);
    letter-spacing: -0.011em;
    font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
    overflow-x: hidden;
    position: relative;
    transition: background-color 0.2s ease, color 0.2s ease;
  }

  body::before {
    content: '';
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 9998;
    opacity: 0.025;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 200px 200px;
  }

  .dark body::before {
    opacity: 0.035;
    mix-blend-mode: overlay;
  }

  img, svg {
    image-rendering: -webkit-optimize-contrast;
  }

  input {
    -webkit-appearance: none;
    border-radius: 0;
  }

  h1 {
    font-family: var(--sf-font-sans);
    font-weight: 700;
    font-size: clamp(2rem, 5vw, 3.5rem);
    line-height: 1.1;
    letter-spacing: -0.02em;
    color: hsl(var(--sf-fg));
  }

  h2 {
    font-family: var(--sf-font-sans);
    font-weight: 700;
    font-size: clamp(1.6rem, 4vw, 2.8rem);
    line-height: 1.2;
    letter-spacing: -0.015em;
    color: hsl(var(--sf-fg));
  }

  h3 {
    font-family: var(--sf-font-sans);
    font-weight: 600;
    font-size: 1rem;
    line-height: 1.5;
    color: hsl(var(--sf-fg));
  }

  p {
    font-family: var(--sf-font-sans);
    font-size: 1rem;
    line-height: 1.6;
    color: hsl(var(--sf-muted-fg));
  }

  a {
    color: hsl(var(--sf-primary));
    text-decoration: none;
    transition: color 0.15s ease;

    &:hover { color: hsl(var(--sf-primary) / 0.8); }
  }

  *:focus-visible {
    outline: 2px solid hsl(var(--sf-ring));
    outline-offset: 2px;
    border-radius: 4px;
  }
}

.max_wrapper {
  max-width: 1800px;
  margin: 0 auto;
}

.sf-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 40px;

  @include rs768 { padding: 0 20px; }
}

.sf-section {
  padding: 80px 0;

  @include rs768 { padding: 60px 0; }
  @include rs550 { padding: 40px 0; }
}

.sf-section--sm {
  padding: 48px 0;

  @include rs768 { padding: 32px 0; }
}

.sf-prose {
  font-family: var(--sf-font-sans);
  font-size: 1rem;
  line-height: 1.75;
  color: hsl(var(--sf-fg));
  max-width: 68ch;

  h1, h2, h3, h4 {
    color: hsl(var(--sf-fg));
    margin: 1.5em 0 0.5em;
    line-height: 1.3;
  }

  p { margin: 0 0 1em; color: hsl(var(--sf-fg)); }

  a { color: hsl(var(--sf-primary)); text-decoration: underline; }

  blockquote {
    border-left: 3px solid hsl(var(--sf-primary));
    padding-left: 1rem;
    margin: 1.5rem 0;
    color: hsl(var(--sf-muted-fg));
    font-style: italic;
  }

  ul, ol { padding-left: 1.5rem; margin: 0 0 1em; }

  li { margin-bottom: 0.4em; }

  img { border-radius: var(--sf-radius); max-width: 100%; }

  pre, code {
    font-family: monospace;
    background: hsl(var(--sf-muted) / 0.5);
    border-radius: var(--sf-radius);
    padding: 2px 6px;
    font-size: 0.875rem;
  }
}

main { overflow-x: hidden; }

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

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
```

**Step 2: Build to verify no SCSS errors**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

Expected: Compiled successfully with no errors.

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/basics/_general.scss
git commit -m "style: add sf-section/container utilities, fix body type scale"
```

---

## Task 2: Rewrite `layouts/header.scss` — glass header

**Files:**
- Modify: `assets/scss/layouts/header.scss`

**Step 1: Replace the entire file with glass header styles**

```scss
// Glass sticky header — shared styles for all 4 header variants
header {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 999;
  height: 68px;
  display: flex;
  align-items: center;
  background: hsl(var(--sf-background) / 0.85);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-bottom: 1px solid hsl(var(--sf-border));
  transition: background 0.2s ease, border-color 0.2s ease;

  @include rs768 { height: 60px; }

  .max_wrapper {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;

    @include rs768 { padding: 0 20px; }
  }

  .salefish {
    flex-shrink: 0;

    .salefish_logo {
      display: block;
      width: 150px;
      height: auto;

      @include rs550 { width: 120px; }
    }
  }

  nav {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-left: auto;

    ul {
      display: flex;
      align-items: center;
      list-style: none;
      gap: 0;
      margin: 0;
      padding: 0;

      @include rs768 { display: none; }

      li {
        a, span {
          font-family: var(--sf-font-sans);
          font-size: 0.875rem;
          font-weight: 500;
          color: hsl(var(--sf-muted-fg));
          padding: 6px 12px;
          border-radius: var(--sf-radius);
          transition: color 0.15s ease, background 0.15s ease;
          cursor: pointer;
          white-space: nowrap;
          text-decoration: none;
          display: block;
          line-height: 1;

          &:hover {
            color: hsl(var(--sf-fg));
            background: hsl(var(--sf-muted) / 0.5);
          }
        }

        &.sales_login {
          margin-left: 4px;

          a, span {
            color: hsl(var(--sf-fg));
            border: 1px solid hsl(var(--sf-border));
            border-radius: var(--sf-radius);
            font-weight: 600;

            &:hover {
              background: hsl(var(--sf-muted));
              opacity: 1;
            }
          }
        }
      }
    }
  }

  // Grouped lang+theme pill
  .sf-header-controls {
    display: flex;
    align-items: center;
    gap: 2px;
    border: 1px solid hsl(var(--sf-border));
    border-radius: var(--sf-radius);
    padding: 3px;
    margin-left: 6px;
    flex-shrink: 0;

    @include rs768 { display: none; }
  }

  .sf-header-icon-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border: none;
    background: transparent;
    color: hsl(var(--sf-muted-fg));
    border-radius: calc(var(--sf-radius) - 2px);
    cursor: pointer;
    transition: background 0.15s ease, color 0.15s ease;
    padding: 0;

    &:hover {
      background: hsl(var(--sf-muted) / 0.6);
      color: hsl(var(--sf-fg));
    }

    svg {
      width: 17px;
      height: 17px;
      flex-shrink: 0;
    }
  }

  .languages {
    position: relative;

    .languages_option {
      position: absolute;
      top: calc(100% + 8px);
      right: 0;
      background: hsl(var(--sf-card));
      border: 1px solid hsl(var(--sf-border));
      border-radius: var(--sf-radius-lg);
      box-shadow: var(--sf-shadow-md);
      padding: 6px;
      min-width: 120px;
      display: none;
      z-index: 10;

      ul {
        display: flex;
        flex-direction: column;
        gap: 2px;
        list-style: none;
        padding: 0;
        margin: 0;

        li a {
          display: flex;
          align-items: center;
          gap: 8px;
          padding: 7px 10px;
          border-radius: calc(var(--sf-radius) - 2px);
          font-size: 0.8125rem;
          color: hsl(var(--sf-fg));

          &:hover { background: hsl(var(--sf-muted) / 0.5); }

          .flag {
            width: 20px;
            height: auto;
            border-radius: 2px;
          }
        }
      }

      &.open { display: block; }
    }
  }

  .menu {
    display: none;
    margin-left: 8px;

    @include rs768 { display: flex; }

    .hamburger {
      .hamburger-inner,
      .hamburger-inner::before,
      .hamburger-inner::after {
        background: hsl(var(--sf-fg));
      }
    }
  }
}

// Default header: show on all pages
.default { display: flex; }

// Newsroom-style header: hidden by default, shown on newsroom/post/legal pages
.newsroom_header { display: none; }

.page-template-page-newsroom,
.post-template-default,
.page-template-page-terms-of-use,
.page-template-page-privacy-policy {
  .default { display: none; }
  .newsroom_header { display: flex; }
}

// Language variant headers — JS sets display:flex when language switches
.de_header { display: none; }
.tr_header { display: none; }

// Floating mobile menu (slide-in drawer)
.floating_menu {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: hsl(var(--sf-card));
  z-index: 998;
  transform: translateX(-100%);
  transition: transform 0.3s ease;
  display: none;

  @include rs768 { display: block; }

  &.active { transform: translateX(0); }

  .wrapper {
    height: 100%;
    overflow-y: auto;
    padding-top: 80px;

    .wrap {
      padding: 20px;

      .top ul {
        list-style: none;
        padding: 0;
        margin: 0;

        li {
          border-bottom: 1px solid hsl(var(--sf-border));

          a, span {
            display: block;
            padding: 14px 0;
            font-family: var(--sf-font-sans);
            font-size: 1rem;
            font-weight: 500;
            color: hsl(var(--sf-fg));
            cursor: pointer;
            text-decoration: none;
          }
        }
      }

      hr {
        border: none;
        border-top: 1px solid hsl(var(--sf-border));
        margin: 20px 0;
      }

      .bottom {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding-bottom: 40px;

        .fish { width: 40px; margin-bottom: 8px; }

        a {
          font-family: var(--sf-font-sans);
          font-size: 0.9rem;
          color: hsl(var(--sf-muted-fg));
          text-decoration: none;

          &:hover { color: hsl(var(--sf-fg)); }
        }
      }
    }
  }
}

// Login dropdown
.sales_login_menu {
  position: fixed;
  top: 68px;
  right: 40px;
  background: hsl(var(--sf-card));
  border: 1px solid hsl(var(--sf-border));
  border-radius: var(--sf-radius-lg);
  box-shadow: var(--sf-shadow-md);
  padding: 6px;
  z-index: 997;
  min-width: 180px;
  display: none;

  &.active { display: block; }

  .wrapper .wrap ul {
    list-style: none;
    padding: 0;
    margin: 0;

    li a {
      display: block;
      padding: 9px 14px;
      font-family: var(--sf-font-sans);
      font-size: 0.875rem;
      font-weight: 500;
      color: hsl(var(--sf-fg));
      border-radius: calc(var(--sf-radius) - 2px);
      text-decoration: none;
      transition: background 0.15s ease;

      &:hover { background: hsl(var(--sf-muted) / 0.5); }
    }
  }
}

// Thank you / notification overlay
.thank_you_msg {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgb(0 0 0 / 0.5);
  z-index: 9000;
  display: none;
  align-items: center;
  justify-content: center;

  &.active { display: flex; }

  .wrapper .wrap {
    background: hsl(var(--sf-card));
    border: 1px solid hsl(var(--sf-border));
    border-radius: var(--sf-radius-lg);
    padding: 40px;
    max-width: 480px;
    text-align: center;
    box-shadow: var(--sf-shadow-md);

    h1 {
      font-size: 1.5rem;
      color: hsl(var(--sf-fg));
      margin-bottom: 12px;
    }

    p {
      color: hsl(var(--sf-muted-fg));
      font-size: 0.9rem;
      line-height: 1.6;
    }

    a { color: hsl(var(--sf-primary)); }
  }
}

// Privacy / Terms popups
.privacy_policy,
.terms_popup {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgb(0 0 0 / 0.5);
  z-index: 9001;
  display: none;
  align-items: flex-start;
  justify-content: center;
  padding-top: 80px;
  overflow-y: auto;

  &.active { display: flex; }

  .wrapper {
    background: hsl(var(--sf-card));
    border: 1px solid hsl(var(--sf-border));
    border-radius: var(--sf-radius-lg);
    padding: 40px;
    max-width: 720px;
    width: calc(100% - 40px);
    position: relative;
    margin-bottom: 40px;

    h1 {
      font-size: 1.6rem;
      color: hsl(var(--sf-fg));
      margin-bottom: 20px;
    }

    i {
      position: absolute;
      top: 16px; right: 16px;
      cursor: pointer;
      color: hsl(var(--sf-muted-fg));
      font-size: 1.5rem;
      line-height: 1;

      &:hover { color: hsl(var(--sf-fg)); }
    }

    .wrap { @extend .sf-prose; max-width: none; }
  }
}

// Profile popup (banner feature)
.profile_popup {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgb(0 0 0 / 0.6);
  z-index: 9002;
  display: none;
  align-items: center;
  justify-content: center;

  &.active { display: flex; }

  .wrapper {
    background: hsl(var(--sf-card));
    border: 1px solid hsl(var(--sf-border));
    border-radius: var(--sf-radius-lg);
    padding: 40px;
    max-width: 560px;
    width: calc(100% - 40px);
    text-align: center;
    box-shadow: var(--sf-shadow-md);

    h1 {
      font-size: 1.6rem;
      color: hsl(var(--sf-fg));
      margin-bottom: 24px;
    }

    .role {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;

      @include rs550 { grid-template-columns: 1fr; }

      .item {
        @extend .sf-card;
        padding: 24px 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        cursor: pointer;

        svg { color: hsl(var(--sf-primary)); }

        h2 {
          font-size: 0.9rem;
          font-weight: 700;
          color: hsl(var(--sf-fg));
          line-height: 1.3;
          margin: 0;

          span { display: block; font-weight: 400; color: hsl(var(--sf-muted-fg)); }
        }

        a {
          @extend .sf-btn;
          font-size: 0.8125rem;
          padding: 8px 16px;
        }
      }
    }

    .close_profile_popup {
      position: absolute;
      top: 16px; right: 16px;
      cursor: pointer;
      color: hsl(var(--sf-muted-fg));

      &:hover { color: hsl(var(--sf-fg)); }
    }

    > p {
      margin-top: 20px;
      font-size: 0.875rem;
      color: hsl(var(--sf-muted-fg));
      cursor: pointer;

      span {
        color: hsl(var(--sf-primary));
        text-decoration: underline;
        cursor: pointer;
      }
    }
  }
}
```

**Step 2: Build to verify**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

Expected: Compiled successfully. Check `dest/app.css` is updated.

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/layouts/header.scss
git commit -m "style: rewrite header as glass sticky bar with Plinthra token styles"
```

---

## Task 3: Update `header.php` — unified glass header with lucide icons

**Files:**
- Modify: `header.php`

**Context:** The current header.php has 4 `<header>` variants (`.default`, `.newsroom_header`, `.de_header`, `.tr_header`). Keep all 4 — the visibility logic relies on CSS body classes and JS. The changes are: (1) replace the flag-image language dropdown opener with an inline `Languages` SVG button, (2) replace the separate sun/moon toggle with a `SunMoon` SVG button, (3) group both in `.sf-header-controls`. The floating menus, login dropdown, Terms/Privacy popups, and profile popup at the bottom of the file are kept verbatim.

**Step 1: Replace only the 4 `<header>` blocks**

Find the section from `<header class="default"` to the closing `</header>` of `.tr_header` (lines 86–440) and replace all four headers with these new versions. Everything from `<script>` (the theme toggle handler) onward stays unchanged.

The language dropdown opener and theme toggle in each header now look like this. Apply the same pattern to all 4 variants:

```php
<header class="default">
	<div class="max_wrapper" data-aos="fade-down" data-aos-delay="200">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php bloginfo('template_directory'); ?>/img/salefish_logo.png"
					alt="SaleFish">
			</a>
		</div>
		<nav>
			<ul>
				<li class="features_nav">
					<a href="/#features">Features</a>
				</li>
				<li class="newsroom_nav">
					<a href="/newsroom">Newsroom</a>
				</li>
				<li class="partners_nav">
					<a href="/partners">Partners</a>
				</li>
				<li class="contact_us_nav">
					<a href="/contact-us">Contact</a>
				</li>
				<li class="sales_login">
					<span>Login</span>
				</li>
			</ul>
			<div class="sf-header-controls">
				<div class="languages">
					<button class="sf-header-icon-btn" aria-label="Select language" type="button">
						<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
					</button>
					<div class="languages_option">
						<ul>
							<li>
								<a href="">
									<img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/australia.png" alt="English">
								</a>
							</li>
							<li>
								<a href="">
									<img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/germany.png" alt="Deutsch">
								</a>
							</li>
							<li>
								<a href="">
									<img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/turkey.png" alt="Türkçe">
								</a>
							</li>
						</ul>
					</div>
				</div>
				<button class="sf-header-icon-btn sf-theme-toggle" id="sf-theme-toggle" aria-label="Toggle dark mode" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8a2.83 2.83 0 0 0 4 4 4 4 0 1 1-4-4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.9 4.9 1.4 1.4"/><path d="m17.7 17.7 1.4 1.4"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.3 17.7-1.4 1.4"/><path d="m19.1 4.9-1.4 1.4"/></svg>
				</button>
			</div>
			<div class="menu">
				<button class="hamburger hamburger--emphatic" type="button">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</button>
			</div>
		</nav>
	</div>
</header>

<header class="newsroom_header">
	<div class="max_wrapper" data-aos="fade-down" data-aos-delay="200">
		<div class="salefish">
			<a href="/">
				<img class="salefish_logo"
					src="<?php bloginfo('template_directory'); ?>/img/salefish_logo.png"
					alt="SaleFish">
			</a>
		</div>
		<nav>
			<ul>
				<li class="features_li">
					<a href="/#features">Features</a>
				</li>
				<li class="newsroom_nav">
					<a href="/newsroom">Newsroom</a>
				</li>
				<li class="partners_nav">
					<a href="/partners">Partners</a>
				</li>
				<li class="contact_us_nav">
					<a href="/contact-us">Contact</a>
				</li>
				<li class="sales_login">
					<span>Login</span>
				</li>
			</ul>
			<div class="sf-header-controls">
				<div class="languages">
					<button class="sf-header-icon-btn" aria-label="Select language" type="button">
						<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
					</button>
					<div class="languages_option">
						<ul>
							<li><a href=""><img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/australia.png" alt="English"></a></li>
							<li><a href=""><img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/germany.png" alt="Deutsch"></a></li>
							<li><a href=""><img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/turkey.png" alt="Türkçe"></a></li>
						</ul>
					</div>
				</div>
				<button class="sf-header-icon-btn sf-theme-toggle" id="sf-theme-toggle" aria-label="Toggle dark mode" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8a2.83 2.83 0 0 0 4 4 4 4 0 1 1-4-4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.9 4.9 1.4 1.4"/><path d="m17.7 17.7 1.4 1.4"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.3 17.7-1.4 1.4"/><path d="m19.1 4.9-1.4 1.4"/></svg>
				</button>
			</div>
			<div class="menu">
				<button class="hamburger hamburger--emphatic" type="button">
					<span class="hamburger-box"><span class="hamburger-inner"></span></span>
				</button>
			</div>
		</nav>
	</div>
</header>

<header class="de_header">
	<div class="max_wrapper" data-aos="fade-down" data-aos-delay="200">
		<div class="salefish">
			<a href="/"><img class="salefish_logo" src="<?php bloginfo('template_directory'); ?>/img/salefish_logo.png" alt="SaleFish"></a>
		</div>
		<nav>
			<ul>
				<li class="features_li"><a href="/#features">MERKMALE</a></li>
				<li class="partners_nav"><a href="/partners">PARTNER</a></li>
				<li class="newsroom_nav"><a href="/newsroom">NEWSRAUM</a></li>
				<li class="contact_us_nav"><a href="/contact-us">KONTAKTIERE UNS</a></li>
				<li class="sales_login"><span>EINLOGGEN</span></li>
			</ul>
			<div class="sf-header-controls">
				<div class="languages">
					<button class="sf-header-icon-btn" aria-label="Sprache wählen" type="button">
						<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
					</button>
					<div class="languages_option">
						<ul>
							<li><a href=""><img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/australia.png" alt="English"></a></li>
							<li><a href=""><img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/germany.png" alt="Deutsch"></a></li>
							<li><a href=""><img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/turkey.png" alt="Türkçe"></a></li>
						</ul>
					</div>
				</div>
				<button class="sf-header-icon-btn sf-theme-toggle" id="sf-theme-toggle" aria-label="Dunkelmodus umschalten" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8a2.83 2.83 0 0 0 4 4 4 4 0 1 1-4-4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.9 4.9 1.4 1.4"/><path d="m17.7 17.7 1.4 1.4"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.3 17.7-1.4 1.4"/><path d="m19.1 4.9-1.4 1.4"/></svg>
				</button>
			</div>
			<div class="menu">
				<button class="hamburger hamburger--emphatic" type="button">
					<span class="hamburger-box"><span class="hamburger-inner"></span></span>
				</button>
			</div>
		</nav>
	</div>
</header>

<header class="tr_header">
	<div class="max_wrapper" data-aos="fade-down" data-aos-delay="200">
		<div class="salefish">
			<a href="/"><img class="salefish_logo" src="<?php bloginfo('template_directory'); ?>/img/salefish_logo.png" alt="SaleFish"></a>
		</div>
		<nav>
			<ul>
				<li class="features_li"><a href="/#features">ÖZELLİKLERİ</a></li>
				<li class="newsroom_nav"><a href="/newsroom">HABERLER</a></li>
				<li class="partners_nav"><a href="/partners">ORTAKLAR</a></li>
				<li class="contact_us_nav"><a href="/tr/contact">BİZE ULAŞIN</a></li>
				<li class="sales_login"><span>GİRİŞ YAPMA</span></li>
			</ul>
			<div class="sf-header-controls">
				<div class="languages">
					<button class="sf-header-icon-btn" aria-label="Dil seç" type="button">
						<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
					</button>
					<div class="languages_option">
						<ul>
							<li><a href=""><img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/australia.png" alt="English"></a></li>
							<li><a href=""><img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/germany.png" alt="Deutsch"></a></li>
							<li><a href=""><img class="flag" src="<?php bloginfo('template_directory'); ?>/img/flags/turkey.png" alt="Türkçe"></a></li>
						</ul>
					</div>
				</div>
				<button class="sf-header-icon-btn sf-theme-toggle" id="sf-theme-toggle" aria-label="Karanlık modu aç/kapat" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8a2.83 2.83 0 0 0 4 4 4 4 0 1 1-4-4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.9 4.9 1.4 1.4"/><path d="m17.7 17.7 1.4 1.4"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.3 17.7-1.4 1.4"/><path d="m19.1 4.9-1.4 1.4"/></svg>
				</button>
			</div>
			<div class="menu">
				<button class="hamburger hamburger--emphatic" type="button">
					<span class="hamburger-box"><span class="hamburger-inner"></span></span>
				</button>
			</div>
		</nav>
	</div>
</header>
```

Keep everything from `<script>` (the theme toggle handler, line 442 onward) unchanged.

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/header.php
git commit -m "feat: update header with glass design, Languages + SunMoon lucide icons"
```

---

## Task 4: Rewrite `footer.php` — dark footer layout

**Files:**
- Modify: `footer.php`

**Context:** Keep `wp_footer()`, the `salefishAjax` JS variable, all external CDN scripts, and the analytics tags. Replace only the `<footer>` element markup and the closing `</body></html>` wrapper.

**Step 1: Replace the `<footer>...</footer>` block** (from line 10 to line 253) with:

```php
<footer class="sf-footer">
	<div class="sf-footer__inner">
		<div class="sf-footer__top">
			<div class="sf-footer__brand">
				<a href="/">
					<img class="sf-footer__logo" src="<?php bloginfo('template_directory'); ?>/img/salefish_logo.png" alt="SaleFish">
				</a>
				<p class="sf-footer__tagline">The all-in-one platform for new home sales teams.</p>
				<div class="sf-footer__social">
					<a href="https://www.linkedin.com/company/salefishapp/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
						<img class="social_logo linkedin" src="<?php bloginfo('template_directory'); ?>/img/linkedin_logo.png" alt="LinkedIn">
					</a>
					<a href="https://www.instagram.com/salefishapp/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
						<img class="social_logo ig" src="<?php bloginfo('template_directory'); ?>/img/ig_logo.png" alt="Instagram">
					</a>
				</div>
			</div>
			<div class="sf-footer__links us_links">
				<div class="sf-footer__col">
					<div class="sf-footer__col-title">Platform</div>
					<ul>
						<li><a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer">Sales App</a></li>
						<li><a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer">Highrise Admin</a></li>
						<li class="terms_of_use_footer"><span class="terms_menu_footer">Terms of Use</span></li>
						<li class="privacy_policy_footer"><span class="privacy_policy_menu_footer">Privacy Policy</span></li>
					</ul>
				</div>
				<div class="sf-footer__col">
					<div class="sf-footer__col-title">Newsroom</div>
					<ul>
						<li><a href="/newsroom?filter=success-stories">Success Stories</a></li>
						<li><a href="/newsroom?filter=press">Press</a></li>
						<li><a href="/newsroom?filter=blog">Blog</a></li>
						<li><a href="/newsroom?filter=videos">Videos</a></li>
					</ul>
				</div>
				<div class="sf-footer__col">
					<div class="sf-footer__col-title">Company</div>
					<ul>
						<li><a href="https://salefish.app/#features">Features</a></li>
						<li><a href="/our-story">Our Story</a></li>
						<li><a href="/awards">Awards</a></li>
						<li><a href="/partners">Partners</a></li>
						<li><a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">The Plus Group</a></li>
					</ul>
				</div>
				<div class="sf-footer__col sf-footer__col--logos">
					<a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">
						<img class="plus_group" src="<?php bloginfo('template_directory'); ?>/img/plus_group.png" alt="Plus Group">
					</a>
					<a href="https://ised-isde.canada.ca/site/cybersecure-canada/en" target="_blank" rel="noopener noreferrer">
						<img class="certified" src="<?php bloginfo('template_directory'); ?>/img/certified.png" alt="Certified">
					</a>
					<img class="fish" src="<?php echo get_template_directory_uri(); ?>/img/fish.gif" alt="Fish">
				</div>
			</div>
			<div class="sf-footer__links de_links">
				<div class="sf-footer__col">
					<div class="sf-footer__col-title">Plattform</div>
					<ul>
						<li><a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer">Verkaufs-App</a></li>
						<li><a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer">Highrise Admin</a></li>
						<li class="terms_of_use_footer"><span class="terms_menu_footer">Nutzungsbedingungen</span></li>
						<li class="privacy_policy_footer"><span class="privacy_policy_menu_footer">Datenschutz-Bestimmungen</span></li>
					</ul>
				</div>
				<div class="sf-footer__col">
					<div class="sf-footer__col-title">Newsroom</div>
					<ul>
						<li><a href="/newsroom?filter=success-stories">Erfolgsgeschichten</a></li>
						<li><a href="/newsroom?filter=press">Presse</a></li>
						<li><a href="/newsroom?filter=blog">Neuigkeiten</a></li>
						<li><a href="/newsroom?filter=videos">Videos</a></li>
					</ul>
				</div>
				<div class="sf-footer__col">
					<div class="sf-footer__col-title">Gesellschaft</div>
					<ul>
						<li><a href="/our-story">Unsere Geschichte</a></li>
						<li><a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">Die Plus-Gruppe</a></li>
					</ul>
				</div>
				<div class="sf-footer__col sf-footer__col--logos">
					<a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">
						<img class="plus_group" src="<?php bloginfo('template_directory'); ?>/img/plus_group.png" alt="Plus Group">
					</a>
					<img class="fish" src="<?php echo get_template_directory_uri(); ?>/img/fish.gif" alt="Fish">
				</div>
			</div>
			<div class="sf-footer__links tr_links">
				<div class="sf-footer__col">
					<div class="sf-footer__col-title">Aplikasyon Linkleri</div>
					<ul>
						<li><a href="https://salefish.app/sales" target="_blank" rel="noopener noreferrer">Satış Aplikasyonu</a></li>
						<li><a href="https://salefish.app/admin/hm" target="_blank" rel="noopener noreferrer">Çok Katlı Bina Yönetimi</a></li>
						<li class="terms_of_use_footer"><span class="terms_menu_footer">Kullanım Koşulları</span></li>
						<li class="privacy_policy_footer"><span class="privacy_policy_menu_footer">Gizlilik Politikası</span></li>
					</ul>
				</div>
				<div class="sf-footer__col">
					<div class="sf-footer__col-title">Haberler</div>
					<ul>
						<li><a href="/newsroom?filter=success-stories">Başarı Hikayeleri</a></li>
						<li><a href="/newsroom?filter=press">Basın</a></li>
						<li><a href="/newsroom?filter=blog">Blog</a></li>
						<li><a href="/newsroom?filter=videos">Videolar</a></li>
					</ul>
				</div>
				<div class="sf-footer__col">
					<div class="sf-footer__col-title">Kurumsal</div>
					<ul>
						<li><a href="/our-story">Hikayemiz</a></li>
						<li><a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">The Plus Group</a></li>
					</ul>
				</div>
				<div class="sf-footer__col sf-footer__col--logos">
					<a href="https://www.theplusgroup.ca/" target="_blank" rel="noopener noreferrer">
						<img class="plus_group" src="<?php bloginfo('template_directory'); ?>/img/plus_group.png" alt="Plus Group">
					</a>
					<img class="fish" src="<?php echo get_template_directory_uri(); ?>/img/fish.gif" alt="Fish">
				</div>
			</div>
		</div>
		<div class="sf-footer__bottom">
			<p>Made with ❤ by <a href="https://coolaidstudios.com/" target="_blank" rel="noopener noreferrer">COOLAID Studios Inc.</a> · <span>© <?php echo date("Y"); ?> SaleFish Inc. All rights reserved.</span></p>
			<a href="https://ised-isde.canada.ca/site/cybersecure-canada/en" target="_blank" rel="noopener noreferrer">
				<img class="certified" src="<?php bloginfo('template_directory'); ?>/img/cyber-essentials.png" alt="Cyber Essentials Certified">
			</a>
		</div>
	</div>
</footer>
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/footer.php
git commit -m "feat: rewrite footer with dark background grid layout"
```

---

## Task 5: Rewrite `layouts/footer.scss`

**Files:**
- Modify: `assets/scss/layouts/footer.scss`

**Step 1: Replace the entire file**

```scss
.sf-footer {
  background: hsl(var(--sf-primary));
  color: hsl(var(--sf-primary-fg) / 0.85);
  margin-top: auto;

  &__inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 64px 40px 32px;

    @include rs768 { padding: 48px 20px 24px; }
  }

  &__top {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 48px;
    margin-bottom: 48px;

    @include rs990 {
      grid-template-columns: 1fr;
      gap: 32px;
    }
  }

  &__brand {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  &__logo {
    width: 140px;
    height: auto;
    filter: brightness(0) invert(1);
    opacity: 0.9;
  }

  &__tagline {
    font-family: var(--sf-font-sans);
    font-size: 0.875rem;
    line-height: 1.5;
    color: hsl(var(--sf-primary-fg) / 0.65);
    max-width: 220px;
  }

  &__social {
    display: flex;
    gap: 12px;
    margin-top: 4px;

    a {
      display: flex;
      opacity: 0.7;
      transition: opacity 0.15s ease;

      &:hover { opacity: 1; }

      img {
        width: 22px;
        height: auto;
        filter: brightness(0) invert(1);
      }
    }
  }

  // Language-specific link sets — JS shows the right one
  &__links {
    display: none;

    &.us_links { display: grid; }

    grid-template-columns: repeat(4, 1fr);
    gap: 32px;

    @include rs990 { grid-template-columns: repeat(2, 1fr); gap: 24px; }
    @include rs550 { grid-template-columns: 1fr 1fr; gap: 16px; }
  }

  &__col {
    display: flex;
    flex-direction: column;
    gap: 10px;

    &-title {
      font-family: var(--sf-font-sans);
      font-size: 0.6875rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: hsl(var(--sf-primary-fg) / 0.5);
      margin-bottom: 4px;
    }

    ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    a, span {
      font-family: var(--sf-font-sans);
      font-size: 0.875rem;
      color: hsl(var(--sf-primary-fg) / 0.75);
      text-decoration: none;
      transition: color 0.15s ease;
      cursor: pointer;
      line-height: 1;

      &:hover { color: hsl(var(--sf-primary-fg)); }
    }

    &--logos {
      display: flex;
      flex-direction: column;
      gap: 16px;
      align-items: flex-start;

      img {
        max-width: 120px;
        height: auto;
        filter: brightness(0) invert(1);
        opacity: 0.6;
        transition: opacity 0.15s ease;

        &:hover { opacity: 0.9; }

        &.fish {
          width: 50px;
          filter: none;
          opacity: 1;
        }
      }
    }
  }

  &__bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding-top: 24px;
    border-top: 1px solid hsl(var(--sf-primary-fg) / 0.15);

    @include rs550 { flex-direction: column; align-items: flex-start; }

    p {
      font-family: var(--sf-font-sans);
      font-size: 0.8125rem;
      color: hsl(var(--sf-primary-fg) / 0.5);
      margin: 0;

      a {
        color: hsl(var(--sf-primary-fg) / 0.7);
        text-decoration: none;
        &:hover { color: hsl(var(--sf-primary-fg)); }
      }

      span { color: hsl(var(--sf-primary-fg) / 0.4); }
    }

    .certified {
      width: 80px;
      height: auto;
      opacity: 0.5;
      filter: brightness(0) invert(1);
      transition: opacity 0.15s ease;

      &:hover { opacity: 0.8; }
    }
  }
}
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/layouts/footer.scss
git commit -m "style: rewrite footer SCSS with dark primary background grid"
```

---

## Task 6: Rewrite `front-page.php` — homepage

**Files:**
- Modify: `front-page.php`

**Context:** ACF fields kept identical. Swiper class names (`buildersSwiper`, `pillarsSwiper`, `numbersSwiper`) and counter IDs (`count_1`, `count_2`, etc.) kept intact. `#contact_us` anchor kept. `textArray` JS variable kept. `selling_popup_container` kept. `get_template_part('/partials/salefish-features')` kept. `get_template_part('/partials/contact-general')` kept.

**Step 1: Replace the entire file**

```php
<?php
/**
 * Template Name: Home Page
 */
get_header();

$fade_msg = get_field('fade_messages');
$fade = array();
if ( $fade_msg ) {
    foreach ($fade_msg as $msg) {
        array_push($fade, $msg['text']);
    }
}
$hero_header    = get_field('hero_header');
$hero_image     = get_field('hero_image');
$builders       = get_field('builders_developers');
$pillars        = get_field('pillars');
$numbers_header = get_field('numbers_header');
$the_numbers    = get_field('the_numbers');
?>

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5CX687F" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<script>let textArray = <?php echo wp_json_encode( $fade, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;</script>

<main class="home">

	<div class="selling_popup_container">
		<div class="selling_popup">
			<a href="#contact_us" class="scroll_link button-image">
				<img src="<?php bloginfo('template_directory'); ?>/img/popup.png" class="popup">
			</a>
			<img src="<?php bloginfo('template_directory'); ?>/img/x-closed.svg" class="close_icon">
		</div>
	</div>

	<!-- HERO -->
	<section class="home-hero sf-section">
		<div class="sf-container">
			<div class="home-hero__inner">
				<div class="home-hero__text" data-aos="fade-right" data-aos-delay="200">
					<span class="sf-badge sf-badge--ocean home-hero__eyebrow">AN EASIER WAY TO SELL <label id="app_for_home">HOME SALES</label></span>
					<h1><?php echo wp_kses_post( $hero_header ); ?></h1>
					<div class="home-hero__ctas">
						<a class="sf-btn" target="_blank" rel="noopener noreferrer"
							href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c">
							Book a Free Demo
						</a>
						<a class="sf-btn sf-btn--ghost" href="#features">Explore Features</a>
					</div>
				</div>
				<?php if ( $hero_image ): ?>
				<div class="home-hero__image" data-aos="zoom-in" data-aos-delay="300">
					<img src="<?php echo esc_url( $hero_image ); ?>" alt="SaleFish App Demo" class="home-hero__img">
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- BUILDERS LOGO STRIP -->
	<?php if ( $builders ): ?>
	<section class="home-builders sf-section--sm">
		<div class="sf-container">
			<p class="home-builders__label">TRUSTED BY THE REAL ESTATE LEADERS BEATING YOU BECAUSE OF SALEFISH:</p>
			<div class="home-builders__carousel">
				<div class="swiper buildersSwiper">
					<div class="swiper-wrapper">
						<?php foreach ( $builders as $logo_url ): ?>
						<div class="swiper-slide">
							<img class="home-builders__logo" src="<?php echo esc_url( $logo_url ); ?>" alt="">
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="home-builders__mobile">
				<?php foreach ( $builders as $logo_url ): ?>
				<img class="home-builders__logo" src="<?php echo esc_url( $logo_url ); ?>" alt="">
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END BUILDERS -->

	<!-- FEATURES (partial — ACF options field) -->
	<?php get_template_part('/partials/salefish-features'); ?>
	<!-- END FEATURES -->

	<!-- STATS STRIP -->
	<?php if ( $the_numbers ): ?>
	<section class="home-stats">
		<div class="sf-container">
			<?php if ( $numbers_header ): ?>
			<div class="home-stats__header">
				<h2><?php echo esc_html( $numbers_header['title'] ); ?></h2>
				<p><?php echo wp_kses_post( $numbers_header['description'] ); ?></p>
			</div>
			<?php endif; ?>
			<div class="home-stats__grid">
				<?php $counter = 0; foreach ( $the_numbers as $row ): $counter++; ?>
				<div class="home-stats__item">
					<div class="home-stats__number">
						<?php echo esc_html( $row['prefix'] ); ?><span
							data-number="<?php echo esc_attr( $row['number'] ); ?>"
							id="count_<?php echo esc_attr( $counter ); ?>"></span><?php echo esc_html( $row['suffix'] ); ?>
					</div>
					<p class="home-stats__label"><?php echo wp_kses_post( $row['description'] ); ?></p>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="home-stats__cta">
				<a class="sf-btn" target="_blank" rel="noopener noreferrer"
					href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c">
					Book a Free Demo
				</a>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END STATS -->

	<!-- PILLARS -->
	<?php if ( $pillars ): ?>
	<section class="home-pillars sf-section">
		<div class="sf-container">
			<div class="home-pillars__header" data-aos="fade-up">
				<h2>The SaleFish Pillars</h2>
				<p>We Build Tools for People Who Expect to Win.</p>
			</div>
			<div class="home-pillars__grid" data-aos="fade-up" data-aos-delay="100">
				<?php foreach ( $pillars as $row ): ?>
				<div class="sf-card home-pillars__card">
					<?php if ( $row['icon'] ): ?>
					<img class="home-pillars__icon" src="<?php echo esc_url( $row['icon'] ); ?>" alt="">
					<?php endif; ?>
					<h3><?php echo esc_html( $row['title'] ); ?></h3>
					<p><?php echo wp_kses_post( $row['description'] ); ?></p>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END PILLARS -->

	<!-- CONTACT FORM -->
	<section class="home-contact sf-section" id="contact_us">
		<div class="sf-container">
			<?php get_template_part('/partials/contact-general'); ?>
		</div>
	</section>
	<!-- END CONTACT -->

</main>

<?php get_footer(); ?>
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/front-page.php
git commit -m "feat: rewrite homepage template with sf-section/container layout"
```

---

## Task 7: Rewrite `pages/home.scss`

**Files:**
- Modify: `assets/scss/pages/home.scss`

**Step 1: Replace the entire file**

```scss
// Homepage — Hero
.home-hero {
  padding-top: calc(68px + 80px); // header height + section padding

  @include rs768 { padding-top: calc(60px + 60px); }

  &__inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: center;

    @include rs990 { grid-template-columns: 1fr; gap: 32px; }
  }

  &__text {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  &__eyebrow {
    font-size: 0.75rem;
    align-self: flex-start;
  }

  h1 {
    font-size: clamp(2.2rem, 5vw, 3.8rem);
    color: hsl(var(--sf-fg));
    line-height: 1.1;
    letter-spacing: -0.02em;
    margin: 0;
  }

  &__ctas {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 4px;
  }

  &__image {
    display: flex;
    justify-content: flex-end;
    align-items: center;

    @include rs990 { justify-content: center; }
  }

  &__img {
    width: 100%;
    max-width: 560px;
    height: auto;
    border-radius: var(--sf-radius-lg);
    box-shadow: var(--sf-shadow-md);
  }
}

// Builder logo strip
.home-builders {
  border-top: 1px solid hsl(var(--sf-border));
  border-bottom: 1px solid hsl(var(--sf-border));
  background: hsl(var(--sf-card));

  &__label {
    font-family: var(--sf-font-sans);
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: hsl(var(--sf-muted-fg));
    margin: 0 0 20px;
    text-align: center;
  }

  &__carousel {
    @include rs768 { display: none; }

    .swiper-slide {
      display: flex;
      align-items: center;
      justify-content: center;
    }
  }

  &__mobile {
    display: none;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;

    @include rs768 { display: flex; }
  }

  &__logo {
    height: 40px;
    width: auto;
    object-fit: contain;
    filter: grayscale(1);
    opacity: 0.6;
    transition: opacity 0.2s ease, filter 0.2s ease;

    &:hover {
      opacity: 1;
      filter: none;
    }
  }
}

// Stats strip
.home-stats {
  background: hsl(var(--sf-primary));
  padding: 80px 0;

  @include rs768 { padding: 60px 0; }

  &__header {
    text-align: center;
    margin-bottom: 48px;

    h2 {
      color: hsl(var(--sf-primary-fg));
      font-size: clamp(1.6rem, 4vw, 2.4rem);
      margin-bottom: 12px;
    }

    p {
      color: hsl(var(--sf-primary-fg) / 0.7);
      font-size: 1.1rem;
      max-width: 540px;
      margin: 0 auto;
    }
  }

  &__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    text-align: center;

    @include rs768 { grid-template-columns: 1fr; gap: 24px; }
  }

  &__number {
    font-family: var(--sf-font-sans);
    font-size: clamp(2.5rem, 6vw, 4rem);
    font-weight: 800;
    color: hsl(var(--sf-primary-fg));
    line-height: 1;
    letter-spacing: -0.02em;
    margin-bottom: 10px;
  }

  &__label {
    font-family: var(--sf-font-sans);
    font-size: 1rem;
    color: hsl(var(--sf-primary-fg) / 0.75);
    line-height: 1.4;
    margin: 0;
  }

  &__cta {
    display: flex;
    justify-content: center;
    margin-top: 48px;

    .sf-btn {
      background: hsl(var(--sf-primary-fg));
      color: hsl(var(--sf-primary));

      &:hover { opacity: 0.9; }
    }
  }
}

// Pillars card grid
.home-pillars {
  &__header {
    text-align: center;
    margin-bottom: 48px;

    h2 { margin-bottom: 10px; }
    p { font-size: 1.1rem; }
  }

  &__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;

    @include rs990 { grid-template-columns: 1fr 1fr; }
    @include rs550 { grid-template-columns: 1fr; }
  }

  &__card {
    padding: 28px 24px;
    display: flex;
    flex-direction: column;
    gap: 12px;

    h3 {
      font-size: 1.05rem;
      font-weight: 700;
      color: hsl(var(--sf-fg));
      margin: 0;
    }

    p {
      font-size: 0.9rem;
      line-height: 1.6;
      color: hsl(var(--sf-muted-fg));
      margin: 0;
    }
  }

  &__icon {
    width: 40px;
    height: 40px;
    object-fit: contain;
    margin-bottom: 4px;
  }
}

// Contact form section
.home-contact {
  .bottom {
    background: var(--sf-card-gradient);
    background-color: hsl(var(--sf-card));
    border: 1px solid hsl(var(--sf-card-border));
    border-radius: var(--sf-radius-lg);
    padding: 40px;

    @include rs550 { padding: 24px; }

    .form {
      max-width: 800px;
      margin: 0 auto;

      h3 {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: hsl(var(--sf-primary));
        margin-bottom: 8px;
      }

      h1 {
        font-size: clamp(1.4rem, 3vw, 2rem);
        color: hsl(var(--sf-fg));
        margin-bottom: 32px;
        font-weight: 700;
      }

      form .row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;

        @include rs550 { grid-template-columns: 1fr; }

        &.submit_row {
          grid-template-columns: 1fr;
          justify-items: flex-start;
        }

        .col {
          label {
            display: block;
            font-family: var(--sf-font-sans);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: hsl(var(--sf-muted-fg));
            margin-bottom: 6px;
          }

          input, select {
            width: 100%;
            box-sizing: border-box;
            background: hsl(var(--sf-input));
            border: 1px solid hsl(var(--sf-border));
            border-radius: var(--sf-radius);
            padding: 10px 14px;
            font-family: var(--sf-font-sans);
            font-size: 0.9rem;
            color: hsl(var(--sf-fg));
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;

            &::placeholder { color: hsl(var(--sf-muted-fg)); }

            &:focus {
              border-color: hsl(var(--sf-ring));
              box-shadow: 0 0 0 3px hsl(var(--sf-ring) / 0.2);
            }
          }
        }

        .submit {
          @extend .sf-btn;
          border: none;
          cursor: pointer;
        }
      }
    }
  }
}

// Salefish features partial override
.salefish_features {
  padding: 80px 0;

  @include rs768 { padding: 60px 0; }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px;

    @include rs768 { padding: 0 20px; }
  }

  .header {
    text-align: center;
    margin-bottom: 64px;

    h1 {
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      color: hsl(var(--sf-fg));
      margin-bottom: 12px;
    }

    p {
      font-size: 1.1rem;
      color: hsl(var(--sf-muted-fg));
      max-width: 540px;
      margin: 0 auto;
    }
  }

  .features { display: flex; flex-direction: column; gap: 80px; @include rs768 { gap: 48px; } }

  .feature {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: center;
    background: var(--sf-card-gradient);
    background-color: hsl(var(--sf-card));
    border: 1px solid hsl(var(--sf-card-border));
    border-radius: var(--sf-radius-lg);
    padding: 40px;
    box-shadow: var(--sf-shadow-sm);

    @include rs990 {
      grid-template-columns: 1fr;
      gap: 24px;
    }

    @include rs550 { padding: 24px; }

    .content {
      &.content_image {
        display: flex;
        align-items: center;
        justify-content: center;

        img {
          width: 100%;
          max-width: 420px;
          height: auto;
          border-radius: var(--sf-radius);
          box-shadow: var(--sf-shadow-sm);
        }
      }

      &.context_info {
        h3 {
          font-size: 0.8125rem;
          font-weight: 700;
          text-transform: uppercase;
          letter-spacing: 0.08em;
          color: hsl(var(--sf-primary));
          margin-bottom: 8px;
        }

        h2 {
          font-size: clamp(1.4rem, 3vw, 2rem);
          color: hsl(var(--sf-fg));
          margin-bottom: 14px;
          font-weight: 700;
          letter-spacing: -0.01em;
        }

        p {
          font-size: 1rem;
          line-height: 1.7;
          color: hsl(var(--sf-muted-fg));
          margin-bottom: 20px;
        }

        a.button { @extend .sf-btn; }
      }
    }
  }
}
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/pages/home.scss
git commit -m "style: rewrite homepage SCSS with glass cards, gradient stats strip"
```

---

## Task 8: Rewrite `page-newsroom.php` — card grid layout

**Files:**
- Modify: `page-newsroom.php`

**Context:** The `get_posts()` query, article data structure, filter categories, load-more AJAX (JS hooks: `#articles`, `.load_more`), and post field access patterns all stay identical.

**Step 1: Replace the entire file**

```php
<?php
/**
 * Template Name: Newsroom Page
 */
$articles = get_posts(array(
    'post_status'    => 'publish',
    'post_type'      => 'post',
    'posts_per_page' => 9,
    'paged'          => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
));

get_header();
?>

<main class="newsroom">

	<!-- HERO -->
	<section class="newsroom-hero sf-section">
		<div class="sf-container">
			<div class="newsroom-hero__inner">
				<div class="newsroom-hero__text" data-aos="fade-up">
					<span class="sf-badge sf-badge--ocean">Newsroom</span>
					<h1>Indisputable Excellence<br>from the SaleFish Newsroom</h1>
					<p>(Are you jealous yet?)</p>
				</div>
				<div class="newsroom-hero__image" data-aos="zoom-in" data-aos-delay="200">
					<img src="<?php bloginfo('template_directory'); ?>/img/newsroom/newsroom-new.png" alt="SaleFish Newsroom">
				</div>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- FILTER + ARTICLES -->
	<section class="newsroom-articles sf-section" id="articles">
		<div class="sf-container">
			<div class="newsroom-filter">
				<button class="newsroom-filter__btn active" data-filter="all">All</button>
				<button class="newsroom-filter__btn" data-filter="success-stories">Success Stories</button>
				<button class="newsroom-filter__btn" data-filter="press">Press</button>
				<button class="newsroom-filter__btn" data-filter="blog">Blog</button>
				<button class="newsroom-filter__btn" data-filter="videos">Videos</button>
			</div>

			<div class="newsroom-grid">
				<?php foreach ( $articles as $article ) :
					$id       = $article->ID;
					$title    = get_the_title($id);
					$thumb    = get_the_post_thumbnail($id, 'medium_large');
					$date     = get_the_date('F j, Y', $id);
					$link     = get_permalink($id);
					$cats     = get_the_category($id);
					$cat_name = $cats ? $cats[0]->cat_name : '';
					$cat_slug = $cats ? $cats[0]->category_nicename : '';
				?>
				<a href="<?php echo esc_url( $link ); ?>" class="sf-card newsroom-card" data-category="<?php echo esc_attr( $cat_slug ); ?>">
					<?php if ( $thumb ): ?>
					<div class="newsroom-card__image"><?php echo $thumb; ?></div>
					<?php endif; ?>
					<div class="newsroom-card__body">
						<?php if ( $cat_name ): ?>
						<span class="sf-badge sf-badge--<?php echo esc_attr( $cat_slug ); ?> newsroom-card__cat"><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
						<h3 class="newsroom-card__title"><?php echo esc_html( $title ); ?></h3>
						<p class="newsroom-card__date"><?php echo esc_html( $date ); ?></p>
					</div>
				</a>
				<?php endforeach; ?>
			</div>

			<div class="newsroom-loadmore">
				<button class="sf-btn sf-btn--secondary load_more" data-page="1" data-category="all">
					Load More
				</button>
			</div>
		</div>
	</section>
	<!-- END ARTICLES -->

</main>

<?php get_footer(); ?>
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/page-newsroom.php
git commit -m "feat: rewrite newsroom template as card grid with filter tabs"
```

---

## Task 9: Rewrite `pages/newsroom.scss`

**Files:**
- Modify: `assets/scss/pages/newsroom.scss`

**Step 1: Replace the entire file**

```scss
.newsroom-hero {
  padding-top: calc(68px + 80px);

  @include rs768 { padding-top: calc(60px + 60px); }

  &__inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: center;

    @include rs990 { grid-template-columns: 1fr; gap: 32px; }
  }

  &__text {
    display: flex;
    flex-direction: column;
    gap: 16px;

    h1 {
      font-size: clamp(2rem, 5vw, 3.2rem);
      color: hsl(var(--sf-fg));
      margin: 0;
    }

    p {
      font-size: 1.1rem;
      color: hsl(var(--sf-muted-fg));
      margin: 0;
    }
  }

  &__image {
    display: flex;
    justify-content: flex-end;

    @include rs990 { display: none; }

    img {
      width: 100%;
      max-width: 480px;
      height: auto;
      border-radius: var(--sf-radius-lg);
      box-shadow: var(--sf-shadow-md);
    }
  }
}

.newsroom-articles {
  background: hsl(var(--sf-bg));
}

.newsroom-filter {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 40px;

  &__btn {
    font-family: var(--sf-font-sans);
    font-size: 0.875rem;
    font-weight: 500;
    color: hsl(var(--sf-muted-fg));
    background: transparent;
    border: 1px solid hsl(var(--sf-border));
    border-radius: 99px;
    padding: 7px 18px;
    cursor: pointer;
    transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;

    &:hover {
      background: hsl(var(--sf-muted) / 0.5);
      color: hsl(var(--sf-fg));
    }

    &.active {
      background: hsl(var(--sf-primary));
      color: hsl(var(--sf-primary-fg));
      border-color: hsl(var(--sf-primary));
    }
  }
}

.newsroom-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 40px;

  @include rs990 { grid-template-columns: 1fr 1fr; }
  @include rs550 { grid-template-columns: 1fr; }
}

.newsroom-card {
  display: flex;
  flex-direction: column;
  text-decoration: none;
  overflow: hidden;

  &:hover { text-decoration: none; }

  &__image {
    margin: -1px -1px 0;
    overflow: hidden;
    border-radius: var(--sf-radius-lg) var(--sf-radius-lg) 0 0;

    img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
      transition: transform 0.3s ease;
    }
  }

  &:hover &__image img { transform: scale(1.03); }

  &__body {
    padding: 20px 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    flex: 1;
  }

  &__cat { align-self: flex-start; }

  &__title {
    font-family: var(--sf-font-sans);
    font-size: 1rem;
    font-weight: 600;
    color: hsl(var(--sf-fg));
    line-height: 1.4;
    margin: 0;
  }

  &__date {
    font-family: var(--sf-font-sans);
    font-size: 0.8125rem;
    color: hsl(var(--sf-muted-fg));
    margin: 0;
    margin-top: auto;
  }
}

.newsroom-loadmore {
  display: flex;
  justify-content: center;
}
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/pages/newsroom.scss
git commit -m "style: rewrite newsroom SCSS with card grid and filter tabs"
```

---

## Task 10: Rewrite `single-post.php` — prose column layout

**Files:**
- Modify: `single-post.php`

**Context:** Keep all standard WordPress functions (`get_the_title()`, `get_the_content()`, `get_the_category()`, etc.) exactly as-is.

**Step 1: Replace the entire file**

```php
<?php
/**
 * Template Name: Post Page
 */
$id         = get_the_ID();
$title      = get_the_title();
$content    = get_the_content();
$category   = get_the_category($id);
$thumb      = get_the_post_thumbnail($id, 'large');
$date       = get_the_date('F j, Y');
$author_id  = get_post_field('post_author', $id);
$author_fname = get_the_author_meta('first_name', $author_id);
$author_lname = get_the_author_meta('last_name', $author_id);

get_header();
?>

<main class="single-post">

	<!-- ARTICLE -->
	<section class="single-post__section sf-section">
		<div class="single-post__container">

			<div class="single-post__back">
				<a href="/newsroom">
					← Back to Newsroom
				</a>
			</div>

			<header class="single-post__header">
				<?php if ( $category ): ?>
				<span class="sf-badge sf-badge--<?php echo esc_attr( $category[0]->category_nicename ); ?>">
					<?php echo esc_html( $category[0]->cat_name ); ?>
				</span>
				<?php endif; ?>
				<h1><?php echo esc_html( $title ); ?></h1>
				<p class="single-post__meta">
					<?php echo esc_html( $date ); ?>
					<?php if ( $author_fname || $author_lname ): ?>
					· By <?php echo esc_html( trim("$author_fname $author_lname") ); ?>
					<?php endif; ?>
				</p>
			</header>

			<?php if ( $thumb ): ?>
			<div class="single-post__thumb">
				<?php echo $thumb; ?>
			</div>
			<?php endif; ?>

			<div class="sf-prose single-post__content">
				<?php echo wp_kses_post( apply_filters('the_content', $content) ); ?>
			</div>

		</div>
	</section>
	<!-- END ARTICLE -->

</main>

<?php get_footer(); ?>
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/single-post.php
git commit -m "feat: rewrite single post template as centred prose column"
```

---

## Task 11: Rewrite `pages/single_post.scss`

**Files:**
- Modify: `assets/scss/pages/single_post.scss`

**Step 1: Replace the entire file**

```scss
.single-post {
  &__section {
    padding-top: calc(68px + 60px);

    @include rs768 { padding-top: calc(60px + 48px); }
  }

  &__container {
    max-width: 760px;
    margin: 0 auto;
    padding: 0 40px;

    @include rs768 { padding: 0 20px; }
  }

  &__back {
    margin-bottom: 32px;

    a {
      font-family: var(--sf-font-sans);
      font-size: 0.875rem;
      font-weight: 500;
      color: hsl(var(--sf-muted-fg));
      text-decoration: none;
      transition: color 0.15s ease;

      &:hover { color: hsl(var(--sf-fg)); }
    }
  }

  &__header {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 32px;

    .sf-badge { align-self: flex-start; }

    h1 {
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      color: hsl(var(--sf-fg));
      margin: 0;
      line-height: 1.15;
      letter-spacing: -0.02em;
    }
  }

  &__meta {
    font-family: var(--sf-font-sans);
    font-size: 0.875rem;
    color: hsl(var(--sf-muted-fg));
    margin: 0;
  }

  &__thumb {
    margin-bottom: 40px;
    border-radius: var(--sf-radius-lg);
    overflow: hidden;

    img {
      width: 100%;
      height: auto;
      display: block;
    }
  }

  &__content {
    padding-bottom: 80px;

    @include rs768 { padding-bottom: 60px; }
  }
}
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/pages/single_post.scss
git commit -m "style: rewrite single post SCSS with centred prose column"
```

---

## Task 12: Rewrite `page-partners.php` — partner logos card grid

**Files:**
- Modify: `page-partners.php`

**Context:** All ACF fields kept identical: `fade_messages`, `hero_header`, `hero_description`, `hero_image`, `builders_developers`, `agents`, `agents_header`, `builders`, `builders_header`. The `textArray` JS variable for the text animation is kept.

**Step 1: Replace the entire file**

```php
<?php
/**
 * Template Name: Partners Page
 */
get_header();

$fade_msg = get_field('fade_messages');
$fade = array();
if ( $fade_msg ) {
    foreach ($fade_msg as $msg) {
        array_push($fade, $msg['text']);
    }
}
$hero_header      = get_field('hero_header');
$hero_description = get_field('hero_description');
$hero_image       = get_field('hero_image');
$builders_devs    = get_field('builders_developers');
$agents           = get_field('agents');
$agents_header    = get_field('agents_header');
$builders         = get_field('builders');
$builders_header  = get_field('builders_header');
?>

<script>let textArray = <?php echo wp_json_encode( $fade, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;</script>

<main class="partners">

	<!-- HERO -->
	<section class="partners-hero sf-section">
		<div class="sf-container">
			<div class="partners-hero__inner">
				<div class="partners-hero__text" data-aos="fade-right">
					<span class="sf-badge sf-badge--ocean">Partners</span>
					<h1><?php echo wp_kses_post( $hero_header ); ?></h1>
					<?php if ( $hero_description ): ?>
					<p><?php echo wp_kses_post( $hero_description ); ?></p>
					<?php endif; ?>
					<a class="sf-btn" href="#contact_us">Let's Partner Up</a>
				</div>
				<?php if ( $hero_image ): ?>
				<div class="partners-hero__image" data-aos="zoom-in" data-aos-delay="200">
					<img src="<?php echo esc_url( $hero_image ); ?>" alt="Partners">
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<!-- END HERO -->

	<!-- AGENTS SECTION -->
	<?php if ( $agents && $agents_header ): ?>
	<section class="partners-logos sf-section">
		<div class="sf-container">
			<div class="partners-logos__header" data-aos="fade-up">
				<h2><?php echo esc_html( $agents_header['title'] ); ?></h2>
				<p><?php echo wp_kses_post( $agents_header['content'] ); ?></p>
				<?php if ( $agents_header['button']['link'] ): ?>
				<a class="sf-btn" href="<?php echo esc_url( $agents_header['button']['link'] ); ?>">
					<?php echo esc_html( $agents_header['button']['text'] ); ?>
				</a>
				<?php endif; ?>
			</div>
			<div class="partners-logos__grid" data-aos="fade-up" data-aos-delay="100">
				<?php foreach ( $agents as $logo ):
					$src = is_array($logo) ? ($logo['url'] ?? $logo) : $logo;
				?>
				<div class="partners-logos__item">
					<img src="<?php echo esc_url( $src ); ?>" alt="">
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END AGENTS -->

	<!-- BUILDERS SECTION -->
	<?php if ( $builders && $builders_header ): ?>
	<section class="partners-logos partners-logos--alt sf-section">
		<div class="sf-container">
			<div class="partners-logos__header" data-aos="fade-up">
				<h2><?php echo esc_html( $builders_header['title'] ); ?></h2>
				<p><?php echo wp_kses_post( $builders_header['content'] ); ?></p>
				<?php if ( $builders_header['button']['link'] ): ?>
				<a class="sf-btn" href="<?php echo esc_url( $builders_header['button']['link'] ); ?>">
					<?php echo esc_html( $builders_header['button']['text'] ); ?>
				</a>
				<?php endif; ?>
			</div>
			<div class="partners-logos__grid" data-aos="fade-up" data-aos-delay="100">
				<?php foreach ( $builders as $logo ):
					$src = is_array($logo) ? ($logo['url'] ?? $logo) : $logo;
				?>
				<div class="partners-logos__item">
					<img src="<?php echo esc_url( $src ); ?>" alt="">
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- END BUILDERS -->

	<!-- CONTACT FORM -->
	<section class="sf-section" id="contact_us">
		<div class="sf-container">
			<?php get_template_part('/partials/contact-general'); ?>
		</div>
	</section>
	<!-- END CONTACT -->

</main>

<?php get_footer(); ?>
```

**Note:** No new page SCSS needed — partners uses shared utilities (`.sf-section`, `.sf-container`, `.sf-card`). Add partner-specific rules to `pages/home.scss` or create `pages/partners.scss` and import it in `app.scss` if styling is needed.

**Step 2: Add `pages/partners.scss` with minimal partner-specific styles**

Create new file `assets/scss/pages/partners.scss`:

```scss
.partners-hero {
  padding-top: calc(68px + 80px);

  @include rs768 { padding-top: calc(60px + 60px); }

  &__inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: center;

    @include rs990 { grid-template-columns: 1fr; }
  }

  &__text {
    display: flex;
    flex-direction: column;
    gap: 16px;

    h1 { font-size: clamp(2rem, 5vw, 3.2rem); color: hsl(var(--sf-fg)); margin: 0; }
    p  { font-size: 1.05rem; color: hsl(var(--sf-muted-fg)); margin: 0; }
  }

  &__image img {
    width: 100%;
    max-width: 480px;
    height: auto;
    border-radius: var(--sf-radius-lg);
    box-shadow: var(--sf-shadow-md);
  }
}

.partners-logos {
  &--alt { background: hsl(var(--sf-card)); }

  &__header {
    text-align: center;
    margin-bottom: 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;

    h2 { font-size: clamp(1.6rem, 4vw, 2.4rem); color: hsl(var(--sf-fg)); margin: 0; }
    p  { font-size: 1rem; color: hsl(var(--sf-muted-fg)); max-width: 540px; margin: 0; }
  }

  &__grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
  }

  &__item {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px 24px;
    border: 1px solid hsl(var(--sf-border));
    border-radius: var(--sf-radius-lg);
    background: hsl(var(--sf-card));
    transition: box-shadow 0.2s ease;

    &:hover { box-shadow: var(--sf-shadow-md); }

    img {
      height: 48px;
      width: auto;
      object-fit: contain;
      filter: grayscale(1);
      opacity: 0.65;
      transition: filter 0.2s ease, opacity 0.2s ease;
    }

    &:hover img {
      filter: none;
      opacity: 1;
    }
  }
}
```

**Step 3: Add partners import to `app.scss`** (append after the last `@import` line):

```scss
@import "pages/partners";
```

**Step 4: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 5: Commit**

```bash
git add wp-content/themes/salefish-2026/page-partners.php \
        wp-content/themes/salefish-2026/assets/scss/pages/partners.scss \
        wp-content/themes/salefish-2026/assets/scss/app.scss
git commit -m "feat: rewrite partners template with logo grid cards"
```

---

## Task 13: Rewrite `page-contact-us.php` — two-column card layout

**Files:**
- Modify: `page-contact-us.php`

**Context:** Keep the `get_template_part('/partials/contact-general')` call and its `#contact_us` form. Keep the Google Maps script. Keep the HQ address and phone numbers.

**Step 1: Replace the entire file**

```php
<?php
/**
 * Template Name: Contact Us Page
 */
get_header();
?>

<main class="contact-page">

	<!-- MAIN CONTACT SECTION -->
	<section class="contact-page__main sf-section" id="contact_us">
		<div class="sf-container">
			<div class="contact-page__grid">

				<!-- HQ INFO CARD -->
				<div class="sf-card contact-page__info">
					<h2>SaleFish HQ</h2>
					<div class="contact-page__address">
						<a href="https://www.google.com/maps/place/8395+Jane+St+%23203,+Concord,+ON+L4K+5Y2/@43.8121808,-79.5277303,17z"
							target="_blank" rel="noopener noreferrer">
							8395 Jane Street, Suite 202<br>
							Vaughan, ON, L4K 5Y2<br>
							Canada
						</a>
					</div>
					<div class="contact-page__contact-links">
						<a href="tel:+18778927741">1.877.892.7741</a>
						<a href="tel:+19057615364">1.905.761.5364</a>
						<a href="mailto:hello@salefish.app">hello@salefish.app</a>
					</div>
					<div id="map" class="contact-page__map"></div>
					<div class="contact-page__social">
						<a href="https://www.linkedin.com/company/salefishapp/" target="_blank" rel="noopener noreferrer">
							<img class="social_logo linkedin" src="<?php bloginfo('template_directory'); ?>/img/linkedin_logo.png" alt="LinkedIn">
						</a>
						<a href="https://www.instagram.com/salefishapp/" target="_blank" rel="noopener noreferrer">
							<img class="social_logo ig" src="<?php bloginfo('template_directory'); ?>/img/ig_logo.png" alt="Instagram">
						</a>
					</div>
				</div>

				<!-- CONTACT FORM CARD -->
				<div class="sf-card contact-page__form-card">
					<?php get_template_part('/partials/contact-general'); ?>
				</div>

			</div>
		</div>
	</section>
	<!-- END MAIN CONTACT -->

	<!-- SUPPORT CTA STRIP -->
	<section class="contact-page__support sf-gradient-primary sf-section">
		<div class="sf-container">
			<div class="contact-page__support-inner">
				<h2>Need Support?</h2>
				<p>Are you a SaleFish user and need some help? Our award-winning support team has you covered.</p>
				<a class="sf-btn" style="background:hsl(var(--sf-primary-fg));color:hsl(var(--sf-primary));"
					target="_blank" rel="noopener noreferrer"
					href="https://chatting.page/salefish">
					Get Live Chat Support
				</a>
			</div>
		</div>
	</section>
	<!-- END SUPPORT -->

</main>

<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBysfnL28j4RcQy5y3PfTPtQY_6Ao6AAog&callback=initMap"></script>

<?php get_footer(); ?>
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/page-contact-us.php
git commit -m "feat: rewrite contact page with two-column card layout"
```

---

## Task 14: Rewrite `pages/contact_us.scss`

**Files:**
- Modify: `assets/scss/pages/contact_us.scss`

**Step 1: Replace the entire file**

```scss
.contact-page {
  &__main {
    padding-top: calc(68px + 80px);

    @include rs768 { padding-top: calc(60px + 60px); }
  }

  &__grid {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 24px;
    align-items: start;

    @include rs990 { grid-template-columns: 1fr; }
  }

  &__info {
    padding: 32px;
    display: flex;
    flex-direction: column;
    gap: 20px;

    h2 {
      font-size: 1.5rem;
      font-weight: 700;
      color: hsl(var(--sf-fg));
      margin: 0;
    }
  }

  &__address {
    a {
      font-family: var(--sf-font-sans);
      font-size: 0.9375rem;
      line-height: 1.7;
      color: hsl(var(--sf-muted-fg));
      text-decoration: none;

      &:hover { color: hsl(var(--sf-fg)); }
    }
  }

  &__contact-links {
    display: flex;
    flex-direction: column;
    gap: 6px;

    a {
      font-family: var(--sf-font-sans);
      font-size: 0.9375rem;
      color: hsl(var(--sf-primary));
      text-decoration: none;

      &:hover { opacity: 0.8; }
    }
  }

  &__map {
    width: 100%;
    height: 200px;
    border-radius: var(--sf-radius);
    background: hsl(var(--sf-muted) / 0.3);
    overflow: hidden;
  }

  &__social {
    display: flex;
    gap: 12px;

    a { display: flex; opacity: 0.6; transition: opacity 0.15s ease; &:hover { opacity: 1; } }

    img {
      width: 22px;
      height: auto;
      filter: var(--sf-icon-filter, none);
    }
  }

  &__form-card {
    padding: 32px;

    @include rs550 { padding: 20px; }

    .bottom {
      background: none;
      border: none;
      padding: 0;
      box-shadow: none;

      .form {
        max-width: none;

        h3 {
          font-size: 0.8125rem;
          font-weight: 700;
          text-transform: uppercase;
          letter-spacing: 0.08em;
          color: hsl(var(--sf-primary));
          margin-bottom: 8px;
        }

        h1 {
          font-size: clamp(1.3rem, 3vw, 1.8rem);
          color: hsl(var(--sf-fg));
          margin-bottom: 28px;
          font-weight: 700;
        }

        form .row {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 16px;
          margin-bottom: 16px;

          @include rs550 { grid-template-columns: 1fr; }

          &.submit_row {
            grid-template-columns: 1fr;
            justify-items: flex-start;
          }

          .col {
            label {
              display: block;
              font-family: var(--sf-font-sans);
              font-size: 0.75rem;
              font-weight: 600;
              text-transform: uppercase;
              letter-spacing: 0.06em;
              color: hsl(var(--sf-muted-fg));
              margin-bottom: 6px;
            }

            input, select {
              width: 100%;
              box-sizing: border-box;
              background: hsl(var(--sf-input));
              border: 1px solid hsl(var(--sf-border));
              border-radius: var(--sf-radius);
              padding: 10px 14px;
              font-family: var(--sf-font-sans);
              font-size: 0.9rem;
              color: hsl(var(--sf-fg));
              outline: none;
              transition: border-color 0.15s ease, box-shadow 0.15s ease;

              &::placeholder { color: hsl(var(--sf-muted-fg)); }

              &:focus {
                border-color: hsl(var(--sf-ring));
                box-shadow: 0 0 0 3px hsl(var(--sf-ring) / 0.2);
              }
            }
          }

          .submit {
            @extend .sf-btn;
            border: none;
            cursor: pointer;
          }
        }
      }
    }
  }

  &__support {
    text-align: center;
    border-radius: 0;

    &-inner {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 16px;

      h2 {
        font-size: clamp(1.6rem, 4vw, 2.4rem);
        color: hsl(var(--sf-primary-fg));
        margin: 0;
      }

      p {
        font-size: 1.05rem;
        color: hsl(var(--sf-primary-fg) / 0.8);
        max-width: 480px;
        margin: 0;
      }
    }
  }
}
```

**Step 2: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 3: Commit**

```bash
git add wp-content/themes/salefish-2026/assets/scss/pages/contact_us.scss
git commit -m "style: rewrite contact page SCSS with two-column card layout"
```

---

## Task 15: Update `page-terms-of-use.php`, `page-privacy-policy.php`, and `terms_of_use.scss`

**Files:**
- Modify: `page-terms-of-use.php`
- Modify: `page-privacy-policy.php`
- Modify: `assets/scss/pages/terms_of_use.scss`

**Step 1: Replace `page-terms-of-use.php`**

```php
<?php
/**
 * Template Name: Terms of Use Page
 */
get_header();
?>
<main class="prose-page">
	<section class="prose-page__section sf-section">
		<div class="prose-page__container">
			<h1><?php the_title(); ?></h1>
			<div class="sf-prose">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
```

**Step 2: Replace `page-privacy-policy.php`**

```php
<?php
/**
 * Template Name: Privacy Policy Page
 */
get_header();
?>
<main class="prose-page">
	<section class="prose-page__section sf-section">
		<div class="prose-page__container">
			<h1><?php the_title(); ?></h1>
			<div class="sf-prose">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
```

**Step 3: Replace `assets/scss/pages/terms_of_use.scss`**

```scss
.prose-page {
  &__section {
    padding-top: calc(68px + 60px);

    @include rs768 { padding-top: calc(60px + 48px); }
  }

  &__container {
    max-width: 760px;
    margin: 0 auto;
    padding: 0 40px;

    @include rs768 { padding: 0 20px; }

    h1 {
      font-size: clamp(1.8rem, 4vw, 2.6rem);
      color: hsl(var(--sf-fg));
      margin-bottom: 32px;
      padding-bottom: 24px;
      border-bottom: 1px solid hsl(var(--sf-border));
    }
  }
}
```

**Step 4: Build**

```bash
cd wp-content/themes/salefish-2026 && npm run dev
```

**Step 5: Commit**

```bash
git add wp-content/themes/salefish-2026/page-terms-of-use.php \
        wp-content/themes/salefish-2026/page-privacy-policy.php \
        wp-content/themes/salefish-2026/assets/scss/pages/terms_of_use.scss
git commit -m "feat: rewrite terms/privacy pages as centred prose columns"
```

---

## Task 16: Final production build and verify

**Files:** None — verification only.

**Step 1: Run production build**

```bash
cd wp-content/themes/salefish-2026 && npm run prod
```

Expected: Compiled successfully. No errors. `dest/app.css` and `dest/app.js` updated.

**Step 2: Verify `dest/app.css` includes new classes**

```bash
grep -c "sf-section\|sf-container\|home-hero\|newsroom-card\|sf-footer\|single-post\|prose-page\|partners-logos\|contact-page" wp-content/themes/salefish-2026/dest/app.css
```

Expected: Count > 30 (confirms all new classes compiled).

**Step 3: Stage and commit compiled assets**

```bash
git add wp-content/themes/salefish-2026/dest/app.css \
        wp-content/themes/salefish-2026/dest/app.js
git commit -m "build: compile production assets for full SaleFish 2026 redesign"
```
