<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('products.php');
$pid = (int)($_POST['product_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
if ($pid > 0 && $rating >= 1 && $rating <= 5 && $comment !== '') {
    $chk = $pdo->prepare('SELECT id FROM products WHERE id = ?');
    $chk->execute([$pid]);
    if ($chk->fetch()) {
        $pdo->prepare('INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?,?,?,?)')
            ->execute([$pid, $_SESSION['user_id'], $rating, mb_substr($comment, 0, 500)]);
        $_SESSION['flash'] = ['success', 'Thanks for your review!'];
    }
}
redirect('product.php?id=' . $pid);
