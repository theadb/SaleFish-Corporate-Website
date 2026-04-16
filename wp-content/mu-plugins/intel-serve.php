<?php
// Only serve dashboard when accessed via /intel/ rewrite
$uri = $_SERVER['REQUEST_URI'] ?? '';
if (!preg_match('#^/intel/?(\?.*)?$#', $uri)) {
    return; // Not an /intel/ request — let WordPress continue normally
}

// Standalone handler — no WordPress needed
if (!defined('ABSPATH')) {
    define('ABSPATH', realpath(dirname(__FILE__) . '/../../') . '/');
}
http_response_code(200);
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');
$template = dirname(__FILE__) . '/../themes/salefish/template-intel-dashboard.php';
if (file_exists($template)) {
    include $template;
} else {
    echo '<p>Dashboard not found.</p>';
}
exit;
