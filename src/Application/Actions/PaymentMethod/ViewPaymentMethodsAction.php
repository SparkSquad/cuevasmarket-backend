<?php

declare(strict_types=1);

namespace App\Application\Actions\PaymentMethod;

use App\Domain\PaymentMethod\PaymentMethodNotFoundException;
use App\Domain\PaymentMethod\PaymentMethod;
use App\Domain\PaymentMethod\PaymentMethodRepository;
use App\Application\Actions\ActionError;
use Psr\Http\Message\ResponseInterface as Response;

class ViewPaymentMethodsAction extends PaymentMethodAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('userId');
        try {
            $paymentMethods = $this->paymentMethodRepository->findByUserId($userId);
            if(is_null($paymentMethods)) {
                $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Payment method not found.');
                return $this->respondWithData($error, 404);
            }
        } catch (PaymentMethodNotFoundException $e) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Payment method not found.');
            return $this->respondWithData($error, 404);
        }
        return $this->respondWithData($paymentMethods);
    }
}
