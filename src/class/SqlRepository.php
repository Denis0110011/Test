<?php

namespace repository;

use PDO;
use Exception;

class SqlRepository
{
    private $pdo;


    public function __construct()
    {
        $this->pdo = $this->connectToPDO();
    }


    private function connectToPDO()
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (Exception $e) {
            throw new Exception('Failed to connect to database: ' . $e->getMessage());
        }
    }
    public function createUserSql(string $name, string $email): int
    {
        if (empty($name) || empty($email)) {
            throw new Exception('Name and email are required.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format.');
        }

        $sql = 'INSERT INTO users (Name, Email) VALUES (:name, :email)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'email' => $email]);
        return (int)$this->pdo->lastInsertId();
    }

    
    public function deleteUserSql(int $id): int
    {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception('Invalid user ID.');
        }

        $sql = 'DELETE FROM users WHERE ID=:id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return (int)$id;
    }


    public function showUsersSql(): array
    {
        $sql = 'SELECT * FROM users';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $users=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return (array)$users;
    }


    public function closeConnection()
    {
        $this->pdo = null;
    }
}
