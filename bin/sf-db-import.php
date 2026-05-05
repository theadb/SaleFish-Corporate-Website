<?php
/**
 * One-use database importer.
 * Upload alongside sf-db-restore.sql.gz, visit once, it self-deletes.
 * SECRET token required — do not share the URL.
 */

define('SECRET', 'sf-restore-2026-05-01');
define('SQL_GZ',  __DIR__ . '/sf-db-restore.sql.gz');

// ── Auth ─────────────────────────────────────────────────────────────────────
if (($_GET['key'] ?? '') !== SECRET) {
    http_response_code(403);
    exit('Forbidden');
}

// ── Load WP DB credentials ───────────────────────────────────────────────────
$cfg = dirname(__DIR__) . '/wp-config.php';
if (!file_exists($cfg)) { exit('wp-config.php not found'); }

$raw = file_get_contents($cfg);
preg_match("/define\s*\(\s*'DB_NAME'\s*,\s*'([^']+)'/",     $raw, $m); $db_name = $m[1] ?? '';
preg_match("/define\s*\(\s*'DB_USER'\s*,\s*'([^']+)'/",     $raw, $m); $db_user = $m[1] ?? '';
preg_match("/define\s*\(\s*'DB_PASSWORD'\s*,\s*'([^']+)'/", $raw, $m); $db_pass = $m[1] ?? '';
preg_match("/define\s*\(\s*'DB_HOST'\s*,\s*'([^']+)'/",     $raw, $m); $db_host = $m[1] ?? 'localhost';

if (!$db_name || !$db_user) { exit('Could not parse DB credentials from wp-config.php'); }

// ── Decompress SQL ───────────────────────────────────────────────────────────
if (!file_exists(SQL_GZ)) { exit('SQL file not found: ' . basename(SQL_GZ)); }

$gz  = gzopen(SQL_GZ, 'rb');
$sql = '';
while (!gzeof($gz)) { $sql .= gzread($gz, 65536); }
gzclose($gz);

if (strlen($sql) < 1000) { exit('Decompressed SQL looks too small — aborting'); }

// ── Import ───────────────────────────────────────────────────────────────────
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec('SET FOREIGN_KEY_CHECKS=0; SET UNIQUE_CHECKS=0; SET sql_mode="";');

$statements = preg_split('/;\s*\n/', $sql);
$count = 0;
$errors = [];
foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if ($stmt === '' || str_starts_with($stmt, '--') || str_starts_with($stmt, '/*')) continue;
    try {
        $pdo->exec($stmt);
        $count++;
    } catch (PDOException $e) {
        $errors[] = substr($stmt, 0, 80) . ' → ' . $e->getMessage();
    }
}

// ── Self-delete ──────────────────────────────────────────────────────────────
@unlink(__FILE__);
@unlink(SQL_GZ);

// ── Report ───────────────────────────────────────────────────────────────────
header('Content-Type: text/plain');
echo "✓ Imported $count statements.\n";
echo "✓ Script and SQL file deleted.\n";
if ($errors) {
    echo "\nWarnings (" . count($errors) . "):\n";
    foreach (array_slice($errors, 0, 20) as $e) echo "  $e\n";
} else {
    echo "\nNo errors. Database restore complete.\n";
}
