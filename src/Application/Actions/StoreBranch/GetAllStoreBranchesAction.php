<?php

declare(strict_types=1);

namespace App\Application\Actions\StoreBranch;

use App\Domain\Store\StoreBranchNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllStoreBranchesAction extends StoreBranchAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $storeBranches = $this->storeBranchRepository->findAll();
        return $this->respondWithData($storeBranches);
    }
}
