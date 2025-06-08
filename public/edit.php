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

// Decode JSON data
$personalInfo = json_decode($resume['personal_info'] ?? '{}', true);
$education = json_decode($resume['education'] ?? '[]', true);
$experience = json_decode($resume['experience'] ?? '[]', true);
$skills = json_decode($resume['skills'] ?? '[]', true);
$projects = json_decode($resume['projects'] ?? '[]', true);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['section'])) {
        $section = $_POST['section'];
        $data = $_POST;
        
        // Process data based on section
        switch ($section) {
            case 'personal_info':
                $updateData = [
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'linkedin' => $data['linkedin'],
                    'github' => $data['github'],
                    'summary' => $data['summary']
                ];
                break;
                
            case 'education':
                $updateData = [];
                if (isset($data['institution'])) {
                    foreach ($data['institution'] as $index => $institution) {
                        $updateData[] = [
                            'institution' => $institution,
                            'degree' => $data['degree'][$index],
                            'field' => $data['field'][$index],
                            'start_date' => $data['start_date'][$index],
                            'end_date' => $data['end_date'][$index],
                            'description' => $data['description'][$index]
                        ];
                    }
                }
                break;
                
            case 'experience':
                $updateData = [];
                if (isset($data['company'])) {
                    foreach ($data['company'] as $index => $company) {
                        $updateData[] = [
                            'company' => $company,
                            'position' => $data['position'][$index],
                            'start_date' => $data['start_date'][$index],
                            'end_date' => $data['end_date'][$index],
                            'description' => $data['description'][$index]
                        ];
                    }
                }
                break;
                
            case 'skills':
                $updateData = [];
                if (isset($data['skill'])) {
                    foreach ($data['skill'] as $index => $skill) {
                        $updateData[] = [
                            'skill' => $skill,
                            'level' => $data['level'][$index]
                        ];
                    }
                }
                break;
                
            case 'projects':
                $updateData = [];
                if (isset($data['project_name'])) {
                    foreach ($data['project_name'] as $index => $project) {
                        $updateData[] = [
                            'name' => $project,
                            'technologies' => $data['technologies'][$index],
                            'description' => $data['description'][$index]
                        ];
                    }
                }
                break;
                
            case 'template':
                $resumeController->updateTemplate($resumeId, $_SESSION['user_id'], $data['template']);
                header("Location: edit.php?id=$resumeId");
                exit;
        }
        
        if ($section !== 'template') {
            $resumeController->updateSection($resumeId, $_SESSION['user_id'], $section, $updateData);
            header("Location: edit.php?id=$resumeId#$section");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resume - Resume Builder</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Edit Resume</h1>
            <nav>
                <a href="dashboard.php" class="btn">Back to Dashboard</a>
                <a href="view.php?id=<?= $resumeId ?>" class="btn">Preview</a>
            </nav>
        </header>
        
        <div class="edit-resume">
            <div class="resume-sections">
                <div class="section" id="personal_info">
                    <h2>Personal Information</h2>
                    <form method="POST">
                        <input type="hidden" name="section" value="personal_info">
                        
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($personalInfo['full_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($personalInfo['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($personalInfo['phone'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address"><?= htmlspecialchars($personalInfo['address'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="linkedin">LinkedIn URL</label>
                            <input type="url" id="linkedin" name="linkedin" value="<?= htmlspecialchars($personalInfo['linkedin'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="github">GitHub URL</label>
                            <input type="url" id="github" name="github" value="<?= htmlspecialchars($personalInfo['github'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="summary">Professional Summary</label>
                            <textarea id="summary" name="summary"><?= htmlspecialchars($personalInfo['summary'] ?? '') ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Save</button>
                    </form>
                </div>
                
                <div class="section" id="education">
                    <h2>Education</h2>
                    <form method="POST">
                        <input type="hidden" name="section" value="education">
                        
                        <div id="education-entries">
                            <?php if (empty($education)): ?>
                                <div class="education-entry">
                                    <div class="form-group">
                                        <label>Institution</label>
                                        <input type="text" name="institution[]" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Degree</label>
                                        <input type="text" name="degree[]" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Field of Study</label>
                                        <input type="text" name="field[]">
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="month" name="start_date[]">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <input type="month" name="end_date[]">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description[]"></textarea>
                                    </div>
                                    
                                    <button type="button" class="btn danger remove-entry">Remove</button>
                                </div>
                            <?php else: ?>
                                <?php foreach ($education as $entry): ?>
                                    <div class="education-entry">
                                        <div class="form-group">
                                            <label>Institution</label>
                                            <input type="text" name="institution[]" value="<?= htmlspecialchars($entry['institution'] ?? '') ?>" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Degree</label>
                                            <input type="text" name="degree[]" value="<?= htmlspecialchars($entry['degree'] ?? '') ?>" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Field of Study</label>
                                            <input type="text" name="field[]" value="<?= htmlspecialchars($entry['field'] ?? '') ?>">
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>Start Date</label>
                                                <input type="month" name="start_date[]" value="<?= htmlspecialchars($entry['start_date'] ?? '') ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>End Date</label>
                                                <input type="month" name="end_date[]" value="<?= htmlspecialchars($entry['end_date'] ?? '') ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description[]"><?= htmlspecialchars($entry['description'] ?? '') ?></textarea>
                                        </div>
                                        
                                        <button type="button" class="btn danger remove-entry">Remove</button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <button type="button" id="add-education" class="btn">Add Education</button>
                        <button type="submit" class="btn">Save</button>
                    </form>
                </div>
                
                <!-- Similar sections for Experience, Skills, and Projects -->
                <!-- Would follow the same pattern as Education section -->
                
                <div class="section" id="template">
                    <h2>Template Settings</h2>
                    <form method="POST">
                        <input type="hidden" name="section" value="template">
                        
                        <div class="form-group">
                            <label for="template">Select Template</label>
                            <select id="template" name="template">
                                <option value="classic" <?= $resume['template'] === 'classic' ? 'selected' : '' ?>>Classic</option>
                                <option value="modern" <?= $resume['template'] === 'modern' ? 'selected' : '' ?>>Modern</option>
                                <option value="creative" <?= $resume['template'] === 'creative' ? 'selected' : '' ?>>Creative</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>