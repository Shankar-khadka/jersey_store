<?php
$pageTitle = 'My Orders';
require __DIR__ . '/includes/header.php';
require_login();
$st = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
$st->execute([$_SESSION['user_id']]);
$orders = $st->fetchAll();
$itemSt = $pdo->prepare('SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?');
?>
<h3 class="mb-3">My Orders</h3>
<?php if (!$orders): ?><div class="alert alert-info">You have no orders yet.</div><?php endif; ?>
<?php foreach ($orders as $o): $itemSt->execute([$o['id']]); $items = $itemSt->fetchAll(); ?>
  <div class="card mb-3">
    <div class="card-header d-flex flex-wrap justify-content-between">
      <strong>Order #<?= (int)$o['id'] ?></strong>
      <span>
        <span class="badge text-bg-dark"><?= e($o['status']) ?></span>
        <span class="badge text-bg-<?= $o['payment_status'] === 'paid' ? 'success' : ($o['payment_status'] === 'failed' ? 'danger' : 'warning') ?>"><?= e(strtoupper($o['payment_method'])) ?> &middot; <?= e($o['payment_status']) ?></span>
      </span>
    </div>
    <div class="card-body">
      <?php foreach ($items as $i): ?>
        <div class="d-flex justify-content-between small"><span><?= e($i['name']) ?> (<?= e($i['size']) ?>) &times; <?= (int)$i['quantity'] ?></span><span><?= money($i['price'] * $i['quantity']) ?></span></div>
      <?php endforeach; ?>
      <hr class="my-2">
      <div class="d-flex justify-content-between"><span class="text-muted small"><?= e(date('M j, Y g:i A', strtotime($o['created_at']))) ?></span><strong><?= money($o['total']) ?></strong></div>
    </div>
  </div>
<?php endforeach; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
