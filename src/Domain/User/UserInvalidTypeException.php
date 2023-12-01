<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\DomainException;

class UserInvalidTypeException extends DomainException
{
    public $message = 'The user type you requested is invalid.';
}
