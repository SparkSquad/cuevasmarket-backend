<?php

declare(strict_types=1);

namespace App\Domain\PaymentMethod;

use App\Domain\DomainException\DomainRecordNotFoundException;

class PaymentMethodNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The payment method you requested does not exist.';
}
