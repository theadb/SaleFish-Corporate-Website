#!/usr/bin/env bash
# bin/claude-sync.sh
# Called by the Claude Code Stop hook — commits any staged theme changes
# and syncs local → live server → GitHub.
set -euo pipefail

REPO="$(cd "$(dirname "$0")/.." && pwd)"
cd "$REPO"

UNCOMMITTED=$(git status --porcelain wp-content/themes/ wp-content/plugins/wordpress-seo/ 2>/dev/null | grep -v '^\?\?' || true)
UNPUSHED=$(git log origin/main..HEAD --oneline 2>/dev/null || true)

if [ -z "$UNCOMMITTED" ] && [ -z "$UNPUSHED" ]; then
  exit 0
fi

# Stage and commit any modified theme files
if [ -n "$UNCOMMITTED" ]; then
  git add wp-content/themes/ wp-content/plugins/wordpress-seo/ 2>/dev/null || true
  if ! git diff --cached --quiet; then
    DATESTAMP="$(date -u +%Y-%m-%d)"
    git commit -m "chore: auto-sync theme changes ${DATESTAMP}"
  fi
fi

# FTP sync → live server + push to GitHub
./bin/deploy.sh --files-only

echo '{"systemMessage":"✓ Site synced — FTP + GitHub updated"}'
