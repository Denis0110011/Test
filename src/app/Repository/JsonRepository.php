<?php

declare(strict_types=1);

namespace App\Repository;

final class JsonRepository implements UserRepositoryInterface
{
    public function __construct(private string $filePath) {}

    private function loadUsers(): array
    {
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode(['users' => [], 'nextid' => 1], JSON_PRETTY_PRINT));
        }
        $jsonContent = file_get_contents($this->filePath);

        return json_decode($jsonContent, true);
    }

    private function saveUsers(array $data): void
    {
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->filePath, $jsonContent);
    }

    /**
     * @return array<User>
     */
    public function showUsers(): array
    {
        $data = $this->loadUsers();

        return array_map(static fn(array $userData) => new User($userData['id'], $userData['name'], $userData['email']), $data['users']);
    }

    public function createUser(string $name, string $email): int
    {
        $data = $this->loadUsers();
        $newUser = new User($data['nextid'], $name, $email);
        $data['users'][] = $newUser;
        ++$data['nextid'];
        $this->SaveUsers($data);

        return $newUser->id;
    }

    public function deleteUser(int $id): int
    {
        $data = $this->loadUsers();
        foreach ($data['users'] as $index => $user) {
            if ($user['id'] === $id) {
                array_splice($data['users'], $index, 1);
                $this->SaveUsers($data);

                return $id;
            }
        }

        return 0;
    }
}
