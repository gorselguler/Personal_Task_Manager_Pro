<?php

class Task {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addTask($userId, $categoryId, $title, $description, $priority, $dueDate) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, category_id, title, description, priority, due_date) VALUES (:user_id, :category_id, :title, :description, :priority, :due_date)");
        return $stmt->execute([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
            'due_date' => $dueDate
        ]);
    }

    public function getTasksByUserId($userId, $sortBy = 'due_date', $sortOrder = 'ASC') {
        $allowedSortBy = ['due_date', 'priority', 'status', 'created_at', 'title'];
        $allowedSortOrder = ['ASC', 'DESC'];

        $sortBy = in_array($sortBy, $allowedSortBy) ? $sortBy : 'due_date';
        $sortOrder = in_array(strtoupper($sortOrder), $allowedSortOrder) ? strtoupper($sortOrder) : 'ASC';

        $orderByClause = '';
        if ($sortBy === 'priority') {
            $orderByClause = "FIELD(t.priority, 'high', 'medium', 'low') " . $sortOrder;
        } else {
            $orderByClause = "t." . $sortBy . " " . $sortOrder;
        }

        $sql = "SELECT t.*, c.name as category_name FROM tasks t LEFT JOIN categories c ON t.category_id = c.id WHERE t.user_id = :user_id ORDER BY " . $orderByClause;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getTaskByIdAndUserId($taskId, $userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $taskId, 'user_id' => $userId]);
        return $stmt->fetch();
    }

    public function updateTask($taskId, $userId, $categoryId, $title, $description, $priority, $dueDate, $status) {
        $completedAt = null;
        if ($status === 'done') {
            $currentTask = $this->getTaskByIdAndUserId($taskId, $userId);
            if ($currentTask && $currentTask['status'] === 'done' && $currentTask['completed_at'] !== null) {
                $completedAt = $currentTask['completed_at'];
            } else {
                $completedAt = date('Y-m-d H:i:s');
            }
        }

        $stmt = $this->pdo->prepare("UPDATE tasks SET category_id = :category_id, title = :title, description = :description, priority = :priority, due_date = :due_date, status = :status, completed_at = :completed_at WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([
            'category_id' => $categoryId,
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
            'due_date' => $dueDate,
            'status' => $status,
            'completed_at' => $completedAt,
            'id' => $taskId,
            'user_id' => $userId
        ]);
    }

    public function updateTaskStatus($taskId, $userId, $status) {
        $completedAt = null;
        if ($status === 'done') {
            $completedAt = date('Y-m-d H:i:s');
        }

        $stmt = $this->pdo->prepare("UPDATE tasks SET status = :status, completed_at = :completed_at WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([
            'status' => $status,
            'completed_at' => $completedAt,
            'id' => $taskId,
            'user_id' => $userId
        ]);
    }

    public function searchTasks($userId, $searchTerm = null, $categoryId = null, $priority = null, $status = null) {
        $sql = "SELECT t.*, c.name as category_name FROM tasks t LEFT JOIN categories c ON t.category_id = c.id WHERE t.user_id = :user_id";
        $params = ['user_id' => $userId];

        if (!empty($searchTerm)) {
            $sql .= " AND (t.title LIKE :searchTerm OR t.description LIKE :searchTerm)";
            $params['searchTerm'] = '%' . $searchTerm . '%';
        }
        
        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND t.category_id = :categoryId";
            $params['categoryId'] = $categoryId;
        }
        if (!empty($priority)) {
            $sql .= " AND t.priority = :priority";
            $params['priority'] = $priority;
        }
        if (!empty($status)) {
            $sql .= " AND t.status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY t.due_date ASC, t.priority DESC, t.created_at DESC";

        error_log("DEBUG-SQL: " . $sql);
        error_log("DEBUG-PARAMS: " . json_encode($params));

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("PDO ERROR in searchTasks: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . json_encode($params));
            throw $e;
        }
    }

    public function getTaskCountsByStatus($userId) {
        $stmt = $this->pdo->prepare("SELECT status, COUNT(*) as count FROM tasks WHERE user_id = :user_id GROUP BY status");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function getOverdueTaskCount($userId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = :user_id AND status != 'done' AND due_date < NOW()");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function getCategorySummary($userId) {
        $sql = "SELECT c.name as category_name,
                       COUNT(t.id) as total_tasks,
                       SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) as done_tasks
                FROM categories c
                LEFT JOIN tasks t ON c.id = t.category_id AND t.user_id = :user_id_join_tasks
                WHERE c.user_id = :user_id_where_category
                GROUP BY c.name
                ORDER BY c.name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_id_join_tasks' => $userId,
            'user_id_where_category' => $userId
        ]);
        return $stmt->fetchAll();
    }

    public function deleteTask($taskId, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $taskId, 'user_id' => $userId]);
    }
}