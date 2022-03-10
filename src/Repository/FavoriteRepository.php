<?php

namespace App\Repository;

use App\Entity\Favorite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Favorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favorite[]    findAll()
 * @method Favorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @template T
 * @extends ServiceEntityRepository<T>
 */
class FavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorite::class);
    }

    /**
     * @param $userId
     * @param $postId
     * @return float|int|mixed|string
     * @throws NonUniqueResultException
     */
    public function findFavoriteWithUserIdAndPostId($userId, $postId)
    {
        return $this->createQueryBuilder('f')
            ->where('f.user_id = :userID')
            ->setParameter('userID', $userId)
            ->andWhere('f.post_id = :postID')
            ->setParameter('postID', $postId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
