#!/usr/bin/env bash
# Generate AVIF siblings for every PNG/JPEG in the theme img/ directory
# above MIN_BYTES. Idempotent — skips files whose .avif sibling is newer.
#
# Quality tiers:
#   • Hero/feature photographs (>= 1 MB)  → q60, downsized to 1600w max
#   • Mid-size composites      (>= 500 KB) → q65, downsized to 1600w max
#   • Smaller assets           (>= 80 KB)  → q70, no resize
#
# Why these defaults:
#   • Most heroes render at ≤ 1280 CSS px on desktop, so a 1600w source
#     covers 2x DPR comfortably without wasting bytes on Retina overflow.
#   • Quality 60 in AVIF ≈ quality 85 in JPEG (well above any visible
#     compression threshold for photographic content).
#   • Files under 80 KB barely benefit from re-encoding.
#
# Tooling: macOS sips (built-in, no Homebrew required). sips supports
# AVIF natively on macOS 13+.

set -euo pipefail

THEME_DIR="$(cd "$(dirname "$0")/../wp-content/themes/salefish" && pwd)"
IMG_DIR="$THEME_DIR/img"

if ! command -v sips >/dev/null 2>&1; then
  echo "ERROR: sips not found (this script requires macOS)" >&2
  exit 1
fi

converted=0
skipped=0
failed=0

# Find every PNG/JPG over 80 KB
while IFS= read -r -d '' src; do
  size=$(stat -f %z "$src")
  avif="${src%.*}.avif"

  # Skip if AVIF exists and is newer than source
  if [[ -f "$avif" ]] && [[ "$avif" -nt "$src" ]]; then
    skipped=$((skipped + 1))
    continue
  fi

  # Pick quality and resize threshold by source size
  if   (( size >= 1048576 )); then  # >= 1 MB
    q=60; max=1600
  elif (( size >=  512000 )); then  # >= 500 KB
    q=65; max=1600
  else                              # >= 80 KB
    q=70; max=0
  fi

  rel="${src#$THEME_DIR/}"
  printf "→ %s (%.1f KB, q%d%s)\n" "$rel" "$(echo "$size / 1024" | bc -l)" "$q" \
    "$([[ $max -gt 0 ]] && echo ", ≤${max}w" || echo "")"

  if [[ $max -gt 0 ]]; then
    if sips -Z "$max" -s format avif -s formatOptions "$q" "$src" --out "$avif" >/dev/null 2>&1; then
      converted=$((converted + 1))
    else
      failed=$((failed + 1))
      echo "  ✗ failed" >&2
    fi
  else
    if sips -s format avif -s formatOptions "$q" "$src" --out "$avif" >/dev/null 2>&1; then
      converted=$((converted + 1))
    else
      failed=$((failed + 1))
      echo "  ✗ failed" >&2
    fi
  fi
done < <(find "$IMG_DIR" \( -name '*.png' -o -name '*.jpg' -o -name '*.jpeg' \) -size +80k -print0)

echo ""
echo "Converted: $converted | Skipped (up to date): $skipped | Failed: $failed"
