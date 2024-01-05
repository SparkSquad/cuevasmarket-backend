<?php

declare(strict_types=1);

namespace App\Domain\ProductStock;

use App\Domain\Product\ProductNotFoundException;
use App\Domain\Product\ProductSearchResultsDTO;
use App\Domain\Store\StoreBranchNotFoundException;

interface ProductStockRepository
{
    /**
     * @param int $id
     * @return Product
     */
    public function findById(int $id): ?ProductStock;

    /**
     * If the product stock does not exist, it will be created
     * @param int $productId
     * @param int $storeBranchId
     * @return ProductStock
     */
    public function findByProductIdAndStoreBranchId(int $productId, int $storeBranchId): ?ProductStock;

    /**
     * @param int $storeId
     * @return ProductStock[]
     */
    public function findByStoreId(int $storeId): ?array;

    /**
     * @param int $productId
     * @return ProductStock[]
     */
    public function findByProductId(int $productId): ?array;

    /**
     * @param int $storeBranchId
     * @param int $productId
     * @param int $quantity
     * @return ProductStock[]
     */
    public function addProductStock(int $storeBranchId, int $productId, int $quantity): ProductStock;

    /**
     * @param string $storeId
     * @param string $keyword
     * @param int $maxResults
     * @param int $page
     * @return Product[]
     */
    public function search(int $storeId, string $keyword, int $maxResults, int $page): ProductSearchResultsDTO;

    /**
     * @param ProductStock $stock
     * @throws ProductStockAlreadyExistsException
     * @throws ProductNotFoundException
     * @throws StoreBranchNotFoundException
     */
    public function save(ProductStock $stock): void;

    /**
     * @param ProductStock $stock
     * @throws ProductStockNotFoundException
     */
    public function update(ProductStock $stock): void;

    /**
     * @param ProductStock $stock
     * @throws ProductStockNotFoundException
     */
    public function delete(ProductStock $stock): void;
}
