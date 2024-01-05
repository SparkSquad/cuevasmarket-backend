<?php

declare(strict_types=1);

namespace App\Application\Actions\OrderItems;

use App\Application\Actions\ActionError;
use App\Domain\OrderItems\OrderItems;
use App\Domain\OrderItems\OrderItemsAlreadyExistsException;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class UpdateOrderItemsAction extends OrderItemsAction
{
    protected function action(): Response
    {
        $newOrderItemsData = $this->getFormData();
        if(is_null($newOrderItemsData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $id = $this->args['id'];
        if(!isset($id) || !is_numeric($id)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid order item id.');
            return $this->respondWithData($error, 400);
        }

        $orderItems = $this->orderItemsRepository->findById(intval($id));
        if(!$orderItems) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Order item not found.');
            return $this->respondWithData($error, 404);
        }

        $orderId = $newOrderItemsData['order_id'];
        if(isset($orderId)) {
            if(!is_numeric($orderId)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid order id.');
                return $this->respondWithData($error, 400);
            }
            $orderItems->setOrderId(intval($orderId));
        }

        $productId = $newOrderItemsData['product_id'];
        if(isset($productId)) {
            if(!is_numeric($productId)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid product id.');
                return $this->respondWithData($error, 400);
            }
            $orderItems->setProductId(intval($productId));
        }

        $quantity = $newOrderItemsData['quantity'];
        if(isset($quantity)) {
            if(!is_numeric($quantity)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid quantity.');
                return $this->respondWithData($error, 400);
            }
            $orderItems->setQuantity(intval($quantity));
        }

        $price = $newOrderItemsData['price'];
        if(isset($price)) {
            if(!is_numeric($price)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid price.');
                return $this->respondWithData($error, 400);
            }
            $orderItems->setPrice(intval($price));
        }

        try {
            $this->orderItemsRepository->update($orderItems);
            return $this->respondWithData($orderItems);
        } catch (OrderItemsAlreadyExistsException $e) {
            $error = new ActionError(ActionError::RESOURCE_ALREADY_EXISTS, 'Order item already exists.');
            return $this->respondWithData($error, 400);
        } catch (Exception $e) {
            $error = new ActionError(ActionError::SERVER_ERROR, 'Internal server error.');
            return $this->respondWithData($error, 500);
        }
    }
}
