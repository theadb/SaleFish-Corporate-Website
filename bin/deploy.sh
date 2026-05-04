#!/usr/bin/env bash
# bin/deploy.sh
# Full deploy: sync files to live server, pull DB backup, push everything to GitHub.
#
# Usage:
#   ./bin/deploy.sh                # full deploy
#   ./bin/deploy.sh --db-only      # just pull DB backup + push
#   ./bin/deploy.sh --files-only   # just sync files (no DB)
#
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
FTP_USER="appsfish"
FTP_HOST="salefish.app"
REMOTE_ROOT="public_html"
DB_ONLY="${1:-}"
FILES_ONLY="${1:-}"

# ── Retrieve FTP password from macOS Keychain ─────────────────────────────────
FTP_PASS=$(security find-generic-password -s "ftp-salefish" -w 2>/dev/null) || {
    echo "✗ Could not retrieve FTP password from Keychain (service: ftp-salefish)"
    exit 1
}

# ── 1. Sync files to live server ──────────────────────────────────────────────
if [[ "$DB_ONLY" != "--db-only" ]]; then
    echo "→ Syncing files to live server..."

    # lftp mirror --exclude uses POSIX ERE regex matched against the FULL
    # relative path of each file/directory. Patterns that end with / are matched
    # as directory prefixes so the entire subtree is skipped in one shot.
    # --exclude-glob uses shell-style globs matched against the basename only.
    lftp -u "$FTP_USER","$FTP_PASS" "ftp://$FTP_HOST" <<LFTP
set ssl:verify-certificate no
set net:timeout 30
set net:max-retries 3
mirror --reverse --verbose --parallel=4 \
    --exclude "^db-backups/" \
    --exclude "^bin/" \
    --exclude "^docs/" \
    --exclude "(^|/)node_modules/" \
    --exclude "^\.git/" \
    --exclude "^\.claude/" \
    --exclude "^\.gitignore$" \
    --exclude "^\.DS_Store$" \
    --exclude "^wp-config\.php$" \
    --exclude-glob "*.md" \
    --exclude "^wp-content/uploads/" \
    "$REPO_ROOT/" "$REMOTE_ROOT/"
bye
LFTP

    echo "✓ Files synced"
fi

# ── 2. Pull database backup ───────────────────────────────────────────────────
if [[ "$FILES_ONLY" != "--files-only" ]]; then
    echo "→ Pulling database backup..."
    "$REPO_ROOT/bin/db-pull.sh" --no-commit
    echo "✓ Database pulled"
fi

# ── 3. Commit + push to GitHub ────────────────────────────────────────────────
cd "$REPO_ROOT"

git add -A -- \
    "$REPO_ROOT/db-backups/" \
    ":!$REPO_ROOT/db-backups/db-20*"   # don't stage dated copies, just latest

if git diff --cached --quiet; then
    echo "  (nothing new to commit)"
else
    DATESTAMP="$(date -u +%Y-%m-%d)"
    git commit -m "chore: deploy ${DATESTAMP} — sync files + db backup"
fi

echo "→ Pushing to GitHub..."
git push origin main
echo "✓ GitHub up to date"

echo ""
echo "✅  Deploy complete."
