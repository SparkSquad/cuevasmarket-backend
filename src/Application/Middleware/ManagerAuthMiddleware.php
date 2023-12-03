<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Actions\ActionError;
use App\Domain\User\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Psr7Response;

class ManagerAuthMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $token = $request->getHeader('Authorization')[0] ?? null;
        if($token) {
            try {
                $key = $_ENV['JWT_SECRET'];
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                $decoded_array = (array) $decoded;
                if($decoded_array['type'] == User::USER_TYPE_ADMIN || $decoded_array['type'] == User::USER_TYPE_MANAGER) {
                    $request = $request->withAttribute('loggedUser', $decoded_array);
                    return $handler->handle($request);
                }
                else {
                    $response = new Psr7Response();
                    $error = new ActionError(ActionError::UNAUTHORIZED, 'User is not an manager.');
                    $response->getBody()->write(json_encode($error, JSON_PRETTY_PRINT));
                    return $response->withStatus(401);
                }
            }
            catch(Exception $e) {
                $response = new Psr7Response();
                $error = new ActionError(ActionError::UNAUTHORIZED, "Invalid token: {$e->getMessage()}");
                $response->getBody()->write(json_encode($error, JSON_PRETTY_PRINT));
                return $response->withStatus(401);
            }
        }
        else {
            $response = new Psr7Response();
            $error = new ActionError(ActionError::UNAUTHORIZED, 'No token provided.');
            $response->getBody()->write(json_encode($error, JSON_PRETTY_PRINT));
            return $response->withStatus(401);
        }
    }
}
