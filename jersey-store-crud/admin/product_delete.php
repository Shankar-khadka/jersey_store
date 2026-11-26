<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare('SELECT image FROM products WHERE id = ?');
$st->execute([$id]);
if ($img = $st->fetchColumn()) {
    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    if (strpos($img, 'p_') === 0) @unlink(__DIR__ . '/../uploads/products/' . basename($img)); // only delete uploaded files, not seed art
    $_SESSION['flash'] = ['success', 'Product deleted.'];
} else {
    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    $_SESSION['flash'] = ['success', 'Product deleted.'];
}
redirect('products.php');
