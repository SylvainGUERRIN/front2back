<?php

namespace App\Repository;

use App\Entity\BadgeUnlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BadgeUnlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method BadgeUnlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method BadgeUnlock[]    findAll()
 * @method BadgeUnlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @template T
 * @extends ServiceEntityRepository<T>
 */
class BadgeUnlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BadgeUnlock::class);
    }

    /*
    public function findOneBySomeField($value): ?Badge
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
