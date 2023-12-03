<?php

declare(strict_types=1);

namespace App\Application\Actions\StoreBranch;

use App\Domain\Store\StoreBranchNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class ViewStoreBranchAction extends StoreBranchAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $storeBranchId = (int) $this->resolveArg('id');
        $storeBranch = $this->storeBranchRepository->findById($storeBranchId);
        if(!$storeBranch) {
            throw new StoreBranchNotFoundException();
        }
        return $this->respondWithData($storeBranch);
    }
}
