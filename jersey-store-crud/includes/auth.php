<?php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function money($v) { return 'Rs. ' . number_format((float)$v, 2); }
function is_logged_in() { return !empty($_SESSION['user_id']) || !empty($_SESSION['admin_id']); }
function is_admin() { return !empty($_SESSION['admin_id']); }
function redirect($path) { header('Location: ' . $path); exit; }

function require_login() {
    if (empty($_SESSION['user_id'])) {
        $_SESSION['flash'] = ['warning', 'Please log in to continue.'];
        redirect(BASE_URL . '/login.php');
    }
}
function require_admin() {
    if (!is_admin()) redirect(BASE_URL . '/login.php');
}
function flash() {
    if (!empty($_SESSION['flash'])) {
        [$type, $msg] = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($type) . ' alert-dismissible fade show" role="alert">' . e($msg) .
             '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}
function product_image($img) {
    $img = $img ?: 'placeholder.svg';
    return BASE_URL . '/uploads/products/' . rawurlencode($img);
}
function cart_count() {
    $n = 0;
    foreach ($_SESSION['cart'] ?? [] as $row) $n += $row['qty'];
    return $n;
}
