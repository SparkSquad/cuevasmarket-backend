<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        EntityManager::class => function(ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $ds = $settings->get('doctrine');

            $cache = $ds['dev_mode'] ? new ArrayAdapter() : new FilesystemAdapter(directory: $ds['cache_dir']);

            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: $ds['metadata_dirs'],
                isDevMode: $ds['dev_mode'],
                proxyDir: null,
                cache: $cache
            );

            $connectionData = $ds['dev_mode'] ? $ds['connection_dev'] : $ds['connection'];
            $connection = DriverManager::getConnection($connectionData, $config);

            return new EntityManager($connection, $config);
        }
    ]);
};
