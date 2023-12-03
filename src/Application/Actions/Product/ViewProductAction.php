<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use App\Domain\Product\ProductNotFoundException;
use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class ViewProductAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $productId = (int) $this->resolveArg('id');
        $product = $this->productRepository->findById($productId);
        if(!$product) {
            throw new ProductNotFoundException();
        }
        return $this->respondWithData($product);
    }
}
