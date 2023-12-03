<?php

declare(strict_types=1);

use App\Application\Actions\User\AuthUserAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\RegisterUserAction;
use App\Application\Actions\User\SearchUserAction;
use App\Application\Actions\User\UpdateUserAction;
use App\Application\Actions\User\VerifyUserAuthAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\Product\ViewProductAction;
use App\Application\Actions\Product\CreateProductAction;
use App\Application\Actions\Product\UpdateProductAction;
use App\Application\Actions\Product\SearchProductAction;
use App\Application\Actions\Product\DeleteProductAction;
use App\Application\Middleware\AuthMiddlewaregit;
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
        $group->get('', ListUsersAction::class); // TODO: remove this 
        $group->get('/{id:[0-9]+}', ViewUserAction::class); 
        $group->post('', RegisterUserAction::class);
        $group->put('/{id:[0-9]+}', UpdateUserAction::class);
        $group->get('/search/[{keyword}]', SearchUserAction::class);
    })->add(AuthMiddleware::class);

    $app->group('/auth', function (Group $group) {
        $group->post('/login', AuthUserAction::class);
        $group->get('/verify', VerifyUserAuthAction::class)->add(AuthMiddleware::class);
        $group->post('/register', RegisterUserAction::class);
    });

    $app->group('/products', function (Group $group) {
        $group->get('/{id:[0-9]+}', ViewProductAction::class);
        $group->post('', CreateProductAction::class);
        $group->put('/{id:[0-9]+}', UpdateProductAction::class);
        $group->delete('/{id:[0-9]+}', DeleteProductAction::class);
        $group->get('/search/[{keyword}]', SearchProductAction::class);
    });
};
