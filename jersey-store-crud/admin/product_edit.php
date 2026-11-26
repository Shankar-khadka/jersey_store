<?php
require __DIR__ . '/_header.php';
require __DIR__ . '/_upload.php';
$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$st->execute([$id]);
$p = $st->fetch();
if (!$p) { $_SESSION['flash'] = ['danger', 'Product not found.']; redirect('products.php'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $p['image'];
    $p = array_merge($p, [
        'name' => trim($_POST['name'] ?? ''), 'category' => $_POST['category'] ?? 'Club',
        'price' => $_POST['price'] ?? '', 'size' => trim($_POST['size'] ?? ''),
        'stock' => (int)($_POST['stock'] ?? 0), 'description' => trim($_POST['description'] ?? ''),
    ]);
    if ($p['name'] === '' || !is_numeric($p['price']) || $p['price'] < 0) $errors[] = 'Name and a valid price are required.';
    [$img, $err] = handle_product_upload();
    if ($err) $errors[] = $err;
    if (!$errors) {
        $newImage = $img ?: $old;
        $pdo->prepare('UPDATE products SET name=?, category=?, price=?, size=?, stock=?, description=?, image=? WHERE id=?')
            ->execute([$p['name'], $p['category'], $p['price'], $p['size'], $p['stock'], $p['description'], $newImage, $id]);
        if ($img && $old && strpos($old, 'p_') === 0) @unlink(__DIR__ . '/../uploads/products/' . basename($old));
        $_SESSION['flash'] = ['success', 'Product updated.'];
        redirect('products.php');
    }
}
$title = 'Edit Product'; $isEdit = true;
include __DIR__ . '/_product_form.php';
require __DIR__ . '/_footer.php';
