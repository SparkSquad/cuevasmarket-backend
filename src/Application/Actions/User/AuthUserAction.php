<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\ActionError;
use App\Domain\User\UserNotFoundException;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;

class AuthUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $credentials = $this->getFormData();
        if(is_null($credentials)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid request body.');
            return $this->respondWithData($error, 400);
        }

        $email = $credentials['email'];
        if(isset($email) && !is_string($email)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid email.');
            return $this->respondWithData($error, 400);
        }

        $password = $credentials['password'];
        if(isset($password) && !is_string($password)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid password.');
            return $this->respondWithData($error, 400);
        }

        $user = $this->userRepository->findByEmail($email);
        if(!$user) {
            $this->logger->info("User of email `{$email}` does not exist.");
            throw new UserNotFoundException("User of email '{$email}' does not exist.", 404);
        }

        if($user->authenticate($password)) {
            $key = $_ENV['JWT_SECRET'];
            $payload = array(
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getFirstName(),
                'surnames' => $user->getSurnames(),
                'type' => $user->getType(),
                'iat' => time()
            );
            $token = JWT::encode($payload, $key, 'HS256');
            $responseData = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getFirstName(),
                'surnames' => $user->getSurnames(),
                'type' => $user->getType(),
                'token' => $token
            ];
            return $this->respondWithData($responseData);
        } 
        else {
            $this->logger->info("User of email `{$email}` was not authenticated.");
            throw new UserNotFoundException("User of email '{$email}' does not exist.", 404);
        }
    }
}
