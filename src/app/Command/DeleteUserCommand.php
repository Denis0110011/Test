<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\UserService;

final class DeleteUserCommand implements CommandInterface
{
    public function __construct(private UserService $userService) {}

    public function execute(array $params): array
    {
        if ($params['id'] === '') {
            throw new \InvalidArgumentException('User ID is required');
        }
        $userId = $this->userService->deleteUser((int) $params['id']);
        if ($userId === 0) {
            return ['error' => 'user not found'];
        }

        return ['user_id' => $userId, 'message' => 'user deleted'];
    }
}
