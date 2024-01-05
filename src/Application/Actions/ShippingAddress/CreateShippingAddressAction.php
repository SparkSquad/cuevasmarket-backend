<?php

declare(strict_types=1);

namespace App\Application\Actions\ShippingAddress;

use App\Application\Actions\ActionError;
use App\Domain\ShippingAddress\ShippingAddress;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class CreateShippingAddressAction extends ShippingAddressAction
{
    protected function action(): Response
    {
        $loggedUser = $this->request->getAttribute('loggedUser');
        $userId = (int) $loggedUser['id'];

        $newShippingAddressData = $this->getFormData();
        if(is_null($newShippingAddressData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $name = $newShippingAddressData['name'];
        if(!isset($name) || !is_string($name)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid name.');
            return $this->respondWithData($error, 400);
        }

        $address = $newShippingAddressData['address'];
        if(!isset($address) || !is_string($address)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid address.');
            return $this->respondWithData($error, 400);
        }

        $city = $newShippingAddressData['city'];
        if(!isset($city) || !is_string($city)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid city.');
            return $this->respondWithData($error, 400);
        }

        $state = $newShippingAddressData['state'];
        if(!isset($state) || !is_string($state)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid state.');
            return $this->respondWithData($error, 400);
        }

        $zipCode = $newShippingAddressData['zipCode'];
        if(!isset($zipCode) || !is_string($zipCode)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid zip code.');
            return $this->respondWithData($error, 400);
        }

        $country = $newShippingAddressData['country'];
        if(!isset($country) || !is_string($country)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid country.');
            return $this->respondWithData($error, 400);
        }

        $shippingAddress = new ShippingAddress();
        $shippingAddress->setUserId($userId);
        $shippingAddress->setName($name);
        $shippingAddress->setAddress($address);
        $shippingAddress->setCity($city);
        $shippingAddress->setState($state);
        $shippingAddress->setZipCode($zipCode);

        try {
            $this->shippingAddressRepository->save($shippingAddress);
        } catch(Exception $e) {
            $error = new ActionError(ActionError::SERVER_ERROR, 'Error creating shipping address.');
            return $this->respondWithData($error, 500);
        }

        return $this->respondWithData($shippingAddress);
    }
}
