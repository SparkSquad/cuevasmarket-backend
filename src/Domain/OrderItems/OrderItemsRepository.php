<?php

declare(strict_types=1);

namespace App\Domain\OrderItems;

use App\Domain\Order\Order;
use App\Domain\OrderItems\OrderItems;
use App\Domain\Product\Product;
use App\Domain\OrderItems\OrderItemsNotFoundException;
use App\Domain\OrderItems\OrderItemsAlreadyExistsException;

interface OrderItemsRepository
{
    /**
     * @param int $id
     * @return OrderItems
     * @throws OrderItemsNotFoundException
     */
    public function findById(int $id): OrderItems;

    /**
     * @param int $orderId
     * @return OrderItems[]
     */
    public function findByOrderId(int $orderId): array;

    /**
     * @param OrderItems $orderItems
     * @throws OrderItemsAlreadyExistsException
     */
    public function save(OrderItems $orderItems): void;

    /**
     * @param OrderItems $orderItems
     * @throws OrderItemsNotFoundException
     */
    public function update(OrderItems $orderItems): void;
}
