<?php

class Category {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addCategory($userId, $name) {
        $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE user_id = :user_id AND name = :name");
        $stmt->execute(['user_id' => $userId, 'name' => $name]);
        if ($stmt->fetch()) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO categories (user_id, name) VALUES (:user_id, :name)");
        return $stmt->execute([
            'user_id' => $userId,
            'name'    => $name
        ]);
    }

    public function getCategoriesByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT id, name FROM categories WHERE user_id = :user_id ORDER BY name ASC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
}