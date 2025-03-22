<?php

declare(strict_types=1);

namespace App\Repository;

final class SqlRepository implements UserRepositoryInterface
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = $this->connectToPDO();
    }

    private function connectToPDO()
    {
        try {
            $pdo = new \PDO('mysql:host=localhost;dbname=test', 'root', '');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (\Exception $e) {
            throw new \Exception('Failed to connect to database: ' . $e->getMessage());
        }
    }

    public function createUser(string $name, string $email): int
    {
        if (empty($name) || empty($email)) {
            throw new \Exception('Name and email are required.');
        }

        $sql = 'INSERT INTO users (name, email) VALUES (:name, :email)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'email' => $email]);

        return (int) $this->pdo->lastInsertId();
    }

    public function deleteUser(int $id): int
    {
        if ($id <= 0) {
            throw new \Exception('Invalid user ID.');
        }
        $sql = 'DELETE FROM users WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        if ($stmt->rowCount() === 0) {
            return 0;
        }

        return (int) $id;
    }

    public function showUsers(): array
    {
        $sql = 'SELECT * FROM users';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return (array) $users;
    }

    public function closeConnection(): void
    {
        $this->pdo = null;
    }
}
