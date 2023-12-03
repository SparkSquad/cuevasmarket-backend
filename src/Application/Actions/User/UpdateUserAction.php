<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\ActionError;
use App\Domain\User\User;
use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserInvalidTypeException;
use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class UpdateUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $newUserData = $this->getFormData();
        if(is_null($newUserData)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

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

        $loggedUser = $this->request->getAttribute('loggedUser');
        $type = User::USER_TYPE_CUSTOMER;
        if($loggedUser && $loggedUser['type'] !== User::USER_TYPE_ADMIN && $loggedUser['id'] !== $user->getId()) {
            $error = new ActionError(ActionError::INSUFFICIENT_PRIVILEGES, 'Insufficient privileges.');
            return $this->respondWithData($error, 403);
        }

        $email = $newUserData['email'];
        if(isset($email)) {
            if(!is_string($email)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid email.');
                return $this->respondWithData($error, 400);
            }
            $user->setEmail($email);
        }

        $password = $newUserData['password'];
        if(isset($password)) {
            if(!is_string($password)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid password.');
                return $this->respondWithData($error, 400);
            }
            $user->changePassword($password);
        }

        $firstName = $newUserData['firstName'];
        if(isset($firstName)) {
            if(!is_string($firstName)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid first name.');
                return $this->respondWithData($error, 400);
            }
            $user->setFirstName($firstName);
        }

        $surnames = $newUserData['surnames'];
        if(isset($surnames)) {
            if(!is_string($surnames)) {
                $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid surnames.');
                return $this->respondWithData($error, 400);
            }
            $user->setSurnames($surnames);
        }

        try {
            $this->userRepository->update($user);
            return $this->respondWithData($user, 200);
        }
        catch(UserNotFoundException $e) {
            $this->logger->warning("User of id `{$id}` was not found. This should not happen.");
            $error = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'User not found.');
            return $this->respondWithData($error, 400);
        }
        catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $error = new ActionError(ActionError::SERVER_ERROR, 'Internal server error.');
            return $this->respondWithData($error, 500);
        }
    }
}
