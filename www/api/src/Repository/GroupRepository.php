<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Group;
use App\Exception\GroupNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GroupRepository extends ServiceEntityRepository
{
    public const LIMIT = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function getGroupById(int $id): ?Group
    {
        $user = $this->find($id);
        if (null === $user) {
            throw new GroupNotFoundException();
        }

        return $user;
    }

    public function getGroupByName(string $name): ?Group
    {
        $group = $this->findOneBy(['name' => $name]);
        if (null === $group) {
            throw new GroupNotFoundException();
        }

        return $group;
    }

    public function findAllName(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g.name')
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByName(string $name): ?Group
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()
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
