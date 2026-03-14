<?php
/**
 * Admin Login Page
 */
require_once __DIR__ . '/includes/admin-header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare('SELECT * FROM admin_users WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_username'] = $user['username'];
                redirect(SITE_URL . '/website/index.php');
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (Exception $e) {
            $error = 'Login failed. Please try again.';
        }
    }
}
?>

<div class="login-container">
    <div class="login-card">
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock-fill" style="font-size:3rem;color:#1a56db;"></i>
            <h2 class="mt-2">Admin Login</h2>
            <p class="text-muted">Shree Anukul Steels - CRM Panel</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo sanitize($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" id="username" name="username" required autofocus placeholder="Enter username">
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Enter password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>
        </form>
        <div class="text-center mt-3">
            <a href="<?php echo SITE_URL; ?>/index.php" class="text-muted"><i class="bi bi-arrow-left me-1"></i>Back to Website</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
