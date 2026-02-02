<?php
// Compute a web-root relative BASE_URL for the app directory.
// Works regardless of which page includes this file.

// Real filesystem paths
$docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
$projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));

// Derive the web path for the project relative to document root
$webPath = str_replace($docRoot, '', $projectRoot);
$webPath = $webPath === '' ? '/' : '/' . trim($webPath, '/');

// Expose BASE_URL and helper function
if (!defined('BASE_URL')) {
    define('BASE_URL', $webPath);
}

if (!function_exists('base_url')) {
    function base_url($path = '') {
        $base = rtrim(BASE_URL, '/');
        if ($path === '' || $path === '/') return $base === '' ? '/' : $base;
        return $base . '/' . ltrim($path, '/');
    }
}

?>
