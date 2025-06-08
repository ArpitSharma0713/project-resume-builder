<?php
require_once __DIR__ . '/../src/config/db.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/ResumeController.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$authController = new AuthController($pdo);
$resumeController = new ResumeController($pdo);

$user = $authController->getUserById($_SESSION['user_id']);
$resumes = $resumeController->getResumes($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resumeController->createResume($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Resume Builder</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome, <?= htmlspecialchars($user['username']) ?></h1>
            <a href="logout.php" class="btn logout">Logout</a>
        </header>
        
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert success">Resume deleted successfully</div>
        <?php endif; ?>
        
        <section class="resume-actions">
            <h2>Create New Resume</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="template">Choose a template:</label>
                    <select id="template" name="template">
                        <option value="classic">Classic</option>
                        <option value="modern">Modern</option>
                        <option value="creative">Creative</option>
                    </select>
                </div>
                <button type="submit" class="btn">Create Resume</button>
            </form>
        </section>
        
        <section class="resume-list">
            <h2>Your Resumes</h2>
            <?php if (empty($resumes)): ?>
                <p>You don't have any resumes yet. Create one above!</p>
            <?php else: ?>
                <div class="resume-grid">
                    <?php foreach ($resumes as $resume): ?>
                        <div class="resume-card">
                            <h3>Resume #<?= $resume['id'] ?></h3>
                            <p>Template: <?= ucfirst($resume['template']) ?></p>
                            <p>Last updated: <?= date('M j, Y', strtotime($resume['updated_at'])) ?></p>
                            <div class="resume-actions">
                                <a href="edit.php?id=<?= $resume['id'] ?>" class="btn">Edit</a>
                                <a href="view.php?id=<?= $resume['id'] ?>" class="btn">View</a>
                                <a href="delete.php?id=<?= $resume['id'] ?>" class="btn danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>