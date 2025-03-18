<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use repository\JsonRepository;
use repository\SqlRepository;
use Slim\Factory\AppFactory;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->Load();
$dbSource = $_ENV['DB_SOURCE'];
$mode = $_ENV['Mode'];

if ($dbSource === 'json') {
    $UserManager = new JsonRepository('Users.json');
} elseif ($dbSource === 'mysql') {
    $UserManager = new SqlRepository();
}
if ($mode === 'cli') {
    if ($argc < 2) {
        echo "show Список пользователей\n";
        echo "add Добавить пользователя <name> <email>\n";
        echo "delete Удалить пользователя <id>\n";
        exit(1);
    }
    $command = $argv[1];
    switch ($command) {
        case 'show':
            $users = $UserManager->ShowUsers();
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

$app = AppFactory::create();
$app->get('/show-users', static function (Request $request, Response $response, array $args) use ($UserManager) {
    $users = $UserManager->ShowUsers();
    $response->getBody()->write(json_encode($users));

    return $response->withHeader('Content-type', 'application/json');
});
$app->post('/create-user', static function (Request $request, Response $response, array $args) use ($UserManager) {
    $data = json_decode($request->getBody()->getContents(), true);
    if (!isset($data['name']) || !isset($data['email'])) {
        $response->getBody()->write(json_encode(['error' => 'enter your name and email']));

        return $response->withStatus(400)->withHeader('Content-type', 'application/json');
    }
    $userId = $UserManager->CreateUser($data['name'], $data['email']);
    $response->getBody()->write(json_encode(['userid' => $userId]));

    return $response->withHeader('Content-type', 'application/json');
});
$app->delete('/delete-user/{id}', static function (Request $request, Response $response, array $args) use ($UserManager) {
    $id = (int) $args['id'];
    $userId = $UserManager->DeleteUser($id);
    if (!isset($userId)) {
        $response->getBody()->write(json_encode(['deletedUser' => $userId]));
    } else {
        $response->getBody()->write(json_encode(['error' => 'user not found']));

        return $response->withStatus(404);
    }

    return $response->withHeader('Content-type', 'application/json');
});
$app->run();
