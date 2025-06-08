<?php
$host = 'localhost';
$dbname = 'resume_builder';
$username = 'root';
$password = 'Deadpool2023';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SHOW TABLES LIKE 'resumes'");
    if ($stmt->rowCount() > 0) {
        $columnsToAdd = [
            "template" => "VARCHAR(50) NOT NULL DEFAULT 'classic'",
            "personal_info" => "JSON",
            "updated_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];
        
        $existingColumns = [];
        $result = $pdo->query("DESCRIBE resumes");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $existingColumns[] = $row['Field'];
        }
        foreach ($columnsToAdd as $columnName => $columnDef) {
            if (!in_array($columnName, $existingColumns)) {
                try {
                    $pdo->exec("ALTER TABLE resumes ADD COLUMN $columnName $columnDef");
                    echo "Added column: $columnName\n";
                } catch (PDOException $e) {
                    echo "Failed to add column $columnName: " . $e->getMessage() . "\n";
                }
            } 
        }
    } else {
        die("Error: 'resumes' table doesn't exist");
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>  