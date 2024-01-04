<?php

declare(strict_types=1);

namespace App\Application\Actions\ShippingAddress;

use App\Domain\ShippingAddress\ShippingAddress;
use App\Domain\ShippingAddress\ShippingAddressNotFoundException;
use App\Domain\ShippingAddress\ShippingAddressRepository;
use App\Application\Actions\ActionError;
use Psr\Http\Message\ResponseInterface as Response;

class ViewShippingAddressesAction extends ShippingAddressAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('userId');
        try {
            $shippingAddresses = $this->shippingAddressRepository->findByUserId($userId);
            if(is_null($shippingAddresses)) {
                $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Shipping address not found.');
                return $this->respondWithData($error, 404);
            }
        } catch (ShippingAddressNotFoundException $e) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Shipping address not found.');
            return $this->respondWithData($error, 404);
        }
        return $this->respondWithData($shippingAddresses);
    }
}
