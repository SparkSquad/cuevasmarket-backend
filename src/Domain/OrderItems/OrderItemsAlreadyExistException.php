<?php

declare(strict_types=1);

namespace App\Domain\OrderItems;

use App\Domain\DomainException\DomainException;

class OrderItemsAlreadyExistException extends DomainException
{
    public $message = 'The order items already exist.';
}
