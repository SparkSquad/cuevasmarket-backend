<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\DomainException;

class UserAlreadyExistsException extends DomainException
{
    public $message = 'The user you requested already exists.';
}
