<?php

declare(strict_types=1);

namespace App\Domain\OrderItems;

use App\Domain\DomainException\DomainRecordNotFoundException;

class OrderItemsNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The order items you requested do not exist.';
}
