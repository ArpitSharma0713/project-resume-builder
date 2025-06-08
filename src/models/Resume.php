<?php
class Resume {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function create(int $userId, string $template = 'classic', string $fullName = 'Untitled Resume', string $email = 'no-email@example.com'): int|false {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("
                INSERT INTO resumes 
                (user_id, template, full_name, email, personal_info, education, experience, skills, projects) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $emptyJson = json_encode([]);
            $stmt->execute([
                $userId,
                $template,
                $fullName,
                $email,
                $emptyJson, 
                $emptyJson, 
                $emptyJson,
                $emptyJson, 
                $emptyJson 
            ]);
            
            $resumeId = $this->pdo->lastInsertId();
            $this->pdo->commit();
            
            return $resumeId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Resume creation error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getResumes(int $userId): array {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM resumes 
                WHERE user_id = ? 
                ORDER BY updated_at DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Error fetching resumes: " . $e->getMessage());
            return [];
        }
    }
    
    public function getResume(int $id, int $userId): ?array {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM resumes 
                WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([$id, $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching resume: " . $e->getMessage());
            return null;
        }
    }
    
    public function updateSection(int $id, int $userId, string $section, array $data): bool {
        try {
            $this->pdo->beginTransaction();
            
            if (!in_array($section, ['personal_info', 'education', 'experience', 'skills', 'projects'])) {
                throw new InvalidArgumentException("Invalid section: $section");
            }
            
            $stmt = $this->pdo->prepare("
                UPDATE resumes 
                SET $section = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ? AND user_id = ?
            ");
            $result = $stmt->execute([json_encode($data), $id, $userId]);
            
            $this->pdo->commit();
            return $result;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error updating section: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateTemplate(int $id, int $userId, string $template): bool {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("
                UPDATE resumes 
                SET template = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ? AND user_id = ?
            ");
            $result = $stmt->execute([$template, $id, $userId]);
            
            $this->pdo->commit();
            return $result;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error updating template: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteResume(int $id, int $userId): bool {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("
                DELETE FROM resumes 
                WHERE id = ? AND user_id = ?
            ");
            $result = $stmt->execute([$id, $userId]);
            
            $this->pdo->commit();
            return $result;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error deleting resume: " . $e->getMessage());
            return false;
        }
    }
}
?>