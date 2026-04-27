# Partners Page — Full-Width Logo Strip

**Date:** 2026-04-27

## Goal

Make partner logos substantially larger and more prominent by removing them from a constrained grid card and giving them a dedicated full-width strip. Restructure the partner cards from a 2-column asymmetric layout to a clean 3-column row.

## Design

### Structure (page-partners.php)

1. Section header — unchanged
2. `.logo-strip` — full-width band with all 6 logos
3. `.items` — 3-column partner card grid (Technology, Referral, Reseller)

The `.item_img` card is removed from the grid entirely.

### Logo strip

- **Background:** `$gray` (`#F3F5F9`), `border-radius: 16px`, `padding: 56px 64px`
- **Label:** "Tools You Already Trust. Now Working Together." — `$PSB`, `$dark_purple`, centered
- **Grid:** 6 columns desktop → 3 columns ≤990px → 2 columns ≤550px
- **Logo sizing:** `width: 100%; height: auto; max-height: 64px; object-fit: contain`
- **Hover:** `opacity: 0.75 → 1.0` + `translateY(-3px)`, `0.2s ease`
- **Entrance:** AOS `fade-up`

### Partner cards

- `grid-template-columns: repeat(3, 1fr)` — desktop
- `repeat(2, 1fr)` at ≤990px (Technology full-width on top, Referral + Reseller side by side) OR straight `repeat(3, 1fr)` collapsing to 1 column at ≤768px
- All card internals (shadow, radius, dark-purple image header, `$PSB` CTA arrow) unchanged

## Files to change

- `page-partners.php` — remove `.item_img` block, move logo HTML into `.logo-strip`, update `.items` to 3 cards
- `assets/scss/pages/partners.scss` — add `.logo-strip` styles, update `.items` grid to 3 columns
