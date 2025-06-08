<?php
require_once __DIR__ . '/../src/config/db.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/ResumeController.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$resumeId = $_GET['id'];
$authController = new AuthController($pdo);
$resumeController = new ResumeController($pdo);

$resume = $resumeController->getResume($resumeId, $_SESSION['user_id']);

if (!$resume) {
    header('Location: dashboard.php');
    exit;
}

$renderedResume = $resumeController->renderResume($resume);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Resume - Resume Builder</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Your Resume</h1>
            <nav>
                <a href="dashboard.php" class="btn">Back to Dashboard</a>
                <a href="edit.php?id=<?= $resumeId ?>" class="btn">Edit</a>
                <button onclick="window.print()" class="btn">Print/PDF</button>
            </nav>
        </header>
        
        <div class="resume-preview">
            <?= $renderedResume ?>
        </div>
    </div>
</body>
</html>