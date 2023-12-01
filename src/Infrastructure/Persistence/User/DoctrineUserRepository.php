<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;

class DoctrineUserRepository implements UserRepository
{
    private EntityManager $em;
    private ObjectRepository $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(User::class);
    }


    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findById(int $id): ?User
    {
        return $this->repository->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function search(string $keyword, int $maxResults, int $page): array
    {
        $query = $this->em->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.firstName LIKE :keyword')
            ->orWhere('user.surnames LIKE :keyword')
            ->orWhere('user.email LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->setFirstResult($maxResults * ($page - 1))
            ->setMaxResults($maxResults)
            ->getQuery();
        return $query->getResult();
    }

    public function save(User $user): void
    {
        $userExists = $this->findByEmail($user->getEmail());
        if ($userExists) {
            throw new UserAlreadyExistsException();
        }
        $this->em->persist($user);
        $this->em->flush();
    }

    public function update(User $user): void
    {
        $userExists = $this->findById($user->getId());
        if (!$userExists) {
            throw new UserNotFoundException();
        }
        $this->em->persist($user);
        $this->em->flush();
    }

    public function delete(User $user): void
    {
        $userExists = $this->findById($user->getId());
        if (!$userExists) {
            throw new UserNotFoundException();
        }
        $this->em->remove($user);
        $this->em->flush();
    }
}
