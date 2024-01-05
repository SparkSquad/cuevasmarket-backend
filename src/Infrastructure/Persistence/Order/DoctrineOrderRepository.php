<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Order;

use App\Domain\Order\Order;
use App\Domain\Order\OrderRepository;
use App\Domain\Order\OrderNotFoundException;
use App\Domain\Order\OrderAlreadyExistsException;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;

class DoctrineOrderRepository implements OrderRepository
{
    private EntityManager $em;
    private ObjectRepository $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(Order::class);
    }

    public function findById(int $id): Order
    {
        $order = $this->repository->find($id);
        if (is_null($order)) {
            throw new OrderNotFoundException();
        }
        return $order;
    }

    public function findByUserId(int $userId): array
    {
        $query = $this->em->createQueryBuilder()
            ->select('order')
            ->from(Order::class, 'order')
            ->where('order.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();
        return $query->getResult();
    }

    public function search(string $userId, string $keyword, int $maxResults, int $page): array
    {
        $query = $this->em->createQueryBuilder()
            ->select('order')
            ->from(Order::class, 'order')
            ->where('order.user = :userId')
            ->andWhere('order.orderNumber LIKE :keyword')
            ->setParameter('userId', $userId)
            ->setParameter('keyword', '%' . $keyword . '%')
            ->setMaxResults($maxResults)
            ->setFirstResult($page * $maxResults)
            ->getQuery();
        return $query->getResult();
    }

    public function save(Order $order): void
    {
        $orderExists = $this->repository->findOneBy([
            'orderNumber' => $order->getOrderNumber()
        ]);
        if (!is_null($orderExists)) {
            throw new OrderAlreadyExistsException();
        }
        $this->em->persist($order);
        $this->em->flush();
    }

    public function update(Order $order): void
    {
        $this->em->flush();
    }
}
