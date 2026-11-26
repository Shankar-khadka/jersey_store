<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$cur = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Jersey Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/main.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-speedometer2"></i> Admin Dashboard</a>
    <ul class="navbar-nav ms-auto flex-row gap-3">
      <li class="nav-item"><a class="nav-link <?= $cur === 'products.php' ? 'active' : '' ?>" href="products.php">Products</a></li>
      <li class="nav-item"><a class="nav-link <?= $cur === 'orders.php' ? 'active' : '' ?>" href="orders.php">Orders</a></li>
      <li class="nav-item"><a class="nav-link <?= $cur === 'reviews.php' ? 'active' : '' ?>" href="reviews.php">Reviews</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php" target="_blank">View Store</a></li>
      <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>
<main class="container py-4">
<?php flash(); ?>
