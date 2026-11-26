<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('products.php');
$id = (int)($_POST['id'] ?? 0);
$size = trim($_POST['size'] ?? '');
$qty = max(1, (int)($_POST['qty'] ?? 1));
$st = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$st->execute([$id]);
$p = $st->fetch();
if (!$p || $p['stock'] < 1) { $_SESSION['flash'] = ['danger', 'Product unavailable.']; redirect('products.php'); }
$sizes = array_map('trim', explode(',', $p['size']));
if (!in_array($size, $sizes, true)) $size = $sizes[0];
$key = $id . '|' . $size;
$current = $_SESSION['cart'][$key]['qty'] ?? 0;
$_SESSION['cart'][$key] = ['id' => $id, 'size' => $size, 'qty' => min($p['stock'], $current + $qty)];
$_SESSION['flash'] = ['success', 'Added to cart.'];
redirect('cart.php');
