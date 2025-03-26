<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepositoryInterface;

final class UserService
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function showUsers(): array
    {
        return $this->repository->showUsers();
    }

    public function createUser(string $name, string $email): int
    {
        return $this->repository->createUser($name, $email);
    }

    public function deleteUser(int $id): int
    {
        return $this->repository->deleteUser($id);
    }
}
