<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';

use App\Command\CreateUserCommand;
use App\Command\DeleteUserCommand;
use App\Command\ShowUserCommand;
use App\Http\HttpHandler;
use App\Repository\JsonRepository;
use App\Repository\SqlRepository;
use App\Service\UserService;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$env = $_ENV['DB_SOURCE'];
if ($env === 'json') {
    $repository = new JsonRepository('Users.Json');
} elseif ($env === 'mysql') {
    $repository = new SqlRepository($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
}
if (PHP_SAPI === 'cli') {
    if ($argc < 2) {
        echo "show Список пользователей\n";
        echo "add Добавить пользователя <name> <email>\n";
        echo "delete Удалить пользователя <id>\n";
        exit(1);
    }
    $command = $argv[1];
    $userService = new UserService($repository);
    switch ($command) {
        case 'show':
            $command = new ShowUserCommand($userService);
            $result = $command->execute([]);
            foreach ($result['users'] as $user) {
                echo "id:{$user->id}  name:{$user->name} email:{$user->email} \n";
            }
            break;
        case 'add':
            $command = new CreateUserCommand($userService);
            $result = $command->execute(['name' => $argv[2], 'email' => $argv[3]]);
            echo 'User created:' . $result['user_id'];
            break;
        case 'delete':
            $command = new DeleteUserCommand($userService);
            $result = $command->execute(['id' => $argv[2]]);
            if (!isset($result['error'])) {
                echo 'User deleted:' . $result['user_id'];
                exit;
            }
            echo $result['error'];
            exit(1);
    }
    exit;
}
$userService = new UserService($repository);
$httpHandler = new HttpHandler();
$httpHandler->registerRoutes($userService);
$httpHandler->run();
