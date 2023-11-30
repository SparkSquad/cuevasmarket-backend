<?php

declare(strict_types=1);

use App\Application\Actions\User\AuthUserAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\VerifyUserAuthAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    })->add(AuthMiddleware::class);

    $app->group('/auth', function (Group $group) {
        $group->post('/login', AuthUserAction::class);
        $group->get('/verify', VerifyUserAuthAction::class)->add(AuthMiddleware::class);
    });
};
