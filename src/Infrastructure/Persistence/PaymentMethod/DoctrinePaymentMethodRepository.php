<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\PaymentMethod;

use App\Domain\PaymentMethod\PaymentMethod;
use App\Domain\PaymentMethod\PaymentMethodRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;

class DoctrinePaymentMethodRepository implements PaymentMethodRepository
{
    private EntityManager $em;
    private ObjectRepository $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(PaymentMethod::class);
    }

    public function findById(int $id): ?PaymentMethod
    {
        return $this->repository->find($id);
    }

    public function findByUserId(int $userId): ?array
    {
        $query = $this->em->createQueryBuilder()
            ->select('paymentMethod')
            ->from(PaymentMethod::class, 'paymentMethod')
            ->where('paymentMethod.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();
        return $query->getResult();
    }

    public function save(PaymentMethod $paymentMethod): void
    {
        $this->em->persist($paymentMethod);
        $this->em->flush();
    }

    public function update(PaymentMethod $paymentMethod): void
    {
        $this->em->merge($paymentMethod);
        $this->em->flush();
    }

    public function delete(PaymentMethod $paymentMethod): void
    {
        $this->em->remove($paymentMethod);
        $this->em->flush();
    }
}
