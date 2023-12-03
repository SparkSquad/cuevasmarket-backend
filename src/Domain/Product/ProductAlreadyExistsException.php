<?php

declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\DomainException\DomainException;

class ProductAlreadyExistsException extends DomainException
{
    public $message = 'The product you requested already exists.';
}
