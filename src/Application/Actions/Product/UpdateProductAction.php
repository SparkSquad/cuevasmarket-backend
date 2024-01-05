<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use App\Application\Actions\ActionError;
use App\Domain\Product\Product;
use App\Domain\Product\ProductAlreadyExistsException;
use App\Domain\Product\ProductInvalidCategoryException;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class UpdateProductAction extends ProductAction
{
    protected function action(): Response
    {
        $newProductData = $this->getFormData();
        if(is_null($newProductData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

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

        $barcode = $newProductData['barcode'];
        if(isset($barcode)) {
            if(!is_string($barcode)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid barcode.');
                return $this->respondWithData($error, 400);
            }
            $product->setBarcode($barcode);
        }

        $name = $newProductData['name'];
        if(isset($name)) {
            if(!is_string($name)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid name.');
                return $this->respondWithData($error, 400);
            }
            $product->setName($name);
        }

        $description = $newProductData['description'];
        if(isset($description)) {
            if(!is_string($description)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid description.');
                return $this->respondWithData($error, 400);
            }
            $product->setDescription($description);
        }

        $price = $newProductData['price'];
        if(isset($price)) {
            if(!is_double($price)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid price.');
                return $this->respondWithData($error, 400);
            }
            $product->setPrice($price);
        }

        $provider = $newProductData['provider'];
        if(isset($provider)) {
            if(!is_string($provider)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid provider.');
                return $this->respondWithData($error, 400);
            }
            $product->setProvider($provider);
        }

        $category = $newProductData['category'];
        if(isset($category)) {
            if(!is_string($category)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid category.');
                return $this->respondWithData($error, 400);
            }
            try {
                $product->setCategory($category);
            }
            catch(ProductInvalidCategoryException $e) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid product category.');
                return $this->respondWithData($error, 400);
            }
        }

        try {
            $this->productRepository->update($product);
            return $this->respondWithData(null, 204);
        }
        catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $error = new ActionError(ActionError::SERVER_ERROR, 'Internal server error.');
            return $this->respondWithData($error, 500);
        }
    }
}
