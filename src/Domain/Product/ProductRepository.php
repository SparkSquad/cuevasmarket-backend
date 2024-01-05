<?php

declare(strict_types=1);

namespace App\Domain\Product;

interface ProductRepository
{
    /**
     * @param int $id
     * @return Product
     */
    public function findById(int $id): ?Product;

    /**
     * @param string $keyword
     * @param int $maxResults
     * @param int $page
     * @param string|null $category
     * @return Product[]
     */
    public function search(string $keyword, int $maxResults, int $page, string $category = null): ProductSearchResultsDTO;

    /**
     * @param Product $product
     * @throws ProductAlreadyExistsException
     */
    public function save(Product $product): void;

    /**
     * @param Product $product
     * @throws ProductNotFoundException
     */
    public function update(Product $product): void;

    /**
     * @param Product $product
     * @throws ProductNotFoundException
     */
    public function delete(Product $product): void;
}
