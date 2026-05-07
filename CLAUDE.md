# SaleFish Marketing Website — Claude Instructions

## Deployment: always use FTP via lftp (never Cyberduck)

After any build, upload changed files directly with lftp. Credentials are stored in the macOS keychain and retrieved at deploy time — never hardcoded.

### Get credentials
```bash
FTP_PASS=$(security find-internet-password -s ftp.salefish.app -a "andrewdb@salefish.app" -w)
```

### Upload files
```bash
lftp -u "andrewdb@salefish.app,$FTP_PASS" ftp://ftp.salefish.app <<'EOF'
set ftp:ssl-allow no
set net:timeout 30
put -O /public_html/path/to/remote/dir /local/path/to/file
bye
EOF
```

### Verify uploads (always do this after uploading)
```bash
lftp -u "andrewdb@salefish.app,$FTP_PASS" ftp://ftp.salefish.app <<'EOF'
set ftp:ssl-allow no
set net:timeout 30
ls /public_html/path/to/file
bye
EOF
```
Check that the timestamp is today and the byte size matches the local file (`wc -c localfile`).

### After uploading
**Always purge the LiteSpeed cache** or visitors will see stale files:
WP Admin → LiteSpeed Cache → Toolbox → Purge All

---

## Git workflow: local → GitHub → live (never delete anything)

Every change must flow through all three in order. **Never force-push, never delete branches, never use `git clean` or `git reset --hard`.**

### Full deploy sequence
```bash
# 1. Stage and commit
git add <specific files>          # never `git add -A` — review what you're staging
git commit -m "description"

# 2. Push to GitHub (source of truth)
git push origin main

# 3. Build production assets
cd wp-content/themes/salefish && npm run prod && cd ../../..

# 4. FTP upload changed files to live server (see FTP section above)

# 5. Verify uploads (byte-size check)

# 6. Purge LiteSpeed cache
```

### Rules
- **Commit before FTP** — GitHub is the source of truth. Local + GitHub + live must always match.
- **Never delete files** from the remote server via FTP. If a file needs removing, do it intentionally and commit the removal first.
- **Never `git reset --hard`** or `git clean` — ask if unsure.
- **Never force-push** (`git push --force`) to main.
- Compiled `dest/` files ARE committed — they're served directly by WordPress with no build step on the server.

---

## Project structure

| What | Local path | Remote path |
|------|-----------|-------------|
| Theme root | `wp-content/themes/salefish/` | `/public_html/wp-content/themes/salefish/` |
| Compiled CSS/JS | `wp-content/themes/salefish/dest/` | `/public_html/wp-content/themes/salefish/dest/` |
| Service worker | `sw.js` (repo root) | `/public_html/sw.js` |

## Build

Always build with `npm run prod` (never `dev`) before deploying:

```bash
cd "wp-content/themes/salefish"
npm run prod
```

The production build minifies everything and keeps file sizes small. Dev builds are ~5× larger and should never be deployed.
