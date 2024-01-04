<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Product;

use App\Domain\Product\Product;
use App\Domain\Product\ProductAlreadyExistsException;
use App\Domain\Product\ProductNotFoundException;
use App\Domain\Product\ProductRepository;
use App\Domain\Product\ProductSearchResultsDTO;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;

class DoctrineProductRepository implements ProductRepository
{
    private EntityManager $em;
    private ObjectRepository $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(Product::class);
    }

    public function findById(int $id): ?Product
    {
        return $this->repository->find($id);
    }

    public function search(string $keyword, int $maxResults, int $page, ?string $category = null): ProductSearchResultsDTO
    {
        if ($category) {
            $query = $this->em->createQueryBuilder()
                ->select('product')
                ->from(Product::class, 'product')
                ->where('product.name LIKE :keyword')
                ->andWhere('product.category = :category')
                ->setParameter('keyword', '%' . $keyword . '%')
                ->setParameter('category', $category)
                ->setFirstResult($maxResults * ($page - 1))
                ->setMaxResults($maxResults)
                ->getQuery();
            $results = $query->getResult();

            // Get the total number of pages
            $query = $this->em->createQueryBuilder()
                ->select('COUNT(product)')
                ->from(Product::class, 'product')
                ->where('product.name LIKE :keyword')
                ->andWhere('product.category = :category')
                ->setParameter('keyword', '%' . $keyword . '%')
                ->setParameter('category', $category)
                ->getQuery();
            $totalElems = $query->getSingleScalarResult();
            $totalPages = intval(ceil($totalElems / $maxResults));

            return new ProductSearchResultsDTO($results, $totalPages, $page);
        }
        else {
            $query = $this->em->createQueryBuilder()
                ->select('product')
                ->from(Product::class, 'product')
                ->where('product.name LIKE :keyword')
                ->setParameter('keyword', '%' . $keyword . '%')
                ->setFirstResult($maxResults * ($page - 1))
                ->setMaxResults($maxResults)
                ->getQuery();
            $results = $query->getResult();

            // Get the total number of pages
            $query = $this->em->createQueryBuilder()
                ->select('COUNT(product)')
                ->from(Product::class, 'product')
                ->where('product.name LIKE :keyword')
                ->setParameter('keyword', '%' . $keyword . '%')
                ->getQuery();

            $totalElems = $query->getSingleScalarResult();
            $totalPages = intval(ceil($totalElems / $maxResults));

            return new ProductSearchResultsDTO($results, $totalPages, $page);
        }
    }

    public function save(Product $product): void
    {
        $productExists = $this->repository->findOneBy(['barcode' => $product->getBarcode()]);
        if ($productExists) {
            throw new ProductAlreadyExistsException();
        }
        $this->em->persist($product);
        $this->em->flush();
    }

    public function update(Product $product): void
    {
        $this->em->flush();
    }

    public function delete(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();
    }
}
