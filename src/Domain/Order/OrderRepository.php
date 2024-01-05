<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Order\Order;
use App\Domain\Order\OrderNotFoundException;
use App\Domain\Order\OrderAlreadyExistsException;

interface OrderRepository
{
    /**
     * @param int $id
     * @return Order
     * @throws OrderNotFoundException
     */
    public function findById(int $id): Order;

    /**
     * @param int $userId
     * @return Order[]
     */
    public function findByUserId(int $userId): array;

    /**
     * @param string $userId
     * @param string $keyword
     * @param int $maxResults
     * @param int $page
     * @return Order[]
     */
    public function search(string $userId, string $keyword, int $maxResults, int $page): array;

    /**
     * @param Order $order
     * @throws OrderAlreadyExistsException
     */
    public function save(Order $order): void;

    /**
     * @param Order $order
     * @throws OrderNotFoundException
     */
    public function update(Order $order): void;
}
