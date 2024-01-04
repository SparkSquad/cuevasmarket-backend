<?php

declare(strict_types=1);

namespace App\Domain\ShippingAddress;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ShippingAddressNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The shipping address you requested does not exist.';
}
