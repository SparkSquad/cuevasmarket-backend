<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\OrderItems;

use App\Domain\Order\Order;
use App\Domain\OrderItems\OrderItems;
use App\Domain\Product\Product;
use App\Domain\OrderItems\OrderItemsNotFoundException;
use App\Domain\OrderItems\OrderItemsAlreadyExistsException;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;

class DoctrineOrderItemsRepository implements OrderItemsRepository
{
    private EntityManager $em;
    private ObjectRepository $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(OrderItems::class);
    }

    public function findById(int $id): OrderItems
    {
        $orderItems = $this->repository->find($id);
        if (is_null($orderItems)) {
            throw new OrderItemsNotFoundException();
        }
        return $orderItems;
    }

    public function findByOrderId(int $orderId): array
    {
        $query = $this->em->createQueryBuilder()
            ->select('orderItems')
            ->from(OrderItems::class, 'orderItems')
            ->where('orderItems.order = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery();
        return $query->getResult();
    }

    public function save(OrderItems $orderItems): void
    {
        $this->em->persist($orderItems);
        $this->em->flush();
    }

    public function update(OrderItems $orderItems): void
    {
        $this->em->flush();
    }
}
