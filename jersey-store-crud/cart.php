<?php
$pageTitle = 'Cart';
require __DIR__ . '/includes/header.php';
$items = []; $total = 0;
foreach ($_SESSION['cart'] ?? [] as $key => $row) {
    $st = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $st->execute([$row['id']]);
    if ($p = $st->fetch()) {
        $sub = $p['price'] * $row['qty'];
        $total += $sub;
        $items[] = ['key' => $key, 'p' => $p, 'size' => $row['size'], 'qty' => $row['qty'], 'sub' => $sub];
    }
}
?>
<h3 class="mb-3">Shopping Cart</h3>
<?php if (!$items): ?>
  <div class="alert alert-info">Your cart is empty. <a href="products.php">Browse jerseys</a></div>
<?php else: ?>
<div class="table-responsive">
<table class="table align-middle bg-white">
  <thead><tr><th></th><th>Jersey</th><th>Size</th><th>Price</th><th style="width:170px">Qty</th><th>Subtotal</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($items as $it): ?>
    <tr>
      <td><img src="<?= e(product_image($it['p']['image'])) ?>" alt=""></td>
      <td><?= e($it['p']['name']) ?></td>
      <td><?= e($it['size']) ?></td>
      <td><?= money($it['p']['price']) ?></td>
      <td>
        <form action="cart_update.php" method="post" class="d-flex gap-1">
          <input type="hidden" name="key" value="<?= e($it['key']) ?>"><input type="hidden" name="action" value="update">
          <input type="number" name="qty" value="<?= (int)$it['qty'] ?>" min="1" class="form-control form-control-sm">
          <button class="btn btn-sm btn-outline-dark">Update</button>
        </form>
      </td>
      <td><?= money($it['sub']) ?></td>
      <td>
        <form action="cart_update.php" method="post">
          <input type="hidden" name="key" value="<?= e($it['key']) ?>"><input type="hidden" name="action" value="remove">
          <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot><tr><th colspan="5" class="text-end">Total</th><th colspan="2" class="price"><?= money($total) ?></th></tr></tfoot>
</table>
</div>
<div class="d-flex justify-content-between">
  <a href="products.php" class="btn btn-outline-dark">Continue Shopping</a>
  <a href="checkout.php" class="btn btn-warning">Proceed to Checkout</a>
</div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
