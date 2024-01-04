<?php

declare(strict_types=1);

namespace App\Domain\ShippingAddress;

use App\Domain\ShippingAddress\ShippingAddress;
use App\Domain\ShippingAddress\ShippingAddressRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;

class DoctrineShippingAddressRepository implements ShippingAddressRepository
{
    private EntityManager $em;
    private ObjectRepository $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(ShippingAddress::class);
    }

    public function findById(int $id): ?ShippingAddress
    {
        return $this->repository->find($id);
    }

    public function findByUserId(int $userId): ?array
    {
        $query = $this->em->createQueryBuilder()
            ->select('shippingAddress')
            ->from(ShippingAddress::class, 'shippingAddress')
            ->where('shippingAddress.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();
        return $query->getResult();
    }

    public function save(ShippingAddress $shippingAddress): void
    {
        $this->em->persist($shippingAddress);
        $this->em->flush();
    }

    public function update(ShippingAddress $shippingAddress): void
    {
        $this->em->merge($shippingAddress);
        $this->em->flush();
    }

    public function delete(ShippingAddress $shippingAddress): void
    {
        $this->em->remove($shippingAddress);
        $this->em->flush();
    }
}
