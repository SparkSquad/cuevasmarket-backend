<?php

declare(strict_types=1);

namespace App\Application\Actions\ProductStock;

use App\Application\Actions\Action;
use App\Domain\Product\ProductRepository;
use App\Domain\ProductStock\ProductStockRepository;
use App\Domain\Store\StoreBranchRepository;
use Psr\Log\LoggerInterface;

abstract class ProductStockAction extends Action
{
    protected ProductStockRepository $productStockRepository;

    public function __construct(LoggerInterface $logger, ProductStockRepository $productStockRepository)
    {
        parent::__construct($logger);
        $this->productStockRepository = $productStockRepository;
    }
}
