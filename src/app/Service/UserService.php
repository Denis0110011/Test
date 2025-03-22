<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\JsonRepository;
use App\Repository\SqlRepository;

final class UserService
{
    private $FilePath = (__DIR__ . '../../../../Users.Json');

    public function __construct(private $userService) {}

    public function showUsers(): array
    {
        if ($this->userService === 'json') {
            $this->userService = new JsonRepository($this->FilePath);

            return $this->userService->ShowUsers();
        }
        if ($this->userService === 'mysql') {
            $this->userService = new SqlRepository();

            return $this->userService->ShowUsers();
        }

        throw new \Exception('Invalid DB_SOURCE:' . $this->userService);
    }

    public function createUser($name, $email): int
    {
        if ($this->userService === 'json') {
            $this->userService = new JsonRepository($this->FilePath);

            return $this->userService->createUser($name, $email);
        }
        if ($this->userService === 'mysql') {
            $this->userService = new SqlRepository();

            return $this->userService->createUser($name, $email);
        }

        throw new \Exception('Invalid DB_SOURCE:' . $this->userService);
    }

    public function deleteUser($id): int
    {
        if ($this->userService === 'json') {
            $this->userService = new JsonRepository($this->FilePath);

            return $this->userService->deleteUser($id);
        }
        if ($this->userService === 'mysql') {
            $this->userService = new SqlRepository();

            return $this->userService->deleteUser($id);
        }

        throw new \Exception('Invalid DB_SOURCE:' . $this->userService);
    }
}
