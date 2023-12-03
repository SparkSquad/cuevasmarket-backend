<?php

declare(strict_types=1);

namespace App\Domain\ProductStock;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ProductStockNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The product stock you requested does not exist.';
}
