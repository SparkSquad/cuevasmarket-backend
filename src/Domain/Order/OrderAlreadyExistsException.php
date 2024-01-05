<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\DomainException\DomainException;

class OrderAlreadyExistsException extends DomainException
{
    public $message = 'The order you requested already exists.';
}
