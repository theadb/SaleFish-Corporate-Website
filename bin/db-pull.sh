#!/usr/bin/env bash
# bin/db-pull.sh
# Pull a fresh database export from the live site and commit it locally.
# Usage: ./bin/db-pull.sh [--no-commit]
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
BACKUP_DIR="$REPO_ROOT/db-backups"
KEY="7cad673ab0190edb4ad37d8e7f531b0d6bd6ebf2e73d615807a754ba1e19555c"
ENDPOINT="https://salefish.app/sf-db-export?key=$KEY"
DATESTAMP="$(date -u +%Y-%m-%d)"
OUTFILE="$BACKUP_DIR/db-$DATESTAMP.sql.gz"
LATEST="$BACKUP_DIR/latest.sql.gz"

echo "→ Pulling database export from live site..."
curl -sSL "$ENDPOINT" -o "$OUTFILE"

# Verify the downloaded file looks like a gzip
if ! file "$OUTFILE" | grep -qi "gzip"; then
    echo "✗ Download failed or returned non-gzip content. Aborting."
    rm -f "$OUTFILE"
    exit 1
fi

SIZE=$(du -sh "$OUTFILE" | cut -f1)
echo "✓ Saved $OUTFILE ($SIZE)"

# Keep a stable 'latest' pointer
cp "$OUTFILE" "$LATEST"
echo "✓ Updated db-backups/latest.sql.gz"

# Commit unless --no-commit flag passed
if [[ "${1:-}" != "--no-commit" ]]; then
    cd "$REPO_ROOT"
    git add "$LATEST" "$OUTFILE"
    if git diff --cached --quiet; then
        echo "  (no changes to commit — DB unchanged)"
    else
        git commit -m "chore(db): backup live database ${DATESTAMP}"
        echo "✓ Committed to local git"
    fi
fi
