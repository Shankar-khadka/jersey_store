<?php
$pageTitle = 'Jerseys';
require __DIR__ . '/includes/header.php';
$cats = ['Club','National Team','Retro','Training','Goalkeeper'];
$category = $_GET['category'] ?? '';
$q = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM products WHERE 1=1';
$params = [];
if (in_array($category, $cats, true)) { $sql .= ' AND category = ?'; $params[] = $category; }
if ($q !== '') { $sql .= ' AND name LIKE ?'; $params[] = "%$q%"; }
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
  <h3 class="mb-0"><?= $category ? e($category) . ' Jerseys' : 'All Jerseys' ?></h3>
  <form class="d-flex gap-2" method="get">
    <select name="category" class="form-select">
      <option value="">All categories</option>
      <?php foreach ($cats as $c): ?><option <?= $c === $category ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?>
    </select>
    <input type="search" name="q" value="<?= e($q) ?>" class="form-control" placeholder="Search...">
    <button class="btn btn-dark">Go</button>
  </form>
</div>
<div class="row g-4">
  <?php foreach ($products as $p): include __DIR__ . '/includes/product_card.php'; endforeach; ?>
  <?php if (!$products): ?><p class="text-muted">No jerseys found.</p><?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
