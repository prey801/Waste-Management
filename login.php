<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        
        // Redirect based on user type
        if ($user['user_type'] === 'admin') {
            header('Location: admin/dashboard.php');
        } elseif ($user['user_type'] === 'waste_team') {
            header('Location: admin/tasks.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<?php require_once 'includes/header.php'; ?>
<section class="auth-form">
    <h2>Login to Your Account</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
    <p><a href="#">Forgot your password?</a></p>
</section>
<?php require_once 'includes/footer.php'; ?>