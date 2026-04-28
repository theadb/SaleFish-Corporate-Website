/**
 * SaleFish Service Worker
 * Strategy:
 *   - Static assets (CSS, JS, images): Cache-first (fast repeat visits)
 *   - HTML pages: Network-first with cache fallback (always fresh content)
 *   - Offline: serve /offline.html when network and cache both fail
 */

// Bump this version whenever you ship a change that must reach existing
// visitors immediately. The activate handler deletes any cache that
// doesn't match CACHE_NAME, so a version bump triggers a full client wipe.
const CACHE_NAME    = 'salefish-v24-2026-04-28';
const OFFLINE_URL   = '/offline.html';

// Assets to pre-cache on install (shell)
const PRECACHE_URLS = [
  '/',
  '/offline.html',
  '/wp-content/themes/salefish/dest/app.css',
  '/wp-content/themes/salefish/dest/app.js',
  '/wp-content/themes/salefish/img/dark_salefish_logo.png',
  '/wp-content/themes/salefish/img/salefish_logo.png',
  '/android-chrome-192x192.png',
];

// ── Install: pre-cache shell assets ──────────────────────────────────────────
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(PRECACHE_URLS))
      .then(() => self.skipWaiting())
  );
});

// ── Activate: remove old caches ───────────────────────────────────────────────
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys
          .filter(key => key !== CACHE_NAME)
          .map(key => caches.delete(key))
      )
    ).then(() => self.clients.claim())
  );
});

// ── Fetch: route requests ─────────────────────────────────────────────────────
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);

  // Only handle same-origin GET requests
  if (request.method !== 'GET' || url.origin !== location.origin) return;

  // Skip WordPress admin, AJAX, and dynamic API paths
  if (
    url.pathname.startsWith('/wp-admin') ||
    url.pathname.startsWith('/wp-login') ||
    url.pathname.includes('/wp-json/') ||
    url.searchParams.has('action')
  ) return;

  const isAsset = /\.(css|js|woff2?|ttf|otf|png|jpe?g|gif|svg|ico|webp)$/i.test(url.pathname);

  if (isAsset) {
    // Cache-first for static assets
    event.respondWith(
      caches.match(request).then(cached => {
        if (cached) return cached;
        return fetch(request).then(response => {
          if (!response || response.status !== 200) return response;
          const clone = response.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
          return response;
        });
      })
    );
  } else {
    // Network-first for HTML pages
    event.respondWith(
      fetch(request)
        .then(response => {
          if (!response || response.status !== 200) return response;
          const clone = response.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
          return response;
        })
        .catch(() =>
          caches.match(request).then(cached =>
            cached || caches.match(OFFLINE_URL)
          )
        )
    );
  }
});
