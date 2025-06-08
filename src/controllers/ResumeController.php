<?php
require_once __DIR__ . '/../models/Resume.php';
require_once __DIR__ . '/../models/User.php';

class ResumeController {
    private $resumeModel;
    private $userModel;
    
    public function __construct(PDO $pdo) {
        $this->resumeModel = new Resume($pdo);
        $this->userModel = new User($pdo);
    }
    
    public function createResume(int $userId): void {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Clear any previous errors
    unset($_SESSION['resume_error']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $template = $_POST['template'] ?? 'classic';
            
            // Get user data
            $user = $this->userModel->getUserById($userId);
            if (!$user) {
                throw new RuntimeException('User data not found');
            }

            // Prepare required fields
            $resumeName = $_POST['resume_name'] ?? $user['username'] . "'s Resume";
            $email = $user['email'] ?? 'no-email@example.com';

            // Create resume
            $resumeId = $this->resumeModel->create($userId, $template, $resumeName, $email);
            if (!$resumeId) {
                throw new RuntimeException('Database failed to create resume');
            }

            // Ensure no output has been sent
            if (headers_sent()) {
                throw new RuntimeException('Headers already sent, cannot redirect');
            }

            // Successful creation - redirect
            header("Location: edit.php?id=$resumeId");
            exit();

        } catch (Exception $e) {
            // Log detailed error for debugging
            error_log("Resume creation failed: " . $e->getMessage());
            
            // Store user-friendly error
            $_SESSION['resume_error'] = 'Could not create resume. Please try again.';
            
            // Ensure clean redirect
            if (!headers_sent()) {
                header('Location: dashboard.php');
                exit();
            }
            

            echo '<script>window.location.href="dashboard.php";</script>';
            exit();
        }
    }
    if (!headers_sent()) {
        header('Location: dashboard.php');
    } else {
        echo '<script>window.location.href="dashboard.php";</script>';
    }
    exit();
}
    
    public function getResumes(int $userId): array {
        try {
            $resumes = $this->resumeModel->getResumes($userId);
            return is_array($resumes) ? $resumes : [];
        } catch (PDOException $e) {
            error_log("Error fetching resumes: " . $e->getMessage());
            return [];
        }
    }
    
    public function getResume(int $id, int $userId): ?array {
        try {
            return $this->resumeModel->getResume($id, $userId) ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching resume: " . $e->getMessage());
            return null;
        }
    }
    
    public function updateSection(int $id, int $userId, string $section, array $data): bool {
        try {
            return (bool)$this->resumeModel->updateSection($id, $userId, $section, $data);
        } catch (PDOException $e) {
            error_log("Error updating section: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateTemplate(int $id, int $userId, string $template): bool {
        try {
            return (bool)$this->resumeModel->updateTemplate($id, $userId, $template);
        } catch (PDOException $e) {
            error_log("Error updating template: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteResume(int $id, int $userId): void {
        try {
            if (!$this->resumeModel->deleteResume($id, $userId)) {
                throw new RuntimeException('Delete operation failed');
            }
            header('Location: dashboard.php?deleted=1');
            exit;
        } catch (Exception $e) {
            error_log("Error deleting resume: " . $e->getMessage());
            $_SESSION['error'] = 'Could not delete resume';
            header('Location: dashboard.php');
            exit;
        }
    }
    
    public function renderResume(array $resume): string {
        try {
            // Decode all JSON fields with error handling
            $resume['personal_info'] = $this->safeJsonDecode($resume['personal_info'] ?? '{}');
            $resume['education'] = $this->safeJsonDecode($resume['education'] ?? '[]', true);
            $resume['experience'] = $this->safeJsonDecode($resume['experience'] ?? '[]', true);
            $resume['skills'] = $this->safeJsonDecode($resume['skills'] ?? '[]', true);
            $resume['projects'] = $this->safeJsonDecode($resume['projects'] ?? '[]', true);
            
            $template = $resume['template'] ?? 'classic';
            $templateFile = __DIR__ . "/../templates/$template.php";
            
            if (!file_exists($templateFile)) {
                throw new RuntimeException("Template file not found: $templateFile");
            }
            
            ob_start();
            include $templateFile;
            return ob_get_clean() ?: throw new RuntimeException('Empty template output');
            
        } catch (Throwable $e) {
            error_log("Resume rendering error: " . $e->getMessage());
            return '<div class="alert error">Error displaying resume. Please try again.</div>';
        }
    }
    
    private function safeJsonDecode(string $json, bool $associative = true) {
        $data = json_decode($json, $associative);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("JSON decode error: " . json_last_error_msg());
        }
        return $data;
    }
}