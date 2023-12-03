<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use App\Application\Actions\ActionError;
use App\Domain\Product\Product;
use App\Domain\Product\ProductAlreadyExistsException;
use App\Domain\Product\ProductInvalidCategoryException;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class CreateProductAction extends ProductAction
{
    protected function action(): Response
    {
        $newProductData = $this->getFormData();
        if(is_null($newProductData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $barcode = $newProductData['barcode'];
        if(!isset($barcode) || !is_string($barcode)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid barcode.');
            return $this->respondWithData($error, 400);
        }

        $name = $newProductData['name'];
        if(!isset($name) || !is_string($name)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid name.');
            return $this->respondWithData($error, 400);
        }

        $description = $newProductData['description'];
        if(!isset($description) || !is_string($description)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid description.');
            return $this->respondWithData($error, 400);
        }

        $price = $newProductData['price'];
        if(!isset($price) || !is_double($price)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid price.');
            return $this->respondWithData($error, 400);
        }

        $provider = $newProductData['provider'];
        if(!isset($provider) || !is_string($provider)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid provider.');
            return $this->respondWithData($error, 400);
        }

        $category = $newProductData['category'];
        if(!isset($category) || !is_string($category)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid category.');
            return $this->respondWithData($error, 400);
        }

        try {
            $product = new Product(
                $barcode,
                $name,
                $description,
                $price,
                $provider,
                $category
            );

            $this->productRepository->save($product);
            
            return $this->respondWithData(null, 201);
        }
        catch(ProductInvalidCategoryException $e) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid product category.');
            return $this->respondWithData($error, 400);
        }
        catch(ProductAlreadyExistsException $e) {
            $error = new ActionError(ActionError::CONFLICT, 'A product with this barcode already exists.');
            return $this->respondWithData($error, 409);
        }
        catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $error = new ActionError(ActionError::SERVER_ERROR, 'Internal server error.');
            return $this->respondWithData($error, 500);
        }
    }
}
