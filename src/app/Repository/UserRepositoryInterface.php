<?php

declare(strict_types=1);

namespace App\Repository;

interface UserRepositoryInterface
{
    /**
     * @return array<User>
     */
    public function showUsers(): array;

    public function deleteUser(int $id): int;

    public function createUser(string $name, string $email): int;
}
