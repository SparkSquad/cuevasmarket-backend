<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Store;

use App\Domain\Store\StoreBranch;
use App\Domain\Store\StoreBranchAlreadyExistsException;
use App\Domain\Store\StoreBranchRepository;
use App\Domain\Store\StoreBranchSearchResultsDTO;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;

class DoctrineStoreBranchRepository implements StoreBranchRepository 
{
    private EntityManager $em;
    private ObjectRepository $repository;

    public function __construct(EntityManager $em) 
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(StoreBranch::class);
    }

    public function findAll(): array 
    {
        return $this->repository->findAll();
    }

    public function findById(int $id): ?StoreBranch 
    {
        return $this->repository->find($id);
    }

    public function search(string $keyword, int $maxResults, int $page): StoreBranchSearchResultsDTO 
    {
        $searchQuery = $this->em->createQueryBuilder()
            ->select('branch')
            ->from(StoreBranch::class, 'branch')
            ->where('branch.name LIKE :keyword')
            ->orWhere('branch.address LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->setFirstResult($maxResults * ($page - 1))
            ->setMaxResults($maxResults)
            ->getQuery();
        $results = $searchQuery->getResult();

        // Get the total number of pages
        $totalElemsQuery = $this->em->createQueryBuilder()
            ->select('count(branch.id)')
            ->from(StoreBranch::class, 'branch')
            ->where('branch.name LIKE :keyword')
            ->orWhere('branch.address LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->getQuery();
        $totalElems = $totalElemsQuery->getSingleScalarResult();
        $totalPages = intval(ceil($totalElems / $maxResults));

        return new StoreBranchSearchResultsDTO($results, $totalPages, $page);
    }

    public function save(StoreBranch $branch): void 
    {
        $storeBranchExists = $this->repository->findOneBy(['name' => $branch->getName()]);
        if($storeBranchExists) {
            throw new StoreBranchAlreadyExistsException();
        }
        $this->em->persist($branch);
        $this->em->flush();
    }

    public function update(StoreBranch $branch): void 
    {
        $this->em->flush();
    }

    public function delete(StoreBranch $branch): void 
    {
        $this->em->remove($branch);
        $this->em->flush();
    }
}
