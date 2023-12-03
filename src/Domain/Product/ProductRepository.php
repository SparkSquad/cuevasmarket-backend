<?php

declare(strict_types=1);

namespace App\Domain\Product;

interface ProductRepository
{
    /**
     * @return Product[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Product
     * @throws ProductNotFoundException
     */
    public function findById(int $id): ?Product;

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
