<?php

declare(strict_types=1);

namespace App\Application\Actions\StoreBranch;

use Psr\Http\Message\ResponseInterface as Response;

class SearchStoreBranchAction extends StoreBranchAction
{
    protected function action(): Response
    {
        $keyword = $this->args['keyword'] ?? '';
        $queryParams = $this->request->getQueryParams();
        $maxResults = intval($queryParams['maxResults']) ?? 10;
        $page = intval($queryParams['page']) ?? 1;
        $users = $this->storeBranchRepository->search($keyword, $maxResults, $page);
        return $this->respondWithData($users);
    }
}
