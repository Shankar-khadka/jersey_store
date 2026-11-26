<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/config.php';
require_login();

$orderId = (int)($_GET['order_id'] ?? 0);
$st = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? AND payment_method = 'esewa' AND payment_status = 'pending'");
$st->execute([$orderId, $_SESSION['user_id']]);
$order = $st->fetch();
if (!$order) { $_SESSION['flash'] = ['danger', 'Order not found or already paid.']; redirect(BASE_URL . '/orders.php'); }

$amount = number_format((float)$order['total'], 2, '.', '');
$uuid = $orderId . '-' . date('ymdHis');
$pdo->prepare("INSERT INTO payments (order_id, gateway, transaction_uuid, amount, status) VALUES (?,?,?,?, 'initiated')")
    ->execute([$orderId, 'esewa', $uuid, $amount]);

$signed = "total_amount=$amount,transaction_uuid=$uuid,product_code=" . ESEWA_PRODUCT_CODE;
$fields = [
    'amount' => $amount, 'tax_amount' => '0', 'total_amount' => $amount,
    'transaction_uuid' => $uuid, 'product_code' => ESEWA_PRODUCT_CODE,
    'product_service_charge' => '0', 'product_delivery_charge' => '0',
    'success_url' => ESEWA_SUCCESS_URL, 'failure_url' => ESEWA_FAILURE_URL,
    'signed_field_names' => 'total_amount,transaction_uuid,product_code',
    'signature' => esewa_signature($signed),
];
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Redirecting to eSewa...</title></head>
<body style="font-family:sans-serif;text-align:center;padding-top:15vh">
  <p>Redirecting to eSewa&hellip;</p>
  <form id="f" action="<?= e(ESEWA_FORM_URL) ?>" method="post">
    <?php foreach ($fields as $k => $v): ?><input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>"><?php endforeach; ?>
    <noscript><button type="submit">Continue to eSewa</button></noscript>
  </form>
  <script>document.getElementById('f').submit();</script>
</body></html>
