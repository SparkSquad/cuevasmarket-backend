<?php

declare(strict_types=1);

namespace App\Application\Actions\StoreBranch;

use App\Application\Actions\ActionError;
use App\Domain\Store\StoreBranch;
use App\Domain\Store\StoreBranchAlreadyExistsException;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class UpdateStoreBranchAction extends StoreBranchAction
{
    protected function action(): Response
    {
        $newBranchData = $this->getFormData();
        if(is_null($newBranchData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $id = $this->args['id'];
        if(!isset($id) || !is_numeric($id)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid product id.');
            return $this->respondWithData($error, 400);
        }

        $storeBranch = $this->storeBranchRepository->findById(intval($id));
        if(!$storeBranch) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Store branch not found.');
            return $this->respondWithData($error, 404);
        }

        $name = $newBranchData['name'];
        if(isset($name)) {
            if(!is_string($name)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid name.');
                return $this->respondWithData($error, 400);
            }
            $storeBranch->setName($name);
        }

        $address = $newBranchData['address'];
        if(isset($address)) {
            if(!is_string($address)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid address.');
                return $this->respondWithData($error, 400);
            }
            $storeBranch->setAddress($address);
        }

        $latitude = $newBranchData['latitude'];
        if(isset($latitude)) {
            if(!is_string($latitude)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid latitude.');
                return $this->respondWithData($error, 400);
            }
            $storeBranch->setLatitude($latitude);
        }

        $longitude = $newBranchData['longitude'];
        if(isset($longitude)) {
            if(!is_string($longitude)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid longitude.');
                return $this->respondWithData($error, 400);
            }
            $storeBranch->setLongitude($longitude);
        }

        $openingHours = $newBranchData['openingHours'];
        if(isset($openingHours)) {
            if(!is_string($openingHours)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid opening hours.');
                return $this->respondWithData($error, 400);
            }
            $parsedOpeningHours = date_create_from_format('H:i', $openingHours);
            if($parsedOpeningHours === false) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid opening or closing hours.');
                return $this->respondWithData($error, 400);
            }
            $storeBranch->setOpeningHours($parsedOpeningHours);
        }

        $closingHours = $newBranchData['closingHours'];
        if(isset($closingHours)) {
            if(!is_string($closingHours)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid closing hours.');
                return $this->respondWithData($error, 400);
            }
            $parsedClosingHours = date_create_from_format('H:i', $closingHours);
            if($parsedClosingHours === false) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid opening or closing hours.');
                return $this->respondWithData($error, 400);
            }
            $storeBranch->setClosingHours($parsedClosingHours);
        }

        try {
            $this->storeBranchRepository->update($storeBranch);
            return $this->respondWithData(null, 204);
        }
        catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $error = new ActionError(ActionError::SERVER_ERROR, 'Internal server error.');
            return $this->respondWithData($error, 500);
        }
    }
}
