<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\DomainException;

class UserUnauthorizedAccessException extends DomainException
{
    public $message = 'You are not authorized to access this resource.';
}
