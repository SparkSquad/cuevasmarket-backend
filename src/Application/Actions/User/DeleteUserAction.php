<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\ActionError;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = $this->args['id'];
        if(!isset($id) || !is_numeric($id)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid user id.');
            return $this->respondWithData($error, 400);
        }

        $user = $this->userRepository->findById(intval($id));
        if(!$user) {
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'User not found.');
            return $this->respondWithData($error, 404);
        }

        $this->userRepository->delete($user);
        return $this->respondWithData();
    }
}
