<?php
require __DIR__ . '/_header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    if (in_array($status, ['Pending','Processing','Shipped','Delivered','Cancelled'], true)) {
        $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?')->execute([$status, (int)$_POST['order_id']]);
        if ($status === 'Delivered') $pdo->prepare("UPDATE orders SET payment_status='paid' WHERE id=? AND payment_method='cod'")->execute([(int)$_POST['order_id']]);
        $_SESSION['flash'] = ['success', 'Order updated.'];
    }
    redirect('orders.php');
}
$orders = $pdo->query('SELECT o.*, u.username FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.id DESC')->fetchAll();
$itemSt = $pdo->prepare('SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?');
?>
<h3 class="mb-3">Customer Orders</h3>
<?php foreach ($orders as $o): $itemSt->execute([$o['id']]); ?>
<div class="card mb-3">
  <div class="card-header d-flex flex-wrap justify-content-between gap-2">
    <span><strong>#<?= (int)$o['id'] ?></strong> &middot; <?= e($o['username']) ?> &middot; <?= e(date('M j, Y g:i A', strtotime($o['created_at']))) ?></span>
    <form method="post" class="d-flex gap-2">
      <input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>">
      <select name="status" class="form-select form-select-sm">
        <?php foreach (['Pending','Processing','Shipped','Delivered','Cancelled'] as $s): ?><option <?= $o['status'] === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-dark">Update</button>
    </form>
  </div>
  <div class="card-body small">
    <div class="mb-2"><?= e($o['full_name']) ?> &middot; <?= e($o['phone']) ?> &middot; <?= e($o['address']) ?></div>
    <?php foreach ($itemSt->fetchAll() as $i): ?>
      <div><?= e($i['name']) ?> (<?= e($i['size']) ?>) &times; <?= (int)$i['quantity'] ?> &mdash; <?= money($i['price'] * $i['quantity']) ?></div>
    <?php endforeach; ?>
    <div class="mt-2"><strong><?= money($o['total']) ?></strong> &middot; <?= e(strtoupper($o['payment_method'])) ?> &middot; <span class="badge text-bg-<?= $o['payment_status'] === 'paid' ? 'success' : ($o['payment_status'] === 'failed' ? 'danger' : 'warning') ?>"><?= e($o['payment_status']) ?></span></div>
  </div>
</div>
<?php endforeach; ?>
<?php if (!$orders): ?><div class="alert alert-info">No orders yet.</div><?php endif; ?>
<?php require __DIR__ . '/_footer.php'; ?>
