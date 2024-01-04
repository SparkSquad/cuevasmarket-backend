<?php

declare(strict_types=1);

namespace App\Application\Actions\ShippingAddress;

use App\Application\Actions\ActionError;
use Psr\Log\LoggerInterface;
use Exception;

class DeleteShippingAddressAction extends ShippingAddressAction
{
    protected function action()
    {
        $loggedUser = $this->request->getAttribute('loggedUser');
        $userId = (int) $loggedUser['id'];

        $shippingAddressId = (int) $this->resolveArg('id');
        $shippingAddress = $this->shippingAddressRepository->findShippingAddressOfId($shippingAddressId);
        if(is_null($shippingAddress)) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Shipping address not found.');
            return $this->respondWithData($error, 404);
        }

        try {
            $this->shippingAddressRepository->delete($shippingAddress);
        } catch(Exception $e) {
            $error = new ActionError(ActionError::SERVER_ERROR, 'Error deleting shipping address.');
            return $this->respondWithData($error, 500);
        }

        return $this->respondWithData('Shipping address deleted.');
    }
}
