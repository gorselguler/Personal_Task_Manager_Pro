<?php

require_once ROOT_PATH . '/models/User.php';

class UserController {
    private $pdo;
    private $userModel;

    public function __construct() {
        $this->pdo = getDbConnection();
        $this->userModel = new User($this->pdo);
    }

    public function register() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            if (empty($username)) {
                $errors[] = "Username is required.";
            } elseif (strlen($username) < 3 || strlen($username) > 50) {
                $errors[] = "Username must be between 3 and 50 characters.";
            }

            if (empty($email)) {
                $errors[] = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }

            if (empty($password)) {
                $errors[] = "Password is required.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters long.";
            }

            if (empty($errors)) {
                if ($this->userModel->register($username, $password, $email)) {
                    header('Location: ?action=login');
                    exit();
                } else {
                    $errors[] = "Username or email already taken.";
                }
            }
        }
        require_once ROOT_PATH . '/views/register.php';
    }

    public function login() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            if (empty($username)) {
                $errors[] = "Username is required.";
            }
            if (empty($password)) {
                $errors[] = "Password is required.";
            }

            if (empty($errors)) {
                $userId = $this->userModel->login($username, $password);
                if ($userId) {
                    header('Location: ?controller=task&action=list');
                    exit();
                } else {
                    $errors[] = "Invalid username or password.";
                }
            }
        }
        require_once ROOT_PATH . '/views/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: ?action=login');
        exit();
    }
}