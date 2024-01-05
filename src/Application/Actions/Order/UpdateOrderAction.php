<?php

declare(strict_types=1);

namespace App\Application\Actions\Order;

use App\Application\Actions\ActionError;
use App\Domain\Order\Order;
use Psr\Log\LoggerInterface;
use Exception;

class UpdateOrderAction extends OrderAction
{
    protected function action()
    {
        $loggedUser = $this->request->getAttribute('loggedUser');
        $userId = (int) $loggedUser['id'];

        $orderId = (int) $this->resolveArg('id');
        $order = $this->orderRepository->findOrderOfId($orderId);
        if(is_null($order)) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Order not found.');
            return $this->respondWithData($error, 404);
        }

        $orderData = $this->getFormData();
        if(is_null($orderData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $shippingAddressId = $orderData['shippingAddressId'];
        if(!isset($shippingAddressId) || !is_int($shippingAddressId)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid shipping address id.');
            return $this->respondWithData($error, 400);
        }

        $paymentMethodId = $orderData['paymentMethodId'];
        if(!isset($paymentMethodId) || !is_int($paymentMethodId)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid payment method id.');
            return $this->respondWithData($error, 400);
        }

        $orderStatusId = $orderData['orderStatusId'];
        if(!isset($orderStatusId) || !is_int($orderStatusId)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid order status id.');
            return $this->respondWithData($error, 400);
        }

        $order->setShippingAddressId($shippingAddressId);
        $order->setPaymentMethodId($paymentMethodId);
        $order->setOrderStatusId($orderStatusId);

        try {
            $this->orderRepository->updateOrder($order);
            $this->logger->info("Order of id `${orderId}` was updated.");
            return $this->respondWithData($order);
        } catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $error = new ActionError(ActionError::SERVER_ERROR, 'Order not updated.');
            return $this->respondWithData($error, 500);
        }
    }
}
