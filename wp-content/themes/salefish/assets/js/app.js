import { general } from "./general";
import { home } from "./pages/home";
import { content } from "./pages/content";
import { contact_us } from "./pages/contact_us";
import { blog } from "./pages/blog";
import { single_post } from "./pages/single_post";

// ── Scroll-reveal removed (2026-04-28) ──────────────────────────────────────
// All [data-aos] content now renders immediately as fully-styled HTML+CSS.
// No JS-gated visibility, no fade-ins, no IntersectionObserver, no flicker.
//
// Why: the previous JS-driven reveal system caused Safari/iOS users to see
// content "pop in" on scroll, occasionally vanish on scroll-back, and
// generally feel sluggish. Removing the system makes the site instantaneous
// and visually consistent on all devices.
//
// The CSS rules that depended on .sf-anim / .sf-has-anim / .sf-revealed
// have also been removed from _general.scss — content is visible by default.
