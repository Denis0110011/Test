<?

namespace repository;

require_once 'vendor/autoload.php';



use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;




class HttpHandler
{

    public function showUsers($UserManager)
    {
        $app = AppFactory::create();
        $this->app->get('/show-users', static function (Request $request, Response $response, array $args) use ($UserManager) {
            $users = $UserManager->ShowUsers();
            $response->getBody()->write(json_encode($users));
            return $response->withHeader('Content-type', 'application/json');
        });
        $app->run();
    }
    public function createUser($UserManager)
    {
        $app = AppFactory::create();
        $this->app->post('/create-user', static function (Request $request, Response $response, array $args) use ($UserManager) {
            $data = json_decode($request->getBody()->getContents(), true);
            if (!isset($data['name']) || !isset($data['email'])) {
                $response->getBody()->write(json_encode(['error' => 'enter your name and email']));

                return $response->withStatus(400)->withHeader('Content-type', 'application/json');
            }
            $userId = $UserManager->CreateUser($data['name'], $data['email']);
            $response->getBody()->write(json_encode(['userid' => $userId]));

            return $response->withHeader('Content-type', 'application/json');
        });
        $app->run();
    }
    public function deleteUser($UserManager)
    {
        $app = AppFactory::create();
        $this->app->delete('/delete-user/{id}', static function (Request $request, Response $response, array $args) use ($UserManager) {
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
    }
}
