<?php

declare(strict_types=1);

namespace App\Domain\PaymentMethod;

use App\Domain\PaymentMethod\PaymentMethodNotFoundException;

interface PaymentMethodRepository
{
    /**
     * @param int $id
     * @return PaymentMethod
     */
    public function findById(int $id): ?PaymentMethod;

    /**
     * @param int $userId
     * @return PaymentMethod[]
     */
    public function findByUserId(int $userId): ?array;

    /**
     * @param PaymentMethod $paymentMethod
     * @throws PaymentMethodAlreadyExistsException
     */
    public function save(PaymentMethod $paymentMethod): void;

    /**
     * @param PaymentMethod $paymentMethod
     * @throws PaymentMethodNotFoundException
     */
    public function update(PaymentMethod $paymentMethod): void;

    /**
     * @param PaymentMethod $paymentMethod
     * @throws PaymentMethodNotFoundException
     */
    public function delete(PaymentMethod $paymentMethod): void;
}
