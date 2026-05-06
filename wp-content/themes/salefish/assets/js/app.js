import "./general";

// Page-specific JS is now conditionally enqueued via WordPress (functions.php
// salefish_enqueue_assets) so Swiper/CountUp and other heavy libs are only
// loaded on the pages that actually need them.
// See: dest/pages/home.js, blog.js, contact_us.js, single_post.js, content.js
