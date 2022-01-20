<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @template T
 * @extends ServiceEntityRepository<T>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @method Comment[]
     *
     * @throws \Exception
     */
    public function findAllRecent(): Query
    {
        return $this->createQueryBuilder('c')
            ->where('c.commented_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->orderBy('c.commented_at', 'DESC')
            ->getQuery();
    }

    /**
     * @method Comment[]
     *
     * @throws \Exception
     */
    public function findAllUnapproved()
    {
        return $this->createQueryBuilder('c')
            ->where('c.approval = :approved')
            ->setParameter('approved', false)
            ->orderBy('c.commented_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
