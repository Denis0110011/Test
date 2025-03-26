<?php

declare(strict_types=1);

namespace App\Repository;

final class SqlRepository implements UserRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(private string $host, private string $dbname, private string $username, private string $password)
    {
        $this->pdo = $this->connectToPDO($this->host, $this->dbname, $this->username, $this->password);
    }

    /**
     * @throws RuntimeException When database connection fails
     */
    private function connectToPDO(string $host, string $dbname, string $username, string $password): \PDO
    {
        try {
            $pdo = new \PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (\Exception $e) {
            throw new \Exception('Failed to connect to database: ' . $e->getMessage());
        }
    }

    /**
     * @throws \InvalidArgumentException When name or email are empty
     */
    public function createUser(string $name, string $email): int
    {
        if ($name = '' || $email = '') {
            throw new \Exception('Name and email are required.');
        }

        $sql = 'INSERT INTO users (name, email) VALUES (:name, :email)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'email' => $email]);

        return (int) $this->pdo->lastInsertId();
    }
    /**
     * @throws \InvalidArgumentException When invalid user ID is provided
     */
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

        return $id;
    }

    /**
     * @return array<User>
     */
    public function showUsers(): array
    {
        $sql = 'SELECT * FROM users';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        /** @var array{id: int, name: string, email: string} $row */
        $users = [];

        while ($row = $stmt->fetch()) {
            $users[] = new User((int) $row['id'], (string) $row['name'], (string) $row['email']);
        }

        return $users;
    }
}
