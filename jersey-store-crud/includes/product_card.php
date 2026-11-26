<div class="col-6 col-md-4 col-lg-3">
  <div class="card product-card h-100">
    <a href="<?= BASE_URL ?>/product.php?id=<?= (int)$p['id'] ?>"><img src="<?= e(product_image($p['image'])) ?>" class="card-img-top" alt="<?= e($p['name']) ?>"></a>
    <div class="card-body d-flex flex-column">
      <span class="badge text-bg-secondary align-self-start mb-2"><?= e($p['category']) ?></span>
      <h6 class="card-title"><?= e($p['name']) ?></h6>
      <div class="price mb-2"><?= money($p['price']) ?></div>
      <a href="<?= BASE_URL ?>/product.php?id=<?= (int)$p['id'] ?>" class="btn btn-dark btn-sm mt-auto">View Details</a>
    </div>
  </div>
</div>
