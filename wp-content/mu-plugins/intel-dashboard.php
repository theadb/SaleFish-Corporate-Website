<?php
/**
 * Plugin Name: Intel Dashboard Route
 * Description: Serves the SaleFish competitor intelligence dashboard at /intel/
 */

// Run at file load time — before WordPress or any plugin sets headers/status
$_intel_uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
if (rtrim($_intel_uri, '/') === '/intel') {
    $template = dirname(__FILE__) . '/../themes/salefish/template-intel-dashboard.php';
    if (file_exists($template)) {
        while (ob_get_level() > 0) ob_end_clean();
        header('HTTP/1.1 200 OK', true, 200);
        header('Content-Type: text/html; charset=utf-8');
        include $template;
        exit;
    }
}
