<?php
require_once __DIR__ . '/includes/auth.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $full = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) $errors[] = 'Username must be 3-30 letters, numbers or underscores.';
    if ($full === '') $errors[] = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email.';
    if (strlen($pass) < 6) $errors[] = 'Password must be at least 6 characters.';
    if (strtolower($username) === 'admin') $errors[] = 'That username is reserved.';
    if (!$errors) {
        $chk = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $chk->execute([$username, $email]);
        if ($chk->fetch()) $errors[] = 'Username or email already exists.';
    }
    if (!$errors) {
        $pdo->prepare('INSERT INTO users (username, full_name, email, password) VALUES (?,?,?,?)')
            ->execute([$username, $full, $email, password_hash($pass, PASSWORD_DEFAULT)]);
        $_SESSION['flash'] = ['success', 'Account created. Please log in.'];
        redirect('login.php');
    }
}
$pageTitle = 'Register';
require __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center"><div class="col-md-6 col-lg-5">
  <div class="card shadow-sm"><div class="card-body p-4">
    <h3 class="mb-3">Create Account</h3>
    <?php foreach ($errors as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>
    <form method="post">
      <div class="mb-3"><label class="form-label">Full name</label><input name="full_name" class="form-control" value="<?= e($_POST['full_name'] ?? '') ?>" required></div>
      <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" value="<?= e($_POST['username'] ?? '') ?>" required></div>
      <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? '') ?>" required></div>
      <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
      <button class="btn btn-dark w-100">Register</button>
    </form>
    <p class="mt-3 mb-0 small">Already have an account? <a href="login.php">Login</a></p>
  </div></div>
</div></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
