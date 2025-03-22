<?php

declare(strict_types=1);

namespace App\Repository;

final class User
{
    public function __construct(public int $id, public string $name, public string $email) {}
}
