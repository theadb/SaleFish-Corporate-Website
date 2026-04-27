import isotope from "isotope-layout/dist/isotope.pkgd";

// ── Video Dialog ────────────────────────────────────────────────────────────
// Lazily resolves the dialog element so it works regardless of script/HTML order.
function getDialog() {
  return document.getElementById('sf-video-dialog');
}

// Convert an embed URL back to its native watch URL so the fallback link
// always sends the user to a place where the video actually plays.
function toNativeWatchUrl(embedUrl) {
  if (!embedUrl) return '';
  // YouTube: youtube[-nocookie].com/embed/<ID>?... → youtube.com/watch?v=<ID>
  var ytm = /(?:youtube\.com|youtube-nocookie\.com)\/embed\/([a-zA-Z0-9_-]{11})/.exec(embedUrl);
  if (ytm) return 'https://www.youtube.com/watch?v=' + ytm[1];
  // Vimeo: https://player.vimeo.com/video/<ID>?... → https://vimeo.com/<ID>
  var vm = /player\.vimeo\.com\/video\/(\d+)/.exec(embedUrl);
  if (vm) return 'https://vimeo.com/' + vm[1];
  return embedUrl;
}

function openVideoDialog(embedUrl) {
  var dialog = getDialog();
  if (!dialog) return;

  // Recreate the iframe from scratch each time the modal opens.
  // Safari has a quirk where dynamically setting iframe.src AFTER the iframe
  // was rendered with src="" doesn't always honor the referrerpolicy attribute
  // — leading to YouTube error 153 in Safari incognito. Building a fresh
  // iframe element with all attributes set BEFORE src is assigned guarantees
  // the correct referrer policy is applied to the very first navigation.
  var oldIframe = dialog.querySelector('.sf-video-dialog__iframe');
  if (oldIframe) {
    var newIframe = document.createElement('iframe');
    newIframe.className = 'sf-video-dialog__iframe';
    newIframe.setAttribute('allowfullscreen', '');
    newIframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
    newIframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
    newIframe.setAttribute('title', 'Video player');
    // src LAST — every other attribute must be in place before the navigation begins
    newIframe.src = embedUrl;
    oldIframe.parentNode.replaceChild(newIframe, oldIframe);
  }

  // Set fallback link to native watch URL so users always have a working option
  var fallback = dialog.querySelector('.sf-video-dialog__fallback');
  if (fallback) fallback.href = toNativeWatchUrl(embedUrl);
  dialog.hidden = false;
  // Single rAF — gives the browser one frame to paint the unhidden state,
  // then the transition class triggers the fade. Double rAF added a wasted
  // ~16ms; single rAF feels more responsive without losing the transition.
  requestAnimationFrame(function () {
    dialog.classList.add('is-open');
  });
  document.body.style.overflow = 'hidden';
}

function closeVideoDialog() {
  var dialog = getDialog();
  if (!dialog) return;
  dialog.classList.remove('is-open');
  document.body.style.overflow = '';
  // Wait for fade-out transition, then clear iframe src and hide
  setTimeout(function () {
    var iframe = dialog.querySelector('.sf-video-dialog__iframe');
    if (iframe) iframe.src = '';
    dialog.hidden = true;
  }, 300);
}

// Open on any [data-video-url] click — delegated so AJAX-loaded cards work too
document.addEventListener('click', function (e) {
  var card = e.target.closest('[data-video-url]');
  if (!card) return;
  e.preventDefault();
  openVideoDialog(card.getAttribute('data-video-url'));
});

// Close on backdrop or close button
document.addEventListener('click', function (e) {
  var dialog = getDialog();
  if (!dialog || !dialog.classList.contains('is-open')) return;
  if (e.target.closest('.sf-video-dialog__backdrop') || e.target.closest('.sf-video-dialog__close')) {
    closeVideoDialog();
  }
});

// Close on ESC
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    var dialog = getDialog();
    if (dialog && dialog.classList.contains('is-open')) closeVideoDialog();
  }
});

export const blog = {};
