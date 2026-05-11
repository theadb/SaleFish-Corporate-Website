#!/usr/bin/env bash
# bin/deploy.sh
# Full deploy: sync files to live server.
#
# Usage:
#   ./bin/deploy.sh
#
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
FTP_USER="appsfish"
FTP_HOST="salefish.app"
REMOTE_ROOT="public_html"

# ── Retrieve FTP password from macOS Keychain ─────────────────────────────────
FTP_PASS=$(security find-generic-password -s "ftp-salefish" -w 2>/dev/null) || {
    echo "✗ Could not retrieve FTP password from Keychain (service: ftp-salefish)"
    exit 1
}

echo "→ Syncing files to live server..."

# lftp mirror --exclude / --include use POSIX ERE regex matched against
# the FULL relative path of each file/directory. Patterns that end with /
# are matched as directory prefixes so the entire subtree is skipped in
# one shot. --exclude-glob uses shell-style globs matched against the
# basename only. Patterns are evaluated in command-line ORDER — the
# first match wins.
#
# Plugin policy: third-party plugins are managed via WP Admin on the
# live site, so we MUST NOT push from local (the local copy is just
# a snapshot for git history; pushing would silently downgrade a
# plugin that was updated via the admin UI). The custom Salefish
# plugin IS developed locally so it gets an explicit `--include`
# BEFORE the broad plugins-folder exclude — the include matches
# first and the Salefish subtree is preserved in the deploy.
#
# Same logic applied to /wp-content/uploads/ (managed entirely on
# live), /wp-content/upgrade/, and the wp-config.php file.
lftp -u "$FTP_USER","$FTP_PASS" "ftp://$FTP_HOST" <<LFTP
set ssl:verify-certificate yes
set ftp:ssl-force yes
set ftp:ssl-protect-data yes
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
    --exclude "^sftest\.php$" \
    --exclude "^readme\.html$" \
    --exclude-glob "*.md" \
    --exclude-glob "*.sql" \
    --exclude-glob "*.sql.gz" \
    --exclude-glob "*.zip" \
    --exclude-glob "*.zip-old" \
    --exclude-glob "*.php-old" \
    --exclude "^wp-content/.*-old(/|$)" \
    --exclude "^wp-content/.*\.zip-old$" \
    --exclude "^wp-content/.*\.php-old$" \
    --exclude "^wp-content/upgrade-temp-backup/" \
    --exclude "^wp-content/upgrade-temp-backup-old/" \
    --exclude "^wp-content/uploads/" \
    --exclude "^wp-content/upgrade/" \
    --include "^wp-content/plugins/Salefish/" \
    --exclude "^wp-content/plugins/" \
    "$REPO_ROOT/" "$REMOTE_ROOT/"
bye
LFTP

echo "✓ Files synced"

echo ""
echo "✅  Deploy complete."
