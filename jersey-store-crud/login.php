<?php
require_once __DIR__ . '/includes/auth.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    // 1) Admin check (same login page as customers)
    $st = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
    $st->execute([$username]);
    $admin = $st->fetch();
    if ($admin) {
        $ok = false;
        if (strpos($admin['password'], 'PLAIN:') === 0) {
            // First login with seeded password: verify, then upgrade to a bcrypt hash
            $ok = hash_equals(substr($admin['password'], 6), $pass);
            if ($ok) $pdo->prepare('UPDATE admins SET password = ? WHERE id = ?')->execute([password_hash($pass, PASSWORD_DEFAULT), $admin['id']]);
        } else {
            $ok = password_verify($pass, $admin['password']);
        }
        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];
            redirect(BASE_URL . '/admin/index.php');
        }
        $error = 'Invalid username or password.';
    } else {
        // 2) Customer check
        $st = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $st->execute([$username]);
        $u = $st->fetch();
        if ($u && password_verify($pass, $u['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['username'] = $u['username'];
            redirect(BASE_URL . '/index.php');
        }
        $error = 'Invalid username or password.';
    }
}
$pageTitle = 'Login';
require __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center"><div class="col-md-6 col-lg-4">
  <div class="card shadow-sm"><div class="card-body p-4">
    <h3 class="mb-3">Login</h3>
    <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required autofocus></div>
      <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
      <button class="btn btn-dark w-100">Login</button>
    </form>
    <p class="mt-3 mb-0 small">New here? <a href="register.php">Create an account</a></p>
  </div></div>
</div></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
