<?php
require_once('vendor/autoload.php');
use repository\JsonRepository;
use repository\SqlRepository;
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dbSource=$_ENV['DB_SOURCE'];
if ($argc < 2) {
    echo "show Список пользователей\n";
    echo "add Добавить пользователя <name> <email>\n";
    echo "delete Удалить пользователя <id>\n";
    exit(1);
}
$command = $argv[1];
if ($dbSource=='json'){
$UserManager = new JsonRepository('Json/Users.json');
switch ($command) {
    case 'show':
        $UserManager->ShowUsers();
        break;
    case 'add':
        if (isset($argv[2]) and isset($argv[3])) {
            $name = $argv[2];
            $email = $argv[3];
            $UserManager->CreateUser($name, $email);
        } else {
            echo 'Укажите имя и e-mail';
        }
        break;
    case 'delete':
        if (isset($argv[2])) {
            $id = $argv[2];
            $UserManager->DeleteUser($id);
        } else {
            echo 'Укажите id';
        }
        break;
}
}elseif($dbSource=='mysql'){
    $UserManager= new SqlRepository;
    switch ($command){
        case 'show':
            $UserManager->showUsersSql();
            break;
        case 'add':
            if (isset($argv[2]) and isset($argv[3])) {
                $name = $argv[2];
                $email = $argv[3];
                $UserManager->createUserSql($name, $email);
            } else {
                echo 'Укажите имя и e-mail';
            }
            break;
        case 'delete':
            if (isset($argv[2])) {
                $id = $argv[2];
                $UserManager->deleteUserSql($id);
            } else {
                echo 'Укажите id';
            }
            break;
    
    }
}
?>