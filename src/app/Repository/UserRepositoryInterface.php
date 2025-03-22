<?php

declare(strict_types=1);

namespace App\Repository;

interface UserRepositoryInterface
{
    public function showUsers(): array;

    public function deleteUser(int $id): int;

    public function createUser(string $name, string $email): int;
}
