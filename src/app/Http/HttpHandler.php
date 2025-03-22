<?php

declare(strict_types=1);

namespace App\Http;

require_once 'vendor/autoload.php';



use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

final class HttpHandler
{
    private $app;

    public function __construct()
    {
        $this->app = AppFactory::create();
    }

    public function registerRoutes($UserService): void
    {

        $this->app->get('/show-users', static function (Request $request, Response $response, array $args) use ($UserService) {
            $users = $UserService->ShowUsers();
            $response->getBody()->write(json_encode($users));

            return $response->withHeader('Content-type', 'application/json');
        });
        $this->app->post('/create-user', static function (Request $request, Response $response, array $args) use ($UserService) {
            $data = json_decode($request->getBody()->getContents(), true);
            if (!isset($data['name']) || !isset($data['email'])) {
                $response->getBody()->write(json_encode(['error' => 'enter your name and email']));

                return $response->withStatus(400)->withHeader('Content-type', 'application/json');
            }
            $userId = $UserService->CreateUser($data['name'], $data['email']);
            $response->getBody()->write(json_encode(['user created' => $userId]));

            return $response->withHeader('Content-type', 'application/json');
        });
        $this->app->delete('/delete-user/{id}', static function (Request $request, Response $response, array $args) use ($UserService) {
            $id = (int) $args['id'];
            $userId = $UserService->DeleteUser($id);
            if ($userId !== 0) {
                $response->getBody()->write(json_encode(['user deleted' => $userId]));
            } else {
                $response->getBody()->write(json_encode(['error' => 'user not found']));

                return $response->withStatus(404);
            }

            return $response->withHeader('Content-type', 'application/json');
        });
    }

    public function run(): void
    {
        $this->app->run();
    }
}
