<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/config.php';

function fail_back($msg) { $_SESSION['flash'] = ['danger', $msg]; redirect(BASE_URL . '/orders.php'); }

$raw = $_GET['data'] ?? '';
$data = json_decode(base64_decode($raw), true);
if (!is_array($data) || empty($data['transaction_uuid']) || empty($data['signed_field_names'])) fail_back('Invalid payment response.');

// 1) Verify the response signature
$parts = [];
foreach (explode(',', $data['signed_field_names']) as $f) $parts[] = $f . '=' . ($data[$f] ?? '');
if (!hash_equals(esewa_signature(implode(',', $parts)), $data['signature'] ?? '')) fail_back('Payment signature mismatch.');

// 2) Find our payment record
$st = $pdo->prepare('SELECT * FROM payments WHERE transaction_uuid = ?');
$st->execute([$data['transaction_uuid']]);
$pay = $st->fetch();
if (!$pay) fail_back('Unknown transaction.');

// 3) Verify with eSewa's status API (needs PHP cURL)
$url = ESEWA_STATUS_URL . '?' . http_build_query([
    'product_code' => ESEWA_PRODUCT_CODE, 'total_amount' => $pay['amount'], 'transaction_uuid' => $pay['transaction_uuid'],
]);
$ch = curl_init($url);
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20]);
$resp = curl_exec($ch);
curl_close($ch);
$status = json_decode((string)$resp, true);

if (is_array($status) && ($status['status'] ?? '') === 'COMPLETE') {
    $pdo->prepare("UPDATE payments SET status='success', reference_id=? WHERE id=?")->execute([$status['ref_id'] ?? null, $pay['id']]);
    $pdo->prepare("UPDATE orders SET payment_status='paid', status='Processing' WHERE id=?")->execute([$pay['order_id']]);
    $_SESSION['flash'] = ['success', 'Payment successful! Order #' . $pay['order_id'] . ' is being processed.'];
} else {
    $pdo->prepare("UPDATE payments SET status='failed' WHERE id=?")->execute([$pay['id']]);
    $pdo->prepare("UPDATE orders SET payment_status='failed' WHERE id=?")->execute([$pay['order_id']]);
    $_SESSION['flash'] = ['danger', 'Payment could not be verified.'];
}
redirect(BASE_URL . '/orders.php');
