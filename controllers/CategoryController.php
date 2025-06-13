<?php

require_once ROOT_PATH . '/models/Category.php';

class CategoryController {
    private $pdo;
    private $categoryModel;

    public function __construct() {
        $this->pdo = getDbConnection();
        $this->categoryModel = new Category($this->pdo);
        $this->checkAuth();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?action=login');
            exit();
        }
    }

    public function list() {
        $userId = $_SESSION['user_id'];
        $categories = $this->categoryModel->getCategoriesByUserId($userId);
        require_once ROOT_PATH . '/views/category_list.php';
    }

    public function add() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $userId = $_SESSION['user_id'];

            if (empty($name)) {
                $errors[] = "Category name is required.";
            } elseif (strlen($name) < 3 || strlen($name) > 50) {
                $errors[] = "Category name must be between 3 and 50 characters.";
            }

            if (empty($errors)) {
                if ($this->categoryModel->addCategory($userId, $name)) {
                    header('Location: ?controller=category&action=list');
                    exit();
                } else {
                    $errors[] = "Category name already exists for this user.";
                }
            }
        }
        require_once ROOT_PATH . '/views/add_category.php';
    }
}