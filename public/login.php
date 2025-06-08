<?php
require_once __DIR__ . '/../src/config/db.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';

$authController = new AuthController($pdo);
$result = $authController->login();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Resume Builder</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <?php if (isset($result['error'])): ?>
            <div class="alert error"><?= $result['error'] ?></div>
        <?php elseif (isset($_GET['registered'])): ?>
            <div class="alert success">Registration successful! Please login.</div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>