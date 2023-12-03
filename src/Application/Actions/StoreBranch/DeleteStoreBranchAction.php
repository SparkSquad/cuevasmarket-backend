<?php

declare(strict_types=1);

namespace App\Application\Actions\StoreBranch;

use App\Application\Actions\ActionError;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteStoreBranchAction extends StoreBranchAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
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

        $this->storeBranchRepository->delete($storeBranch);
        return $this->respondWithData();
    }
}
