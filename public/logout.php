<?php
require_once __DIR__ . '/../src/controllers/AuthController.php';

$authController = new AuthController($pdo);
$authController->logout();
?>