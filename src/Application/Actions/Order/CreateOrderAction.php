<?php

declare(strict_types=1);

namespace App\Application\Actions\Order;

use App\Application\Actions\ActionError;
use App\Domain\Order\Order;
use Psr\Log\LoggerInterface;
use Exception;

class CreateOrderAction extends OrderAction
{
    protected function action()
    {
        $loggedUser = $this->request->getAttribute('loggedUser');
        $userId = (int) $loggedUser['id'];

        $newOrderData = $this->getFormData();
        if(is_null($newOrderData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $shippingAddressId = $newOrderData['shippingAddressId'];
        if(!isset($shippingAddressId) || !is_int($shippingAddressId)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid shipping address id.');
            return $this->respondWithData($error, 400);
        }

        $paymentMethodId = $newOrderData['paymentMethodId'];
        if(!isset($paymentMethodId) || !is_int($paymentMethodId)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid payment method id.');
            return $this->respondWithData($error, 400);
        }

        $orderItems = $newOrderData['orderItems'];

        if(!isset($orderItems) || !is_array($orderItems)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid order items.');
            return $this->respondWithData($error, 400);
        }

        foreach($orderItems as $orderItem) {
            $productId = $orderItem['productId'];
            if(!isset($productId) || !is_int($productId)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid product id.');
                return $this->respondWithData($error, 400);
            }

            $quantity = $orderItem['quantity'];
            if(!isset($quantity) || !is_int($quantity)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid quantity.');
                return $this->respondWithData($error, 400);
            }
        }

        $order = new Order();
        $order->setUserId($userId);
        $order->setShippingAddressId($shippingAddressId);
        $order->setPaymentMethodId($paymentMethodId);

        try {
            $orderId = $this->orderRepository->createOrder($order);
            $order->setId($orderId);
            foreach($orderItems as $orderItem) {
                $productId = $orderItem['productId'];
                $quantity = $orderItem['quantity'];
                $this->orderItemsRepository->createOrderItem($orderId, $productId, $quantity);
            }
            return $this->respondWithData($order, 201);
        } catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $error = new ActionError(ActionError::SERVER_ERROR, 'Internal server error.');
            return $this->respondWithData($error, 500);
        }
    }
}
