<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\ProductStock;

use App\Domain\Product\Product;
use App\Domain\Product\ProductAlreadyExistsException;
use App\Domain\Product\ProductNotFoundException;
use App\Domain\Product\ProductRepository;
use App\Domain\ProductStock\ProductStock;
use App\Domain\ProductStock\ProductStockRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\Persistence\ObjectRepository;

class DoctrineProductStockRepository implements ProductStockRepository
{
    private EntityManager $em;
    private ObjectRepository $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(ProductStock::class);
    }

    public function findById(int $id): ?ProductStock
    {
        return $this->repository->find($id);
    }

    public function findByStoreId(int $storeId): ?array
    {
        $query = $this->em->createQueryBuilder()
            ->select('productStock')
            ->from(ProductStock::class, 'productStock')
            ->where('productStock.store = :storeId')
            ->setParameter('storeId', $storeId)
            ->getQuery();
        return $query->getResult();
    }

    public function findByProductId(int $productId): ?array
    {
        $query = $this->em->createQueryBuilder()
            ->select('productStock')
            ->from(ProductStock::class, 'productStock')
            ->where('productStock.product = :productId')
            ->setParameter('productId', $productId)
            ->getQuery();
        return $query->getResult();
    }

    public function search(string $storeId, string $keyword, int $maxResults, int $page): array
    {
        $query = $this->em->createQueryBuilder()
            ->select('productStock')
            ->from(ProductStock::class, 'productStock')
            ->where('productStock.store = :storeId')
            ->andWhere('productStock.product.name LIKE :keyword')
            ->setParameter('storeId', $storeId)
            ->setParameter('keyword', '%' . $keyword . '%')
            ->setFirstResult($maxResults * ($page - 1))
            ->setMaxResults($maxResults)
            ->getQuery();
        return $query->getResult();
    }

    public function save(ProductStock $stock): void
    {
        $this->em->persist($stock);
        $this->em->flush();
    }

    public function update(ProductStock $stock): void
    {
        $this->em->flush();
    }

    public function delete(ProductStock $stock): void
    {
        $this->em->remove($stock);
        $this->em->flush();
    }
}
