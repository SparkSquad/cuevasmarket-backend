<?php

declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\DomainException\DomainException;

class ProductInvalidCategoryException extends DomainException
{
    public $message = 'The product category you requested is invalid.';
}
