<?php

declare(strict_types=1);

namespace App\Application\Actions\ProductStock;

use App\Application\Actions\ProductStock\ProductStockAction;
use App\Domain\ProductStock\ProductStockNotFoundException;
use Slim\Psr7\Response;

class ViewStoreBranchProductStock extends ProductStockAction
{
    protected function action(): Response
    {
        $branchId = (int) $this->resolveArg('branchId');
        $productStock = $this->productStockRepository->findByStoreId($branchId);
        return $this->respondWithData($productStock);
    }
}
