<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @template T
 * @extends ServiceEntityRepository<T>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @method Post[]
     *
     * @throws \Exception
     */
    public function findAllRecent(): Query
    {
        return $this->createQueryBuilder('p')
            ->where('p.post_created_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->orderBy('p.post_created_at', 'DESC')
            ->getQuery();
    }

    /**
     * @method Post[]
     *
     * @throws \Exception
     */
    public function findLatestWithLimit($limit)
    {
        return $this->createQueryBuilder('p')
            ->where('p.post_created_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->orderBy('p.post_created_at', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @throws \Exception
     */
    public function findAllRecentByContributor($contributor): Query
    {
        return $this->createQueryBuilder('p')
            ->where('p.post_created_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->andWhere('p.author = :user')
            ->setParameter('user', $contributor)
            ->orderBy('p.post_created_at', 'DESC')
            ->getQuery();
    }

    /**
     * @throws \Exception
     */
    public function findAllPostsFromContributor($contributor)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.author = :user')
            ->setParameter('user', $contributor)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $post
     * @return float|int|mixed|string
     */
    public function getAuthorFromPost($post)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
//            ->select('p.author')
            //->from(Post::class, 'p')
            ->where('p.id = :id')
            ->setParameter('id', $post)
            ->getQuery()
            ->getResult();
//            ->getSingleScalarResult();
    }
}
