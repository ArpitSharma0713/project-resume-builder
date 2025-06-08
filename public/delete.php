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
$resumeController = new ResumeController($pdo);
$resumeController->deleteResume($resumeId, $_SESSION['user_id']);
?>
