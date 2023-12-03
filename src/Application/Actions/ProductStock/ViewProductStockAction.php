<?php

declare(strict_types=1);

namespace App\Application\Actions\ProductStock;

use App\Application\Actions\ProductStock\ProductStockAction;
use App\Domain\ProductStock\ProductStockNotFoundException;
use Slim\Psr7\Response;

class ViewProductStockAction extends ProductStockAction
{
    protected function action(): Response
    {
        $storeBranchId = (int) $this->resolveArg('branchId');
        $productId = (int) $this->resolveArg('productId');

        $productStock = $this->productStockRepository->findByProductIdAndStoreBranchId($productId, $storeBranchId);
        if(!$productStock) {
            throw new ProductStockNotFoundException();
        }

        return $this->respondWithData($productStock);
    }
}
