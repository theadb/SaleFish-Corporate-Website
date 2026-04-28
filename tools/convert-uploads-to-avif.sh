#!/usr/bin/env bash
# Generate AVIF siblings for every PNG/JPEG in a local mirror of the
# WordPress /wp-content/uploads directory. Idempotent — skips files whose
# .avif sibling is newer than the source.
#
# This complements tools/convert-images-to-avif.sh (which targets the
# theme's img/ folder). The two scripts use the same tiered quality
# strategy so the visual output is consistent.
#
# Workflow:
#   1. Mirror /public_html/wp-content/uploads down via FTP:
#        lftp -u <user>,<pass> ftp://<host> \
#          -e 'mirror --include=\\.png\$|\\.jpe?g\$ \
#                     --exclude=-[0-9]+x[0-9]+\\. \
#                     /public_html/wp-content/uploads ./uploads-mirror'
#   2. Run this script:  ./convert-uploads-to-avif.sh ./uploads-mirror
#   3. Upload only the new .avif files back:
#        lftp -u <user>,<pass> ftp://<host> \
#          -e 'mirror -R --only-missing --include=\\.avif\$ \
#                     ./uploads-mirror /public_html/wp-content/uploads'
#
# Why we filter out resized variants (-{W}x{H}.{ext}): WordPress generates
# many size variants of every uploaded image. We only need the original
# converted; the AVIF will be served regardless of which variant the <img>
# src points at, because sf_picture() looks up the AVIF by stripping the
# size suffix.

set -euo pipefail

DIR="${1:-./uploads-mirror}"

if [[ ! -d "$DIR" ]]; then
  echo "Usage: $0 <path-to-uploads-mirror>" >&2
  echo "  e.g. $0 ./uploads-mirror" >&2
  exit 1
fi

if ! command -v sips >/dev/null 2>&1; then
  echo "ERROR: sips not found (this script requires macOS)" >&2
  exit 1
fi

converted=0
skipped=0
failed=0

while IFS= read -r -d '' src; do
  size=$(stat -f %z "$src")
  avif="${src%.*}.avif"

  if [[ -f "$avif" ]] && [[ "$avif" -nt "$src" ]]; then
    skipped=$((skipped + 1))
    continue
  fi

  if   (( size >= 1048576 )); then  # >= 1 MB
    q=60; max=1600
  elif (( size >=  512000 )); then  # >= 500 KB
    q=65; max=1600
  elif (( size >=  150000 )); then  # >= 150 KB
    q=68; max=1600
  else                              # >= 50 KB
    q=72; max=0
  fi

  rel="${src#$DIR/}"
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
done < <(find "$DIR" \( -name '*.png' -o -name '*.jpg' -o -name '*.jpeg' \) -size +50k -print0)

echo ""
echo "Converted: $converted | Skipped (up to date): $skipped | Failed: $failed"
