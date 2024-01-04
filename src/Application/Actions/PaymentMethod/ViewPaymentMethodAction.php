<?php

declare(strict_types=1);

namespace App\Application\Actions\PaymentMethod;

use App\Domain\PaymentMethod\PaymentMethodNotFoundException;
use App\Domain\PaymentMethod\PaymentMethod;
use App\Domain\PaymentMethod\PaymentMethodRepository;
use App\Application\Actions\ActionError;
use Psr\Http\Message\ResponseInterface as Response;

class ViewPaymentMethodAction extends PaymentMethodAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $loggedUser = $this->request->getAttribute('loggedUser');
        $userId = (int) $loggedUser['id'];

        $paymentMethodId = (int) $this->resolveArg('id');
        try {
            $paymentMethod = $this->paymentMethodRepository->findById($paymentMethodId);
            if($paymentMethod->getUserId() !== $userId) {
                $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Payment method not found.');
                return $this->respondWithData($error, 404);
            }
        } catch (PaymentMethodNotFoundException $e) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Payment method not found.');
            return $this->respondWithData($error, 404);
        }

        return $this->respondWithData($paymentMethod);
    }
}
