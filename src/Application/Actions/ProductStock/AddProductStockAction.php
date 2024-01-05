<?php

declare(strict_types=1);

namespace App\Application\Actions\ProductStock;

use App\Application\Actions\ActionError;
use App\Application\Actions\ProductStock\ProductStockAction;
use App\Domain\ProductStock\ProductStockNotFoundException;
use Exception;
use Slim\Psr7\Response;

class AddProductStock extends ProductStockAction
{
    protected function action(): Response
    {
        $storeBranchId = (int) $this->resolveArg('branchId');
        $productId = (int) $this->resolveArg('productId');
        
        $newStock = $this->getFormData();
        if(is_null($newStock)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $quantity = $newStock['quantity'];
        if(!isset($quantity) || !is_int($quantity)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid quantity.');
            return $this->respondWithData($error, 400);
        }

        try {
            $productStock = $this->productStockRepository->addProductStock($storeBranchId, $productId, $quantity);
            return $this->respondWithData($productStock);
        } 
        catch (ProductStockNotFoundException $e) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Product stock not found.');
            return $this->respondWithData($error, 404);
        } 
        catch (Exception $e) {
            $error = new ActionError(ActionError::SERVER_ERROR, 'Internal server error.');
            return $this->respondWithData($error, 500);
        }
    }
}
