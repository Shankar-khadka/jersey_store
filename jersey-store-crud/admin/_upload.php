<?php
// Handles product image upload. Returns [filename|null, error|null].
function handle_product_upload(string $field = 'image'): array {
    if (empty($_FILES[$field]['name'])) return [null, null];
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) return [null, 'Image upload failed.'];
    if ($f['size'] > 3 * 1024 * 1024) return [null, 'Image must be under 3 MB.'];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) return [null, 'Only JPG, PNG, WEBP or GIF images are allowed.'];
    if (@getimagesize($f['tmp_name']) === false) return [null, 'File is not a valid image.'];
    $dir = __DIR__ . '/../uploads/products/';
    if (!is_dir($dir)) mkdir($dir, 0775, true);
    $name = 'p_' . bin2hex(random_bytes(8)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], $dir . $name)) return [null, 'Could not save the image.'];
    return [$name, null];
}
