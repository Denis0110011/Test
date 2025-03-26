<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\UserService;

final class ShowUserCommand implements CommandInterface
{
    public function __construct(private UserService $userService) {}

    public function execute(array $params): array
    {
        return ['users' => $this->userService->showUsers()];
    }
}
