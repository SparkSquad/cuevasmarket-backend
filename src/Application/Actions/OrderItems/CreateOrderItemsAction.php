<?php

declare(strict_types=1);

namespace App\Application\Actions\OrderItems;

use App\Application\Actions\ActionError;
use App\Domain\OrderItems\OrderItems;
use Psr\Log\LoggerInterface;
use Exception;

class CreateOrderItemsAction extends OrderItemsAction
{
    protected function action()
    {
        $loggedUser = $this->request->getAttribute('loggedUser');
        $userId = (int) $loggedUser['id'];

        $newOrderItemsData = $this->getFormData();
        if(is_null($newOrderItemsData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $orderId = $newOrderItemsData['orderId'];
        if(!isset($orderId) || !is_int($orderId)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid order id.');
            return $this->respondWithData($error, 400);
        }

        $productId = $newOrderItemsData['productId'];
        if(!isset($productId) || !is_int($productId)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid product id.');
            return $this->respondWithData($error, 400);
        }

        $quantity = $newOrderItemsData['quantity'];
        if(!isset($quantity) || !is_int($quantity)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid quantity.');
            return $this->respondWithData($error, 400);
        }

        $orderItems = new OrderItems();
        $orderItems->setOrderId($orderId);
        $orderItems->setProductId($productId);
        $orderItems->setQuantity($quantity);

        try {
            $orderItemsId = $this->orderItemsRepository->createOrderItems($orderItems);
            $orderItems->setId($orderItemsId);
            return $this->respondWithData($orderItems, 201);
        } catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $error = new ActionError(ActionError::SERVER_ERROR, 'Internal server error.');
            return $this->respondWithData($error, 500);
        }
    }
}
