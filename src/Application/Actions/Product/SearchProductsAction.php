<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class SearchProductsAction extends ProductAction
{
    protected function action(): Response
    {
        $keyword = $this->args['keyword'] ?? '';
        $queryParams = $this->request->getQueryParams();
        $maxResults = isset($queryParams['maxResults']) ? intval($queryParams['maxResults']) : 10;
        $page = isset($queryParams['page']) ? intval($queryParams['page']) : 1;
        $users = $this->productRepository->search($keyword, $maxResults, $page);
        return $this->respondWithData($users);
    }
}
