<?php

declare(strict_types=1);

namespace App\Application\Actions\Order;

use App\Application\Actions\ActionError;
use App\Domain\Order\Order;
use App\Domain\OrderItem\OrderItem;
use App\Domain\User\User;
use App\Domain\ShippingAddress\ShippingAddress;
use App\Domain\PaymentMethod\PaymentMethod;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class CreateOrderAction extends OrderAction
{
    protected function action(): Response
    {

        $newOrderData = $this->getFormData();
        if(is_null($newOrderData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $userId = $newOrderData['userId'];
        if(!isset($userId) || !is_int($userId)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid user id.');
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
        $user = new User('', '', '', '', '', 'customer');
        $user->setId($userId);
        $order->setUserId($user);
        $shippingAddress = new ShippingAddress($user, '', '', '', '', '');
        $shippingAddress->setId($shippingAddressId);
        $order->setShippingAddressId($shippingAddress);
        $paymentMethod = new PaymentMethod($user, '', '', '', '');
        $paymentMethod->setId($paymentMethodId);
        $order->setPaymentMethodId($paymentMethod);

        try {
            $order = $this->orderRepository->save($order);
            foreach($orderItems as $orderItem) {
                $productId = $orderItem['productId'];
                $quantity = $orderItem['quantity'];
                $this->orderItemRepository->save($order->getId(), $productId, $quantity);
            }
        } catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $error = new ActionError(ActionError::SERVER_ERROR, 'Order not created.');
            return $this->respondWithData($error, 500);
        }

        return $this->respondWithData($order);
    }
}
