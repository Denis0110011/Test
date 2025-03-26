<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\UserService;

final class CreateUserCommand implements CommandInterface
{
    public function __construct(private UserService $userService) {}

    public function execute(array $params): array
    {
        if ($params['name'] === '' || $params['email'] === '') {
            throw new \InvalidArgumentException('Name and email required');
        }
        $userId = $this->userService->createUser($params['name'], $params['email']);

        return ['user_id' => $userId, 'message' => 'User created'];
    }
}
