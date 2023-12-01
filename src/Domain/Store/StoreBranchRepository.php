<?php

declare(strict_types=1);

namespace App\Domain\Store;

interface StoreBranchRepository
{
    /**
     * @return StoreBranch[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return StoreBranch
     */
    public function findById(int $id): ?StoreBranch;

    /**
     * @param string $query
     * @param int $elems
     * @param int $page
     * @return StoreBranch[]
     */
    public function search(string $keyword, int $maxResults, int $page): array;

    /**
     * @param StoreBranch $branch
     * @throws StoreBranchAlreadyExistsException
     */
    public function save(StoreBranch $branch): void;

    /**
     * @param StoreBranch $branch
     * @throws StoreBranchNotFoundException
     */
    public function update(StoreBranch $branch): void;

    /**
     * @param StoreBranch $branch
     * @throws StoreBranchNotFoundException
     */
    public function delete(StoreBranch $branch): void;
}
