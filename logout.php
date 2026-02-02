<?php
// simple logout that redirects back to the app login using base_url()
require_once __DIR__ . '/helper/config.php';
if (session_status() === PHP_SESSION_NONE)
    session_start();
// clear session
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}
session_destroy();

// Redirect to login inside the project using base_url()
$loginUrl = function_exists('base_url') ? base_url('pages/login.php') : '/pages/login.php';
header('Location: ' . $loginUrl);
exit;
?>