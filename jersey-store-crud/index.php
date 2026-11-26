<?php
$pageTitle = 'Home';
require __DIR__ . '/includes/header.php';
$featured = $pdo->query('SELECT * FROM products ORDER BY created_at DESC, id DESC LIMIT 8')->fetchAll();
$cats = ['Club','National Team','Retro','Training','Goalkeeper'];
?>
<section class="hero mb-4">
  <h1 class="display-5 fw-bold">Wear Your Passion</h1>
  <p class="lead mb-4">Club, national team, retro, training and goalkeeper jerseys &mdash; delivered to your door.</p>
  <a href="products.php" class="btn btn-warning btn-lg">Shop Jerseys</a>
</section>

<div class="mb-4 d-flex flex-wrap gap-2">
  <?php foreach ($cats as $c): ?>
    <a class="btn btn-outline-dark category-pill" href="products.php?category=<?= urlencode($c) ?>"><?= e($c) ?></a>
  <?php endforeach; ?>
</div>

<h3 class="mb-3">Latest Jerseys</h3>
<div class="row g-4">
  <?php foreach ($featured as $p): include __DIR__ . '/includes/product_card.php'; endforeach; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
