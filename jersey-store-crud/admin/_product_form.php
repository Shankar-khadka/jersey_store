<?php /* expects $p (array), $errors, $title, $isEdit */ ?>
<h3 class="mb-3"><?= e($title) ?></h3>
<?php foreach ($errors as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" enctype="multipart/form-data" class="card card-body" style="max-width:720px">
  <div class="mb-3"><label class="form-label">Product name</label><input name="name" class="form-control" value="<?= e($p['name']) ?>" required></div>
  <div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Category</label>
      <select name="category" class="form-select">
        <?php foreach (['Club','National Team','Retro','Training','Goalkeeper'] as $c): ?><option <?= $p['category'] === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?>
      </select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Price (Rs.)</label><input type="number" step="0.01" min="0" name="price" class="form-control" value="<?= e($p['price']) ?>" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Sizes (comma separated)</label><input name="size" class="form-control" value="<?= e($p['size']) ?>" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Stock</label><input type="number" min="0" name="stock" class="form-control" value="<?= e($p['stock']) ?>" required></div>
  </div>
  <div class="mb-3"><label class="form-label">Description</label><textarea name="description" rows="4" class="form-control"><?= e($p['description']) ?></textarea></div>
  <div class="mb-3"><label class="form-label">Image</label>
    <?php if ($isEdit && $p['image']): ?><div class="mb-2"><img src="<?= e(product_image($p['image'])) ?>" height="90" alt=""></div><?php endif; ?>
    <input type="file" name="image" class="form-control" accept="image/*"></div>
  <div class="d-flex gap-2"><button class="btn btn-dark">Save</button><a href="products.php" class="btn btn-outline-secondary">Cancel</a></div>
</form>
