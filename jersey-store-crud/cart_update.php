<?php
require_once __DIR__ . '/includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('cart.php');
$key = $_POST['key'] ?? '';
$action = $_POST['action'] ?? '';
if (isset($_SESSION['cart'][$key])) {
    if ($action === 'remove') {
        unset($_SESSION['cart'][$key]);
    } elseif ($action === 'update') {
        $qty = (int)($_POST['qty'] ?? 1);
        if ($qty < 1) { unset($_SESSION['cart'][$key]); }
        else {
            $st = $pdo->prepare('SELECT stock FROM products WHERE id = ?');
            $st->execute([$_SESSION['cart'][$key]['id']]);
            $stock = (int)$st->fetchColumn();
            $_SESSION['cart'][$key]['qty'] = min($qty, max(1, $stock));
        }
    }
}
redirect('cart.php');
