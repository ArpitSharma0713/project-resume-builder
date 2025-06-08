<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            
            // Validate inputs
            if (empty($username) || empty($email) || empty($password)) {
                return ['error' => 'All fields are required'];
            }
            
            if ($password !== $confirmPassword) {
                return ['error' => 'Passwords do not match'];
            }
            
            if (strlen($password) < 6) {
                return ['error' => 'Password must be at least 6 characters'];
            }
            
            try {
                if ($this->userModel->register($username, $email, $password)) {
                    header('Location: login.php?registered=1');
                    exit;
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    return ['error' => 'Username or email already exists'];
                }
                return ['error' => 'Registration failed'];
            }
        }
        return [];
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            
            $user = $this->userModel->login($username, $password);
            
            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                return ['error' => 'Invalid username or password'];
            }
        }
        return [];
    }
    
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    public function getUserById($id) {
        return $this->userModel->getUserById($id);
    }
}
?>