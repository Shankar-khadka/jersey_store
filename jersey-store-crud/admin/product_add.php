<?php
require __DIR__ . '/_header.php';
require __DIR__ . '/_upload.php';
$errors = [];
$p = ['name' => '', 'category' => 'Club', 'price' => '', 'size' => 'S,M,L,XL', 'stock' => 0, 'description' => '', 'image' => null];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = array_merge($p, [
        'name' => trim($_POST['name'] ?? ''), 'category' => $_POST['category'] ?? 'Club',
        'price' => $_POST['price'] ?? '', 'size' => trim($_POST['size'] ?? ''),
        'stock' => (int)($_POST['stock'] ?? 0), 'description' => trim($_POST['description'] ?? ''),
    ]);
    if ($p['name'] === '' || !is_numeric($p['price']) || $p['price'] < 0) $errors[] = 'Name and a valid price are required.';
    [$img, $err] = handle_product_upload();
    if ($err) $errors[] = $err;
    if (!$errors) {
        $pdo->prepare('INSERT INTO products (name, category, price, size, stock, description, image) VALUES (?,?,?,?,?,?,?)')
            ->execute([$p['name'], $p['category'], $p['price'], $p['size'], $p['stock'], $p['description'], $img]);
        $_SESSION['flash'] = ['success', 'Product added.'];
        redirect('products.php');
    }
}
$title = 'Add Product'; $isEdit = false;
include __DIR__ . '/_product_form.php';
require __DIR__ . '/_footer.php';
