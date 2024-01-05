<?php

declare(strict_types=1);

namespace App\Application\Actions\ProductStock;

use App\Application\Actions\ActionError;
use Psr\Http\Message\ResponseInterface as Response;

class SearchProductStockAction extends ProductStockAction
{
    protected function action(): Response
    {
        $branchId = $this->args['branchId'];
        if(!isset($branchId)) {
            $error = new ActionError(ActionError::BAD_REQUEST, 'Invalid branch id.');
            return $this->respondWithData($error, 400);
        }

        $keyword = $this->args['keyword'] ?? '';
        $queryParams = $this->request->getQueryParams();
        $maxResults = intval($queryParams['maxResults'] ?? 10);
        $page = intval($queryParams['page'] ?? 1);
        $users = $this->productStockRepository->search(intval($branchId), $keyword, $maxResults, $page);
        return $this->respondWithData($users);
    }
}
