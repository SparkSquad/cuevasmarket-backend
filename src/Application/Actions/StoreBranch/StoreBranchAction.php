<?php

declare(strict_types=1);

namespace App\Application\Actions\StoreBranch;

use App\Application\Actions\Action;
use App\Domain\Store\StoreBranchRepository;
use Psr\Log\LoggerInterface;

abstract class StoreBranchAction extends Action
{
    protected StoreBranchRepository $storeBranchRepository;

    public function __construct(LoggerInterface $logger, StoreBranchRepository $storeBranchRepository)
    {
        parent::__construct($logger);
        $this->storeBranchRepository = $storeBranchRepository;
    }
}
