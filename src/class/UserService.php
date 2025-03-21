<?php

namespace Service;

use Dotenv\Dotenv;
use repository\JsonRepository;
use repository\SqlRepository;
use repository\HttpHandler;

class UserService
{
    private $userService;
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
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
        if (php_sapi_name() == 'cli') {
            return $this->userService->showUsers();
        } else {
            $http = new HttpHandler;
            return  $http->showUsers($this->userService);
        }
    }
    public function createUser($name, $email)
    {
        if (php_sapi_name() == 'cli') {
            return $this->userService->createUser($name, $email);
        } else {
            $http = new HttpHandler;
            return $http->showUsers($this->userService);
        }
    }
    public function deleteUser($id)
    {
        if (php_sapi_name() == 'cli') {
            return $this->userService->deleteUser($id);
        } else {
            $http = new HttpHandler;
            return $http->showUsers($this->userService);
        }
    }
}
