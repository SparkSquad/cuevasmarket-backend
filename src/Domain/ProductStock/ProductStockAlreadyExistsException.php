<?php

declare(strict_types=1);

namespace App\Domain\ProductStock;

use App\Domain\DomainException\DomainException;

class ProductStockAlreadyExistsException extends DomainException
{
    public $message = 'The product stock you requested already exists.';
}
