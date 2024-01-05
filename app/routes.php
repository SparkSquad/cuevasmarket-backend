<?php

declare(strict_types=1);

use App\Application\Actions\User\AuthUserAction;
use App\Application\Actions\User\RegisterUserAction;
use App\Application\Actions\User\SearchUserAction;
use App\Application\Actions\User\UpdateUserAction;
use App\Application\Actions\User\VerifyUserAuthAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\DeleteUserAction;
use App\Application\Actions\Product\ViewProductAction;
use App\Application\Actions\Product\CreateProductAction;
use App\Application\Actions\Product\UpdateProductAction;
use App\Application\Actions\Product\SearchProductsAction;
use App\Application\Actions\Product\DeleteProductAction;
use App\Application\Actions\ProductStock\ViewProductStockAction;
use App\Application\Actions\ProductStock\ViewProductStockListAction;
use App\Application\Actions\ProductStock\ViewStoreBranchProductStock;
use App\Application\Actions\StoreBranch\CreateStoreBranchAction;
use App\Application\Actions\StoreBranch\DeleteStoreBranchAction;
use App\Application\Actions\StoreBranch\SearchStoreBranchAction;
use App\Application\Actions\StoreBranch\UpdateStoreBranchAction;
use App\Application\Actions\StoreBranch\ViewStoreBranchAction;
use App\Application\Actions\PaymentMethod\CreatePaymentMethodAction;
use App\Application\Actions\PaymentMethod\DeletePaymentMethodAction;
use App\Application\Actions\PaymentMethod\ViewPaymentMethodsAction;
use App\Application\Actions\PaymentMethod\UpdatePaymentMethodAction;
use App\Application\Actions\ShippingAddress\CreateShippingAddressAction;
use App\Application\Actions\ShippingAddress\DeleteShippingAddressAction;
use App\Application\Actions\ShippingAddress\UpdateShippingAddressAction;
use App\Application\Actions\ShippingAddress\ViewShippingAddressesAction;
use App\Application\Actions\Order\CreateOrderAction;
use App\Application\Actions\Order\DeleteOrderAction;
use App\Application\Actions\Order\SearchOrdersAction;
use App\Application\Actions\Order\UpdateOrderAction;
use App\Application\Actions\Order\ViewOrderAction;
use App\Application\Actions\OrderItems\CreateOrderItemsAction;
use App\Application\Actions\OrderItems\UpdateOrderItemsAction;
use App\Application\Actions\OrderItems\ViewOrderItemsAction;
use App\Application\Middleware\AdminAuthMiddleware;
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
        $group->get('/{id:[0-9]+}', ViewUserAction::class); 
        $group->get('/search/[{keyword}]', SearchUserAction::class);
        $group->post('', RegisterUserAction::class);
        $group->put('/{id:[0-9]+}', UpdateUserAction::class);
        $group->delete('/{id:[0-9]+}', DeleteUserAction::class);
    })->add(AdminAuthMiddleware::class);

    $app->group('/users/{userId:[0-9]+}/paymentmethods', function (Group $group) {
        $group->get('/', ViewPaymentMethodsAction::class);
        $group->post('', CreatePaymentMethodAction::class);
        $group->put('/{paymentMethodId:[0-9]+}', UpdatePaymentMethodAction::class);
        $group->delete('/{paymentMethodId:[0-9]+}', DeletePaymentMethodAction::class);
    })->add(AuthMiddleware::class);

    $app->group('/users/{userId:[0-9]+}/shippingaddresses', function (Group $group) {
        $group->get('/', ViewShippingAddressesAction::class);
        $group->post('', CreateShippingAddressAction::class);
        $group->put('/{shippingAddressId:[0-9]+}', UpdateShippingAddressAction::class);
        $group->delete('/{shippingAddressId:[0-9]+}', DeleteShippingAddressAction::class);
    })->add(AuthMiddleware::class);

    $app->group('/auth', function (Group $group) {
        $group->post('/login', AuthUserAction::class);
        $group->get('/verify', VerifyUserAuthAction::class)->add(AuthMiddleware::class);
        $group->post('/register', RegisterUserAction::class);
    });

    $app->group('/products', function (Group $group) {
        $group->get('/{id:[0-9]+}', ViewProductAction::class);
        $group->get('/search/[{keyword}]', SearchProductsAction::class);
        $group->post('', CreateProductAction::class)->add(AdminAuthMiddleware::class);
        $group->put('/{id:[0-9]+}', UpdateProductAction::class)->add(AdminAuthMiddleware::class);
        $group->delete('/{id:[0-9]+}', DeleteProductAction::class)->add(AdminAuthMiddleware::class);
        $group->get('/{productId:[0-9]+}/stock', ViewProductStockListAction::class);
    });

    $app->group('/orders', function (Group $group) {
        $group->get('/{id:[0-9]+}', ViewOrderAction::class);
        $group->get('/search/[{keyword}]', SearchOrdersAction::class);
        $group->post('', CreateOrderAction::class)->add(AuthMiddleware::class);
        $group->put('/{id:[0-9]+}', UpdateOrderAction::class)->add(AuthMiddleware::class);
        $group->delete('/{id:[0-9]+}', DeleteOrderAction::class)->add(AuthMiddleware::class);
        $group->get('/{orderId:[0-9]+}/items', ViewOrderItemsAction::class);
        $group->post('/{orderId:[0-9]+}/items', CreateOrderItemsAction::class)->add(AuthMiddleware::class);
        $group->put('/{orderId:[0-9]+}/items/{orderItemId:[0-9]+}', UpdateOrderItemsAction::class)->add(AuthMiddleware::class);
    });

    $app->group('/storebranches', function (Group $group) {
        $group->get('/{id:[0-9]+}', ViewStoreBranchAction::class);
        $group->get('/search/[{keyword}]', SearchStoreBranchAction::class);
        $group->post('', CreateStoreBranchAction::class)->add(AdminAuthMiddleware::class);
        $group->put('/{id:[0-9]+}', UpdateStoreBranchAction::class)->add(AdminAuthMiddleware::class);
        $group->delete('/{id:[0-9]+}', DeleteStoreBranchAction::class)->add(AdminAuthMiddleware::class);
        $group->get('/{branchId:[0-9]+}/productstock', ViewStoreBranchProductStock::class);
        $group->get('/{branchId:[0-9]+}/productstock/{productId:[0-9]+}', ViewProductStockAction::class);
    });
};
