<?php

declare(strict_types=1);

namespace App\Domain\Product;

use JsonSerializable;

class ProductSearchResultsDTO implements JsonSerializable
{
    private array $results;
    private int $totalPages;
    private int $currentPage;

    public function __construct(array $results, int $totalPages, int $currentPage) {
        $this->results = $results;
        $this->totalPages = $totalPages;
        $this->currentPage = $currentPage;
    }

    public function getResults(): array {
        return $this->results;
    }

    public function getTotalPages(): int {
        return $this->totalPages;
    }

    public function getCurrentPage(): int {
        return $this->currentPage;
    }

    public function jsonSerialize(): array {
        return [
            'results' => $this->results,
            'totalPages' => $this->totalPages,
            'currentPage' => $this->currentPage
        ];
    }
}
