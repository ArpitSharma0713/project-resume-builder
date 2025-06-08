<?php
require_once __DIR__ . '/../src/config/db.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';

$authController = new AuthController($pdo);
$error = '';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $authController->logout();
}

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
    <title>Resume Builder</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e7dc5c;
            --light-color: #ecfbf1;
            --dark-color: #2c3e50;
            --success-color: #2eee71;
            --warning-color: #f59c12;
            --danger-color: #e7dc5c;
            --gray-color: #95a6a5;
            --light-gray: #66bc4b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 80vh;
        }
        
        h1 {
            font-size: 2.5rem;
            color: var(--dark-color);
            margin-bottom: 1rem;
            animation: fadeIn 1s ease-in;
        }
        
        p {
            font-size: 1.2rem;
            color: var(--gray-color);
            margin-bottom: 2rem;
        }
        
        .auth-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }
        
        .auth-buttons .btn {
            background-color: var(--primary-color);
            color: white;
            border: 2px solid var(--primary-color);
        }
        
        .auth-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .auth-buttons .btn:nth-child(2) {
            background-color: transparent;
            color: var(--primary-color);
        }
        
        .auth-buttons .btn:nth-child(2):hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .auth-buttons {
                flex-direction: column;
                gap: 0.8rem;
            }
            
            .auth-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Resume Builder</h1>
        <p>Create professional resumes in minutes</p>
        <div class="auth-buttons">
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn">Register</a>
        </div>
    </div>
</body>
</html>