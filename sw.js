/**
 * SaleFish Service Worker
 *
 * Strategy (rewritten 2026-04-28 to fix slow-feel on Safari):
 *
 *   • Static assets (CSS, JS, fonts, images, .avif):
 *       Cache-first. Browser cache is fastest path on repeat visits.
 *
 *   • HTML pages:
 *       Stale-while-revalidate. The user gets the cached HTML INSTANTLY
 *       (sub-millisecond) while we fetch a fresh copy in the background
 *       and update the cache for next time. This makes navigation feel
 *       native-app-instant on Safari/iOS, where the previous network-
 *       first strategy added 200-400 ms of waiting on every link click.
 *
 *   • API / admin / search-action paths:
 *       Always go to the network (no caching). These need fresh data.
 *
 *   • Offline:
 *       /offline.html served when no cache + no network.
 *
 * Bump CACHE_NAME on every shipped change so the activate handler wipes
 * old caches and clients re-fetch the new shell.
 */

const CACHE_NAME  = 'salefish-v29-2026-04-28';
const OFFLINE_URL = '/offline.html';

// Pre-cache the shell so the very first navigation can use stale-while-
// revalidate. Without this, the first visit still has to wait on the
// network (no cached entry to serve stale).
const PRECACHE_URLS = [
  '/',
  '/offline.html',
  '/wp-content/themes/salefish/dest/app.css',
  '/wp-content/themes/salefish/dest/app.js',
  '/wp-content/themes/salefish/img/dark_salefish_logo.png',
  '/wp-content/themes/salefish/img/salefish_logo.png',
  '/wp-content/themes/salefish/fonts/Poppins-Regular.woff2',
  '/wp-content/themes/salefish/fonts/Poppins-SemiBold.woff2',
  '/wp-content/themes/salefish/fonts/Poppins-Bold.woff2',
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

// ── Activate: remove old caches and take control of clients ─────────────────
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

  // Always go to network (never cache) for admin / API / dynamic paths
  if (
    url.pathname.startsWith('/wp-admin') ||
    url.pathname.startsWith('/wp-login') ||
    url.pathname.includes('/wp-json/') ||
    url.searchParams.has('action') ||
    url.searchParams.has('preview')
  ) return;

  const isAsset = /\.(css|js|woff2?|ttf|otf|png|jpe?g|gif|svg|ico|webp|avif)$/i.test(url.pathname);

  if (isAsset) {
    // Cache-first: browser cache is fastest path on repeats. Fresh copy
    // is fetched in the background to keep the cache up-to-date.
    event.respondWith(
      caches.match(request).then(cached => {
        const networkFetch = fetch(request).then(response => {
          if (response && response.status === 200) {
            const clone = response.clone();
            caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
          }
          return response;
        }).catch(() => cached); // network failed, fall back to cache
        return cached || networkFetch;
      })
    );
    return;
  }

  // ── HTML pages: stale-while-revalidate ──────────────────────────────────
  // Serve cache instantly if present; refresh the cache in the background.
  // First-ever visit: cache empty → wait for network like before.
  // Every subsequent visit: instant render, with a transparent revalidate.
  event.respondWith(
    caches.match(request).then(cached => {
      const networkFetch = fetch(request).then(response => {
        if (response && response.status === 200) {
          const clone = response.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
        }
        return response;
      }).catch(() => null);

      if (cached) {
        // Stale response goes to the user immediately. Background refresh
        // updates the cache for next time. We don't wait for it.
        return cached;
      }
      // No cached copy yet — wait for network, fall back to offline page.
      return networkFetch.then(resp =>
        resp || caches.match(OFFLINE_URL)
      );
    })
  );
});
