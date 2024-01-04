<?php

declare(strict_types=1);

namespace App\Domain\ShippingAddress;

use App\Domain\ShippingAddress\ShippingAddressNotFoundException;

interface ShippingAddressRepository
{
    /**
     * @param int $id
     * @return ShippingAddress
     */
    public function findById(int $id): ?ShippingAddress;

    /**
     * @param int $userId
     * @return ShippingAddress[]
     */
    public function findByUserId(int $userId): ?array;

    /**
     * @param ShippingAddress $shippingAddress
     * @throws ShippingAddressAlreadyExistsException
     */
    public function save(ShippingAddress $shippingAddress): void;

    /**
     * @param ShippingAddress $shippingAddress
     * @throws ShippingAddressNotFoundException
     */
    public function update(ShippingAddress $shippingAddress): void;

    /**
     * @param ShippingAddress $shippingAddress
     * @throws ShippingAddressNotFoundException
     */
    public function delete(ShippingAddress $shippingAddress): void;
}
