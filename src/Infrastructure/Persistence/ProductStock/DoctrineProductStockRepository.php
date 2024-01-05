<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\ProductStock;

use App\Domain\Product\Product;
use App\Domain\ProductStock\ProductStock;
use App\Domain\ProductStock\ProductStockRepository;
use App\Domain\Store\StoreBranch;
use Doctrine\ORM\EntityManager;
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

    public function findByProductIdAndStoreBranchId(int $productId, int $storeBranchId): ?ProductStock
    {
        $productStockExists = $this->repository->findOneBy([
            'product' => $productId,
            'storeBranch' => $storeBranchId
        ]);

        // if does not exist, create new one
        if (is_null($productStockExists)) {
            $productStock = new ProductStock(
                $this->em->getReference(Product::class, $productId),
                $this->em->getReference(StoreBranch::class, $storeBranchId),
                0
            );
            $this->save($productStock);
        }

        $query = $this->em->createQueryBuilder()
            ->select('productStock')
            ->from(ProductStock::class, 'productStock')
            ->where('productStock.product = :productId')
            ->andWhere('productStock.store = :storeBranchId')
            ->setParameter('productId', $productId)
            ->setParameter('storeBranchId', $storeBranchId)
            ->getQuery();
        return $query->getOneOrNullResult();
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

    public function addProductStock(int $storeBranchId, int $productId, int $quantity): ProductStock {
        $productStock = $this->findByProductIdAndStoreBranchId($productId, $storeBranchId);
        $productStock->setStock($productStock->getStock() + $quantity);
        $this->update($productStock);
        return $productStock;
    }
}
