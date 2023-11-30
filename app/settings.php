<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

define('APP_ROOT', dirname(__DIR__));

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, 
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'determineRouteBeforeAppMiddleware' => false,
                'doctrine' => [
                    'dev_mode' => true,
                    'cache_dir' => APP_ROOT . '/var/doctrine',
                    'metadata_dirs' => [APP_ROOT . '/src/Domain/'],
                    'connection_dev' => [
                        'driver' => 'pdo_mysql',
                        'host' => $_ENV['DB_DEV_HOST'] ?? 'localhost',
                        'port' => $_ENV['DB_DEV_PORT'] ?? '3306',
                        'dbname' => $_ENV['DB_DEV_NAME'] ?? 'cuevasmarket-dev',
                        'user' => $_ENV['DB_DEV_USER'] ?? 'user',
                        'password' => $_ENV['DB_DEV_PASSWORD'] ?? 'pass',
                    ]
                ]
            ]);
        }
    ]);
};
