<?php

declare(strict_types=1);

namespace App\Domain\Store;

use App\Domain\DomainException\DomainException;

class StoreBranchAlreadyExistsException extends DomainException
{
    public $message = 'The store branch you requested already exists.';
}
