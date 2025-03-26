<?php

declare(strict_types=1);

namespace App\Http;

require_once 'vendor/autoload.php';



use App\Command\CreateUserCommand;
use App\Command\DeleteUserCommand;
use App\Command\ShowUserCommand;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;

final class HttpHandler
{
    private App $app;

    public function __construct()
    {
        $this->app = AppFactory::create();
    }

    public function registerRoutes(UserService $userService): void
    {
        $showCommand = new ShowUserCommand($userService);
        $createCommand = new CreateUserCommand($userService);
        $deleteCommand = new DeleteUserCommand($userService);
        $this->app->get('/', static function (Request $request, Response $response) {
            $response->getBody()->write('GET/show-users <br> POST/create-user <br> DELETE/delete-user/{id}');

            return $response;
        });
        $this->app->get('/show-users', static function (Request $request, Response $response) use ($showCommand): Response {
            $result = $showCommand->execute([]);
            $response->getBody()->write(json_encode($result));

            return $response->withHeader('Content-type', 'application/json');
        });
        $this->app->post('/create-user', static function (Request $request, Response $response) use ($createCommand): Response {
            $data = json_decode($request->getBody()->getContents(), true);

            try {
                $result = $createCommand->execute($data);
                $response->getBody()->write(json_encode($result));

                return $response->withHeader('Content-Type', 'application/json');
            } catch (\InvalidArgumentException $e) {
                $response->getBody()->write(json_encode(['error' => $e->getMessage()]));

                return $response->withStatus(400)->withHeader('Content Type', 'application/json');
            }
        });
        $this->app->delete('/delete-user/{id}', static function (Request $request, Response $response, array $args) use ($deleteCommand): Response {
            try {
                $result = $deleteCommand->execute(['id' => $args['id']]);
                if (isset($result['error'])) {
                    $response->getBody()->write(json_encode($result));

                    return $response->withHeader('Content-Type', 'application/json');
                }
                $response->getBody()->write(json_encode($result));

                return $response->withHeader('Content-Type', 'application/json');
            } catch (\InvalidArgumentException $e) {
                $response->getBody()->write(json_encode(['error' => $e->getMessage()]));

                return $response->withHeader('Content-Type', 'application/json');
            }
        });
    }

    public function run(): void
    {
        $this->app->run();
    }
}
