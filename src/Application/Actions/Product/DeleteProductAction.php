<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use App\Application\Actions\ActionError;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteProductAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = $this->args['id'];
        if(!isset($id) || !is_numeric($id)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid product id.');
            return $this->respondWithData($error, 400);
        }

        $product = $this->productRepository->findById(intval($id));
        if(!$product) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Product not found.');
            return $this->respondWithData($error, 404);
        }

        $this->productRepository->delete($product);
        return $this->respondWithData();
    }
}
