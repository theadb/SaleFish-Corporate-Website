import { general } from "./general";
import { home } from "./pages/home";
import { content } from "./pages/content";
import { contact_us } from "./pages/contact_us";
import { blog } from "./pages/blog";
import { single_post } from "./pages/single_post";

// Image fade-in via opacity-and-class was removed because it caused content
// to disappear when bfcache restored a page or when any image's `load` event
// didn't fire (cached imgs, swiper-cloned slides, etc.). All visual fade-ins
// now happen via CSS-only animations that are decoupled from JS — see the
// hero text rise + skeleton shimmer in _general.scss.
