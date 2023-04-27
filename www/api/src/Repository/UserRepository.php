<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public const LIMIT = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserById(int $id): ?User
    {
        $user = $this->find($id);
        if (null === $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function findUsersByGroup(string $name): ?array
    {
        return $this->createQueryBuilder('u')
        ->select('u', 'g')
        ->join('u.groups', 'g')
        ->orderBy('u.id', 'ASC')
        ->andWhere('g.name = :name')
        ->setParameter('name', $name)
        ->getQuery()
        ->getResult()
        ;
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'g')
            ->join('u.groups', 'g')
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(self::LIMIT)
            ->getQuery()
            ->getResult()
        ;
    }

    public function saveAdd(object $user): void
    {
        assert($this->_entityName === $user::class);
        $id = $this->findOneBy([], ['id' => 'DESC'])->getId();
        $user->setId($id + 1);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function removeAndCommit(object $object): void
    {
        assert($this->_entityName === $object::class);
        $this->_em->remove($object);
        $this->_em->flush();
    }

    public function saveAndCommit(object $object): void
    {
        assert($this->_entityName === $object::class);
        $this->_em->persist($object);
        $this->_em->flush();
    }
}
