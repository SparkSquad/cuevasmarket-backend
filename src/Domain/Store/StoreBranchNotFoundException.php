<?php

declare(strict_types=1);

namespace App\Domain\Store;

use App\Domain\DomainException\DomainRecordNotFoundException;

class StoreBranchNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The store branch you requested does not exist.';
}
