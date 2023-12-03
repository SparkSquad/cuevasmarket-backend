<?php

declare(strict_types=1);

namespace App\Application\Actions\ProductStock;

use App\Application\Actions\ProductStock\ProductStockAction;
use App\Domain\ProductStock\ProductStockNotFoundException;
use Slim\Psr7\Response;

class ViewProductStockListAction extends ProductStockAction
{
    protected function action(): Response
    {
        $productId = (int) $this->resolveArg('productId');
        $productStock = $this->productStockRepository->findByProductId($productId);
        return $this->respondWithData($productStock);
    }
}
