<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use App\Application\Actions\Action;
use App\Application\Settings\SettingsInterface;
use App\Domain\Product\ProductRepository;
use Psr\Log\LoggerInterface;

abstract class ProductAction extends Action
{
    protected ProductRepository $productRepository;

    public function __construct(LoggerInterface $logger, SettingsInterface $settings, ProductRepository $productRepository)
    {
        parent::__construct($logger, $settings);
        $this->productRepository = $productRepository;
    }
}
