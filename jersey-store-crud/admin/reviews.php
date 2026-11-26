<?php
require __DIR__ . '/_header.php';
if (isset($_GET['delete'])) {
    $pdo->prepare('DELETE FROM reviews WHERE id = ?')->execute([(int)$_GET['delete']]);
    $_SESSION['flash'] = ['success', 'Review deleted.'];
    redirect('reviews.php');
}
$reviews = $pdo->query('SELECT r.*, u.username, p.name AS product FROM reviews r JOIN users u ON u.id=r.user_id JOIN products p ON p.id=r.product_id ORDER BY r.id DESC')->fetchAll();
?>
<h3 class="mb-3">Customer Reviews</h3>
<table class="table bg-white align-middle">
  <thead><tr><th>Product</th><th>User</th><th>Rating</th><th>Comment</th><th>Date</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($reviews as $r): ?>
    <tr><td><?= e($r['product']) ?></td><td><?= e($r['username']) ?></td><td><?= (int)$r['rating'] ?>/5</td><td><?= e($r['comment']) ?></td><td><?= e(date('M j, Y', strtotime($r['created_at']))) ?></td>
    <td><a href="?delete=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Delete this review?">Delete</a></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php if (!$reviews): ?><p class="text-muted">No reviews yet.</p><?php endif; ?>
<?php require __DIR__ . '/_footer.php'; ?>
