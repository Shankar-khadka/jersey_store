<?php
require __DIR__ . '/_header.php';
$products = $pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
?>
<div class="d-flex justify-content-between mb-3"><h3>Products</h3><a href="product_add.php" class="btn btn-warning">+ Add Product</a></div>
<div class="table-responsive"><table class="table align-middle bg-white">
  <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Sizes</th><th>Stock</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($products as $p): ?>
    <tr>
      <td><img src="<?= e(product_image($p['image'])) ?>" alt=""></td>
      <td><?= e($p['name']) ?></td><td><?= e($p['category']) ?></td><td><?= money($p['price']) ?></td>
      <td><?= e($p['size']) ?></td><td><?= (int)$p['stock'] ?></td>
      <td class="text-nowrap">
        <a href="product_edit.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-outline-dark">Edit</a>
        <a href="product_delete.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Delete this product?">Delete</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table></div>
<?php require __DIR__ . '/_footer.php'; ?>
