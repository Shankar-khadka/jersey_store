<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
if (empty($_SESSION['cart'])) redirect('cart.php');

// Load cart lines
$lines = []; $total = 0;
foreach ($_SESSION['cart'] as $row) {
    $st = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $st->execute([$row['id']]);
    if ($p = $st->fetch()) {
        $lines[] = ['p' => $p, 'size' => $row['size'], 'qty' => $row['qty']];
        $total += $p['price'] * $row['qty'];
    }
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $method = $_POST['payment_method'] ?? '';
    if ($name === '' || $phone === '' || $address === '') $errors[] = 'Please fill in all delivery details.';
    if (!in_array($method, ['cod', 'esewa'], true)) $errors[] = 'Select a payment method.';
    foreach ($lines as $l) if ($l['p']['stock'] < $l['qty']) $errors[] = $l['p']['name'] . ' has insufficient stock.';

    if (!$errors) {
        try {
            $pdo->beginTransaction();
            $pdo->prepare('INSERT INTO orders (user_id, full_name, phone, address, total, payment_method) VALUES (?,?,?,?,?,?)')
                ->execute([$_SESSION['user_id'], $name, $phone, $address, $total, $method]);
            $orderId = (int)$pdo->lastInsertId();
            $ins = $pdo->prepare('INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?,?,?,?,?)');
            $upd = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
            foreach ($lines as $l) {
                $ins->execute([$orderId, $l['p']['id'], $l['size'], $l['qty'], $l['p']['price']]);
                $upd->execute([$l['qty'], $l['p']['id']]);
            }
            $pdo->commit();
        } catch (Throwable $ex) {
            $pdo->rollBack();
            $errors[] = 'Could not place the order. Please try again.';
        }
        if (!$errors) {
            unset($_SESSION['cart']);
            if ($method === 'esewa') redirect(BASE_URL . '/payment/esewa_initiate.php?order_id=' . $orderId);
            $_SESSION['flash'] = ['success', 'Order #' . $orderId . ' placed. Pay on delivery.'];
            redirect('orders.php');
        }
    }
}
$pageTitle = 'Checkout';
require __DIR__ . '/includes/header.php';
?>
<h3 class="mb-3">Checkout</h3>
<?php foreach ($errors as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>
<div class="row g-4">
  <div class="col-lg-7">
    <form method="post" class="card card-body">
      <div class="mb-3"><label class="form-label">Full name</label><input name="full_name" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Phone</label><input name="phone" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Delivery address</label><textarea name="address" class="form-control" rows="3" required></textarea></div>
      <label class="form-label">Payment method</label>
      <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked><label class="form-check-label" for="cod">Cash on Delivery</label></div>
      <div class="form-check mb-3"><input class="form-check-input" type="radio" name="payment_method" value="esewa" id="esewa"><label class="form-check-label" for="esewa">eSewa (online payment)</label></div>
      <button class="btn btn-warning">Place Order</button>
    </form>
  </div>
  <div class="col-lg-5">
    <div class="card card-body">
      <h5>Order Summary</h5>
      <?php foreach ($lines as $l): ?>
        <div class="d-flex justify-content-between small border-bottom py-1">
          <span><?= e($l['p']['name']) ?> (<?= e($l['size']) ?>) &times; <?= (int)$l['qty'] ?></span>
          <span><?= money($l['p']['price'] * $l['qty']) ?></span>
        </div>
      <?php endforeach; ?>
      <div class="d-flex justify-content-between fw-bold mt-2"><span>Total</span><span class="price"><?= money($total) ?></span></div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
