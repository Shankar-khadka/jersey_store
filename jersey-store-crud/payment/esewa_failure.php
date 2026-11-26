<?php
require_once __DIR__ . '/../includes/auth.php';
$raw = $_GET['data'] ?? '';
$data = json_decode(base64_decode($raw), true);
if (is_array($data) && !empty($data['transaction_uuid'])) {
    $st = $pdo->prepare('SELECT * FROM payments WHERE transaction_uuid = ?');
    $st->execute([$data['transaction_uuid']]);
    if ($pay = $st->fetch()) {
        $pdo->prepare("UPDATE payments SET status='failed' WHERE id=?")->execute([$pay['id']]);
        $pdo->prepare("UPDATE orders SET payment_status='failed' WHERE id=? AND payment_status='pending'")->execute([$pay['order_id']]);
    }
}
$_SESSION['flash'] = ['danger', 'eSewa payment was cancelled or failed.'];
redirect(BASE_URL . '/orders.php');
