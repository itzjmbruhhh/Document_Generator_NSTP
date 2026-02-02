<?php
// Simple auth helper — starts session and redirects to login if not authenticated
// Use project-relative URLs via base_url() when available
require_once __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE)
    session_start();

// allow access to the login page itself
$current = $_SERVER['REQUEST_URI'] ?? '';
// if not logged in, redirect to login
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // avoid redirect loop if already on login
    if (strpos($current, 'login.php') === false) {
        $loginPath = function_exists('base_url') ? base_url('pages/login.php') : '/pages/login.php';
        header('Location: ' . $loginPath);
        exit;
    }
}

?>