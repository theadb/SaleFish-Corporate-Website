import isotope from "isotope-layout/dist/isotope.pkgd";

// ── Video Dialog ────────────────────────────────────────────────────────────
// Lazily resolves the dialog element so it works regardless of script/HTML order.
function getDialog() {
  return document.getElementById('sf-video-dialog');
}

function openVideoDialog(embedUrl) {
  var dialog = getDialog();
  if (!dialog) return;
  var iframe = dialog.querySelector('.sf-video-dialog__iframe');
  if (iframe) iframe.src = embedUrl;
  dialog.hidden = false;
  // rAF so the browser paints the unhidden state before the transition class is added
  requestAnimationFrame(function () {
    requestAnimationFrame(function () {
      dialog.classList.add('is-open');
    });
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
