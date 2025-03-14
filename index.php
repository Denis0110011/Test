<?php
require_once('vendor/autoload.php');

use repository\JsonRepository;
use repository\SqlRepository;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->Load();
$dbSource = $_ENV['DB_SOURCE'];
if ($argc < 2) {
    echo "show Список пользователей\n";
    echo "add Добавить пользователя <name> <email>\n";
    echo "delete Удалить пользователя <id>\n";
    exit(1);
}
$command = $argv[1];
if ($dbSource == 'json') {
    $UserManager = new JsonRepository('Users.json');
} elseif ($dbSource == 'mysql') {
    $UserManager = new SqlRepository();
}
switch ($command) {
    case 'show':
        $users = $UserManager->ShowUsers();
        foreach ($users as $user) {
            echo ' id:' . $user['id'] . ' name:' . $user['name'] . ' email:' . $user['email'] . "\n";;
        }

        break;
    case 'add':
        if (isset($argv[2]) && isset($argv[3])) {
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
            $id = $argv[2];
            $UserID = $UserManager->DeleteUser($id);
            echo 'Удален пользователь:' . $UserID;
        } else {
            echo 'Укажите id';
        }
        break;
}
