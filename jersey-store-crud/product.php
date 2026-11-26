<?php
$pageTitle = 'Jersey Details';
require __DIR__ . '/includes/header.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { echo '<div class="alert alert-danger">Product not found.</div>'; require __DIR__ . '/includes/footer.php'; exit; }

$rev = $pdo->prepare('SELECT r.*, u.username FROM reviews r JOIN users u ON u.id = r.user_id WHERE r.product_id = ? ORDER BY r.id DESC');
$rev->execute([$id]);
$reviews = $rev->fetchAll();
$avg = $reviews ? array_sum(array_column($reviews, 'rating')) / count($reviews) : 0;
$sizes = array_filter(array_map('trim', explode(',', $p['size'])));
?>
<div class="row g-4">
  <div class="col-md-6 text-center"><img src="<?= e(product_image($p['image'])) ?>" class="img-fluid product-main-img" alt="<?= e($p['name']) ?>"></div>
  <div class="col-md-6">
    <span class="badge text-bg-secondary"><?= e($p['category']) ?></span>
    <h2 class="mt-2"><?= e($p['name']) ?></h2>
    <div class="mb-2">
      <?php for ($i = 1; $i <= 5; $i++): ?><i class="bi bi-star<?= $i <= round($avg) ? '-fill' : '' ?> star"></i><?php endfor; ?>
      <small class="text-muted">(<?= count($reviews) ?> reviews)</small>
    </div>
    <div class="h3 price"><?= money($p['price']) ?></div>
    <p><?= nl2br(e($p['description'])) ?></p>
    <p class="mb-3"><?= $p['stock'] > 0 ? '<span class="text-success">In stock (' . (int)$p['stock'] . ')</span>' : '<span class="text-danger">Out of stock</span>' ?></p>
    <?php if ($p['stock'] > 0): ?>
    <form action="add_to_cart.php" method="post" class="row g-2 align-items-end">
      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
      <div class="col-auto"><label class="form-label">Size</label>
        <select name="size" class="form-select"><?php foreach ($sizes as $s): ?><option><?= e($s) ?></option><?php endforeach; ?></select></div>
      <div class="col-auto"><label class="form-label">Qty</label>
        <input type="number" name="qty" value="1" min="1" max="<?= (int)$p['stock'] ?>" class="form-control" style="width:90px"></div>
      <div class="col-auto"><button class="btn btn-warning"><i class="bi bi-cart-plus"></i> Add to Cart</button></div>
    </form>
    <?php endif; ?>
  </div>
</div>

<hr class="my-5">
<h4>Customer Reviews</h4>
<?php if (!empty($_SESSION['user_id'])): ?>
<form action="review_add.php" method="post" class="card card-body mb-4">
  <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
  <div class="row g-2">
    <div class="col-md-2"><select name="rating" class="form-select" required>
      <?php for ($i = 5; $i >= 1; $i--): ?><option value="<?= $i ?>"><?= $i ?> star<?= $i > 1 ? 's' : '' ?></option><?php endfor; ?></select></div>
    <div class="col-md-8"><input name="comment" class="form-control" placeholder="Write your review..." required maxlength="500"></div>
    <div class="col-md-2"><button class="btn btn-dark w-100">Submit</button></div>
  </div>
</form>
<?php else: ?><p class="text-muted"><a href="login.php">Log in</a> to write a review.</p><?php endif; ?>
<?php foreach ($reviews as $r): ?>
  <div class="border-bottom py-2">
    <strong><?= e($r['username']) ?></strong>
    <?php for ($i = 1; $i <= 5; $i++): ?><i class="bi bi-star<?= $i <= $r['rating'] ? '-fill' : '' ?> star small"></i><?php endfor; ?>
    <small class="text-muted ms-2"><?= e(date('M j, Y', strtotime($r['created_at']))) ?></small>
    <div><?= e($r['comment']) ?></div>
  </div>
<?php endforeach; ?>
<?php if (!$reviews): ?><p class="text-muted">No reviews yet.</p><?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
