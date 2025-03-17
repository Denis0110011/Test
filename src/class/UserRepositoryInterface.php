<?php

declare(strict_types=1);

namespace repository;

interface UserRepositoryInterface
{
    public function showUsers();

    public function deleteUser(int $id);

    public function createUser(string $name, string $email);
}
