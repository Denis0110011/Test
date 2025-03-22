<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';

use App\Http\HttpHandler;
use App\Service\UserService;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$env = $_ENV['DB_SOURCE'];
if (PHP_SAPI === 'cli') {
    if ($argc < 2) {
        echo "show Список пользователей\n";
        echo "add Добавить пользователя <name> <email>\n";
        echo "delete Удалить пользователя <id>\n";
        exit(1);
    }
    $command = $argv[1];
    $userService = new UserService($env);
    switch ($command) {
        case 'show':
            $users = $userService->showUsers();
            foreach ($users as $user) {
                echo ' id:' . $user['id'] . ' name:' . $user['name'] . ' email:' . $user['email'] . "\n";
            }
            break;
        case 'add':
            if (isset($argv[2], $argv[3])) {
                $name = $argv[2];
                $email = $argv[3];
                $userID = $UserService->createUser($name, $email);
                echo 'user created:' . $userID;
            } else {
                echo 'enter your name and email';
            }
            break;
        case 'delete':
            if (isset($argv[2])) {
                $id = (int) $argv[2];
                $userID = $UserService->deleteUser($id);
                if ($UserID !== 0) {
                    echo 'deleted user:' . $userID;
                } else {
                    echo 'user not found';
                }
            } else {
                echo 'enter id';
            }
            break;
    }
    exit;
}
$UserService = new UserService($env);
$httpHanler = new HttpHandler();
$httpHanler->registerRoutes($UserService);
$httpHanler->run();
