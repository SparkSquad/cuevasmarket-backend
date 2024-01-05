<?php

declare(strict_types=1);

use App\Domain\Product\ProductRepository;
use App\Domain\ProductStock\ProductStockRepository;
use App\Domain\Store\StoreBranchRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Product\DoctrineProductRepository;
use App\Infrastructure\Persistence\ProductStock\DoctrineProductStockRepository;
use App\Infrastructure\Persistence\Store\DoctrineStoreBranchRepository;
use App\Infrastructure\Persistence\User\DoctrineUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(DoctrineUserRepository::class),
        ProductRepository::class => \DI\autowire(DoctrineProductRepository::class),
        StoreBranchRepository::class => \DI\autowire(DoctrineStoreBranchRepository::class),
        ProductStockRepository::class => \DI\autowire(DoctrineProductStockRepository::class),
    ]);
};
