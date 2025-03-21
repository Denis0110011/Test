<?php

declare(strict_types=1);

namespace Service;

use Dotenv\Dotenv;
use repository\HttpHandler;
use repository\JsonRepository;
use repository\SqlRepository;

final class UserService
{
    private $userService;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(\dirname(__DIR__, 2));
        $dotenv->load();
        $dbSource = $_ENV['DB_SOURCE'];


        if ($dbSource === 'json') {
            $this->userService = new JsonRepository('../../Users.json');
        } elseif ($dbSource === 'mysql') {
            $this->userService = new SqlRepository();
        }
    }

    public function showUsers()
    {
        if (\PHP_SAPI === 'cli') {
            return $this->userService->showUsers();
        }
        $http = new HttpHandler();

        return  $http->showUsers($this->userService);

    }

    public function createUser($name, $email)
    {
        if (\PHP_SAPI === 'cli') {
            return $this->userService->createUser($name, $email);
        }
        $http = new HttpHandler();

        return $http->showUsers($this->userService);

    }

    public function deleteUser($id)
    {
        if (\PHP_SAPI === 'cli') {
            return $this->userService->deleteUser($id);
        }
        $http = new HttpHandler();

        return $http->showUsers($this->userService);

    }
}
