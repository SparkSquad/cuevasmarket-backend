<?php

declare(strict_types=1);

use App\Domain\Product\ProductRepository;
use App\Domain\Store\StoreBranchRepository;
use App\Domain\User\UserRepository;
use App\Domain\PaymentMethod\PaymentMethodRepository;
use App\Domain\ShippingAddress\ShippingAddressRepository;
use App\Infrastructure\Persistence\PaymentMethod\DoctrinePaymentMethodRepository;
use App\Infrastructure\Persistence\Product\DoctrineProductRepository;
use App\Infrastructure\Persistence\Store\DoctrineStoreBranchRepository;
use App\Infrastructure\Persistence\User\DoctrineUserRepository;
use App\Infrastructure\Persistence\ShippingAddress\DoctrineShippingAddressRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(DoctrineUserRepository::class),
        ProductRepository::class => \DI\autowire(DoctrineProductRepository::class),
        StoreBranchRepository::class => \DI\autowire(DoctrineStoreBranchRepository::class),
        PaymentMethodRepository::class => \DI\autowire(DoctrinePaymentMethodRepository::class),
        ShippingAddressRepository::class => \DI\autowire(DoctrineShippingAddressRepository::class),
    ]);
};
