<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use psr\Http\Message\ResponseInterface as Response;
use psr\Http\Message\ServerRequestInterface as Request;
use repository\JsonRepository;
use repository\SqlRepository;
use Slim\Factory\AppFactory;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->Load();
$dbSource = $_ENV['DB_SOURCE'];
if ($dbSource === 'json') {
    $UserManager = new JsonRepository('Users.json');
} elseif ($dbSource === 'mysql') {
    $UserManager = new SqlRepository();
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
