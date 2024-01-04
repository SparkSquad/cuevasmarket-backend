<?php

declare(strict_types=1);

namespace App\Application\Actions\PaymentMethod;

use App\Application\Actions\ActionError;
use Psr\Log\LoggerInterface;
use Exception;

class DeletePaymentMethodAction extends PaymentMethodAction
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

        try {
            $this->paymentMethodRepository->delete($paymentMethod);
        } catch(Exception $e) {
            $error = new ActionError(ActionError::SERVER_ERROR, 'Error deleting payment method.');
            return $this->respondWithData($error, 500);
        }

        return $this->respondWithData('Payment method deleted.');
    }
}
