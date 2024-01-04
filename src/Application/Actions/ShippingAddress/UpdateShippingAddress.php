<?php

declare(strict_types=1);

namespace App\Application\Actions\ShippingAddress;

use App\Application\Actions\ActionError;
use App\Domain\ShippingAddress\ShippingAddress;
use Psr\Log\LoggerInterface;
use Exception;

class UpdateShippingAddressAction extends ShippingAddressAction
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

        $shippingAddressData = $this->getFormData();
        if(is_null($shippingAddressData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $name = $shippingAddressData['name'];
        if(!isset($name) || !is_string($name)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid name.');
            return $this->respondWithData($error, 400);
        }

        $address = $shippingAddressData['address'];
        if(!isset($address) || !is_string($address)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid address.');
            return $this->respondWithData($error, 400);
        }

        $city = $shippingAddressData['city'];
        if(!isset($city) || !is_string($city)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid city.');
            return $this->respondWithData($error, 400);
        }

        $state = $shippingAddressData['state'];
        if(!isset($state) || !is_string($state)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid state.');
            return $this->respondWithData($error, 400);
        }

        $zipCode = $shippingAddressData['zipCode'];
        if(!isset($zipCode) || !is_string($zipCode)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid zip code.');
            return $this->respondWithData($error, 400);
        }

        return $this->respondWithData($shippingAddress);
    }
}
