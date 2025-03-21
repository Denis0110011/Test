<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';
require_once 'src/class/HttpHandler.php';

use repository\HttpHandler;
use Service\UserService;

if (PHP_SAPI === 'cli') {
    if ($argc < 2) {
        echo "show Список пользователей\n";
        echo "add Добавить пользователя <name> <email>\n";
        echo "delete Удалить пользователя <id>\n";
        exit(1);
    }
    $command = $argv[1];
    $UserManager = new UserService();
    switch ($command) {
        case 'show':
            $users = $UserManager->showUsers();
            foreach ($users as $user) {
                echo ' id:' . $user['id'] . ' name:' . $user['name'] . ' email:' . $user['email'] . "\n";
            }
            break;
        case 'add':
            if (isset($argv[2], $argv[3])) {
                $name = $argv[2];
                $email = $argv[3];
                $UserID = $UserManager->CreateUser($name, $email);
                echo 'Добавлен пользователь:' . $UserID;
            } else {
                echo 'Укажите имя и e-mail';
            }
            break;
        case 'delete':
            if (isset($argv[2])) {
                $id = (int) $argv[2];
                $UserID = $UserManager->DeleteUser($id);
                echo 'Удален пользователь:' . $UserID;
            } else {
                echo 'Укажите id';
            }
            break;
    }
    exit;
}
$UserManager = new UserService();
$httpHandler = new HttpHandler();
$httpHandler->showUsers($UserManager);
$httpHandler->createUser($UserManager);
$httpHandler->deleteUser($UserManager);
