<?php
require __DIR__ . '/_header.php';
$stats = [
  'Products' => $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
  'Orders' => $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
  'Customers' => $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
  'Reviews' => $pdo->query('SELECT COUNT(*) FROM reviews')->fetchColumn(),
];
$revenue = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE payment_status='paid' OR (payment_method='cod' AND status='Delivered')")->fetchColumn();
$recent = $pdo->query('SELECT o.*, u.username FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.id DESC LIMIT 5')->fetchAll();
?>
<h3 class="mb-4">Welcome, <?= e($_SESSION['admin_name']) ?></h3>
<div class="row g-3 mb-4">
  <?php foreach ($stats as $label => $n): ?>
    <div class="col-6 col-md-3"><div class="card text-center"><div class="card-body"><div class="display-6"><?= (int)$n ?></div><div class="text-muted"><?= e($label) ?></div></div></div></div>
  <?php endforeach; ?>
</div>
<div class="alert alert-secondary">Collected revenue: <strong><?= money($revenue) ?></strong></div>
<div class="d-flex gap-2 mb-4">
  <a href="product_add.php" class="btn btn-warning">+ Add Product</a>
  <a href="products.php" class="btn btn-outline-dark">Manage Products</a>
  <a href="orders.php" class="btn btn-outline-dark">View Orders</a>
</div>
<h5>Recent Orders</h5>
<table class="table bg-white">
  <thead><tr><th>#</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th></tr></thead>
  <tbody><?php foreach ($recent as $o): ?>
    <tr><td><?= (int)$o['id'] ?></td><td><?= e($o['username']) ?></td><td><?= money($o['total']) ?></td><td><?= e(strtoupper($o['payment_method'])) ?> / <?= e($o['payment_status']) ?></td><td><?= e($o['status']) ?></td></tr>
  <?php endforeach; ?></tbody>
</table>
<?php require __DIR__ . '/_footer.php'; ?>
