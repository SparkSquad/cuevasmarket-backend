<?php

declare(strict_types=1);

namespace App\Application\Actions\PaymentMethod;

use App\Application\Actions\ActionError;
use App\Domain\PaymentMethod\PaymentMethod;
use Psr\Log\LoggerInterface;
use Exception;

class UpdatePaymentMethodAction extends PaymentMethodAction
{
    protected function action()
    {
        $loggedUser = $this->request->getAttribute('loggedUser');
        $userId = (int) $loggedUser['id'];

        $paymentMethodId = (int) $this->resolveArg('id');
        $paymentMethod = $this->paymentMethodRepository->findPaymentMethodOfId($paymentMethodId);
        if(is_null($paymentMethod)) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Payment method not found.');
            return $this->respondWithData($error, 404);
        }

        $paymentMethodData = $this->getFormData();
        if(is_null($paymentMethodData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $name = $paymentMethodData['name'];
        if(!isset($name) || !is_string($name)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid name.');
            return $this->respondWithData($error, 400);
        }

        $cardNumber = $paymentMethodData['cardNumber'];
        if(!isset($cardNumber) || !is_string($cardNumber)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid card number.');
            return $this->respondWithData($error, 400);
        }

        $expirationDate = $paymentMethodData['expirationDate'];
        if(!isset($expirationDate) || !is_string($expirationDate)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid expiration date.');
            return $this->respondWithData($error, 400);
        }

        $cvv = $paymentMethodData['cvv'];
        if(!isset($cvv) || !is_string($cvv)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid cvv.');
            return $this->respondWithData($error, 400);
        }

        $paymentMethod->setName($name);
        $paymentMethod->setCardNumber($cardNumber);
        $paymentMethod->setExpirationDate($expirationDate);
        $paymentMethod->setCvv($cvv);

        try {
            $this->paymentMethodRepository->save($paymentMethod);
        } catch(Exception $e) {
            $error = new ActionError(ActionError::SERVER_ERROR, 'Error updating payment method.');
            return $this->respondWithData($error, 500);
        }

        return $this->respondWithData($paymentMethod);
    }
}
