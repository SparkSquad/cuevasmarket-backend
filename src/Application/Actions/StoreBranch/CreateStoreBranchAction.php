<?php

declare(strict_types=1);

namespace App\Application\Actions\StoreBranch;

use App\Application\Actions\ActionError;
use App\Domain\Store\StoreBranch;
use App\Domain\Store\StoreBranchAlreadyExistsException;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class CreateStoreBranchAction extends StoreBranchAction
{
    protected function action(): Response
    {
        $newBranchData = $this->getFormData();
        if(is_null($newBranchData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $name = $newBranchData['name'];
        if(!isset($name) || !is_string($name)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid name.');
            return $this->respondWithData($error, 400);
        }

        $address = $newBranchData['address'];
        if(!isset($address) || !is_string($address)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid address.');
            return $this->respondWithData($error, 400);
        }

        $latitude = $newBranchData['latitude'];
        if(!isset($latitude) || !is_string($latitude)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid latitude.');
            return $this->respondWithData($error, 400);
        }

        $longitude = $newBranchData['longitude'];
        if(!isset($longitude) || !is_string($longitude)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid longitude.');
            return $this->respondWithData($error, 400);
        }

        $openingHours = $newBranchData['openingHours'];
        if(!isset($openingHours) || !is_string($openingHours)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid opening hours.');
            return $this->respondWithData($error, 400);
        }

        $closingHours = $newBranchData['closingHours'];
        if(!isset($closingHours) || !is_string($closingHours)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid closing hours.');
            return $this->respondWithData($error, 400);
        }

        try {
            $parsedOpeningHours = date_create_from_format('H:i', $openingHours);
            $parsedClosingHours = date_create_from_format('H:i', $closingHours);

            if($parsedOpeningHours === false || $parsedClosingHours === false) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid opening or closing hours.');
                return $this->respondWithData($error, 400);
            }

            $storeBranch = new StoreBranch(
                $name,
                $address,
                $latitude,
                $longitude,
                $parsedOpeningHours,
                $parsedClosingHours
            );

            $this->storeBranchRepository->save($storeBranch);
            return $this->respondWithData(null, 201);
        }
        catch(StoreBranchAlreadyExistsException $e) {
            $error = new ActionError(ActionError::CONFLICT, 'A store branch with the same name already exists.');
            return $this->respondWithData($error, 409);
        }
        catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $error = new ActionError(ActionError::SERVER_ERROR, 'Internal server error.');
            return $this->respondWithData($error, 500);
        }
    }
}
