<?php

require_once ROOT_PATH . '/models/Task.php';
require_once ROOT_PATH . '/models/Category.php';

class TaskController {
    private $pdo;
    private $taskModel;
    private $categoryModel;

    public function __construct() {
        $this->pdo = getDbConnection();
        $this->taskModel = new Task($this->pdo);
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
        $sortBy = $_GET['sort_by'] ?? 'due_date';
        $sortOrder = $_GET['sort_order'] ?? 'ASC';

        $tasks = $this->taskModel->getTasksByUserId($userId, $sortBy, $sortOrder);
        require_once ROOT_PATH . '/views/task_list.php';
    }

    public function add() {
        $userId = $_SESSION['user_id'];
        $categories = $this->categoryModel->getCategoriesByUserId($userId);
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $categoryId = $_POST['category_id'] === '' ? NULL : (int)$_POST['category_id'];
            $priority = $_POST['priority'];
            $dueDateInputDate = trim($_POST['due_date_date']);
            $dueDateInputTime = trim($_POST['due_date_time']);
            $isAllDay = isset($_POST['is_all_day']);

            $dueDate = NULL;
            if ($isAllDay) {
                if (!empty($dueDateInputDate)) {
                    $dueDate = date('Y-m-d 00:00:00', strtotime($dueDateInputDate));
                } else {
                    $dueDate = date('Y-m-d 00:00:00');
                }
            } elseif (!empty($dueDateInputDate)) {
                $fullDateTime = $dueDateInputDate;
                if (!empty($dueDateInputTime)) {
                    $fullDateTime .= ' ' . $dueDateInputTime;
                } else {
                    $fullDateTime .= ' 00:00:00';
                }
                $timestamp = strtotime($fullDateTime);
                if ($timestamp !== false) {
                    $dueDate = date('Y-m-d H:i:s', $timestamp);
                }
            }

            if (empty($title)) {
                $errors[] = "Title is required.";
            } elseif (strlen($title) < 3 || strlen($title) > 100) {
                $errors[] = "Title must be between 3 and 100 characters.";
            }

            if (!empty($description) && strlen($description) > 1000) {
                $errors[] = "Description cannot exceed 1000 characters.";
            }

            $validPriorities = ['low', 'medium', 'high'];
            if (!in_array($priority, $validPriorities)) {
                $errors[] = "Invalid priority selected.";
            }

            if (!$isAllDay && empty($dueDateInputDate)) {
                $errors[] = "Due date is required if not 'All Day' task.";
            } elseif (!$isAllDay && !empty($dueDateInputDate) && strtotime($dueDateInputDate) === false) {
                 $errors[] = "Invalid date format.";
            } elseif (!$isAllDay && !empty($dueDateInputTime) && strtotime('2000-01-01 ' . $dueDateInputTime) === false) {
                $errors[] = "Invalid time format.";
            }


            if (empty($errors)) {
                if ($this->taskModel->addTask($userId, $categoryId, $title, $description, $priority, $dueDate)) {
                    header('Location: ?controller=task&action=list');
                    exit();
                } else {
                    $errors[] = "Failed to add task.";
                }
            }
        }
        require_once ROOT_PATH . '/views/add_task.php';
    }

    public function edit() {
        $userId = $_SESSION['user_id'];
        $taskId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $task = $this->taskModel->getTaskByIdAndUserId($taskId, $userId);

        if (!$task) {
            http_response_code(404);
            echo "Error: Task not found or you don't have permission to edit it.";
            exit();
        }

        $categories = $this->categoryModel->getCategoriesByUserId($userId);
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $categoryId = $_POST['category_id'] === '' ? NULL : (int)$_POST['category_id'];
            $priority = $_POST['priority'];
            $dueDateInputDate = trim($_POST['due_date_date']);
            $dueDateInputTime = trim($_POST['due_date_time']);
            $status = $_POST['status'];
            $isAllDay = isset($_POST['is_all_day']);

            $dueDate = NULL;
            if ($isAllDay) {
                if (!empty($dueDateInputDate)) {
                    $dueDate = date('Y-m-d 00:00:00', strtotime($dueDateInputDate));
                } else {
                    $dueDate = date('Y-m-d 00:00:00', strtotime($task['due_date'] ?? 'now'));
                }
            } elseif (!empty($dueDateInputDate)) {
                $fullDateTime = $dueDateInputDate;
                if (!empty($dueDateInputTime)) {
                    $fullDateTime .= ' ' . $dueDateInputTime;
                } else {
                    $fullDateTime .= ' 00:00:00';
                }
                $timestamp = strtotime($fullDateTime);
                if ($timestamp !== false) {
                    $dueDate = date('Y-m-d H:i:s', $timestamp);
                }
            }

            if (empty($title)) {
                $errors[] = "Title is required.";
            } elseif (strlen($title) < 3 || strlen($title) > 100) {
                $errors[] = "Title must be between 3 and 100 characters.";
            }

            if (!empty($description) && strlen($description) > 1000) {
                $errors[] = "Description cannot exceed 1000 characters.";
            }

            $validPriorities = ['low', 'medium', 'high'];
            if (!in_array($priority, $validPriorities)) {
                $errors[] = "Invalid priority selected.";
            }

            $validStatuses = ['todo', 'in_progress', 'done'];
            if (!in_array($status, $validStatuses)) {
                $errors[] = "Invalid status selected.";
            }

            if (!$isAllDay && empty($dueDateInputDate)) {
                $errors[] = "Due date is required if not 'All Day' task.";
            } elseif (!$isAllDay && !empty($dueDateInputDate) && strtotime($dueDateInputDate) === false) {
                $errors[] = "Invalid date format.";
            } elseif (!$isAllDay && !empty($dueDateInputTime) && strtotime('2000-01-01 ' . $dueDateInputTime) === false) {
                $errors[] = "Invalid time format.";
            }

            if (empty($errors)) {
                if ($this->taskModel->updateTask($taskId, $userId, $categoryId, $title, $description, $priority, $dueDate, $status)) {
                    header('Location: ?controller=task&action=list');
                    exit();
                } else {
                    $errors[] = "Failed to update task.";
                }
            }
        }
        require_once ROOT_PATH . '/views/edit_task.php';
    }

    public function setStatus() {
        $userId = $_SESSION['user_id'];
        $taskId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $status = isset($_GET['status']) ? strtolower($_GET['status']) : '';

        $validStatuses = ['todo', 'in_progress', 'done'];

        if ($taskId > 0 && in_array($status, $validStatuses)) {
            if ($this->taskModel->updateTaskStatus($taskId, $userId, $status)) {
                header('Location: ?controller=task&action=list');
                exit();
            }
        }
        header('Location: ?controller=task&action=list');
        exit();
    }

    public function search() {
        $userId = $_SESSION['user_id'];
        $searchTerm = null;
        
        $categoryId = $_GET['category_id'] ?? null;
        $priority = $_GET['priority'] ?? null;
        $status = $_GET['status'] ?? null;

        $categories = $this->categoryModel->getCategoriesByUserId($userId);

        $tasks = [];
        if (($categoryId !== null && $categoryId !== '') || !empty($priority) || !empty($status)) {
            $tasks = $this->taskModel->searchTasks($userId, $searchTerm, $categoryId, $priority, $status);
        } else {
            $tasks = $this->taskModel->getTasksByUserId($userId);
        }

        require_once ROOT_PATH . '/views/search_tasks.php';
    }

    public function report() {
        $userId = $_SESSION['user_id'];

        $taskCountsByStatus = $this->taskModel->getTaskCountsByStatus($userId);
        $overdueTaskCount = $this->taskModel->getOverdueTaskCount($userId);
        $categorySummary = $this->taskModel->getCategorySummary($userId);

        require_once ROOT_PATH . '/views/report.php';
    }

    public function delete() {
        $userId = $_SESSION['user_id'];
        $taskId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($taskId > 0) {
            if ($this->taskModel->deleteTask($taskId, $userId)) {
                header('Location: ?controller=task&action=list');
                exit();
            } else {
                http_response_code(403);
                echo "Error: You don't have permission to delete this task or task not found.";
                exit();
            }
        }
        header('Location: ?controller=task&action=list');
        exit();
    }
}