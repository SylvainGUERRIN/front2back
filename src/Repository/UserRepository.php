<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @template T
 * @extends ServiceEntityRepository<T>
 */
class UserRepository extends ServiceEntityRepository
{
    use UniqueEmailTrait;

    private ManagerRegistry $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->em = $registry;
    }

//    /**
//     * Used to upgrade (rehash) the user's password automatically over time.
//     */
//    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
//    {
//        if (!$user instanceof User) {
//            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
//        }
//
//        $user->setPassword($newEncodedPassword);
//        $this->em->getManager()->persist($user);
//        $this->em->getManager()->flush();
//    }

    /**
     * @return array
     */
    public function findUserWithRequests(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.requests IS NOT NULL')
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//    * @return User[] Returns an array of User objects
//    */
//    public function findUserByRequestContributingOnRequesting($request): array
//    {
//        $rsm = $this->createResultSetMappingBuilder('u');
//        $rsm->addRootEntityFromClassMetadata(User::class, 'u');
//        $rawQuery = sprintf(
//            'SELECT %s FROM User u WHERE JSON_EXTRACT(current_state, \'$.processing\')',
//            $rsm->generateSelectClause()
//        );
//        $query = $this->_em->createNativeQuery($rawQuery, $rsm);
//        return $query->getResult();
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.requests = :val')
//            ->setParameter('val', 'contributor')
//            ->getQuery()
//            ->getResult()
//        ;

        // The ResultSetMapping maps the SQL result to entities
        //$rsm = $this->createResultSetMappingBuilder('u');

//        $rawQuery = sprintf(
//            'SELECT %s
//        FROM user u
//        WHERE JSON_CONTAINS(u.requests, :request, "$")',
//            $rsm->generateSelectClause()
//        );
//        $rawQuery = 'SELECT '. $rsm->generateSelectClause() .' FROM User u WHERE JSON_OVERLAPS(u.requests, :request, "$")';
////        $rawQuery = 'SELECT '. $rsm->generateSelectClause() .' FROM User u WHERE JSON_CONTAINS(u.requests, :request, "$")';
////        $rawQuery = 'SELECT '. $rsm->generateSelectClause() .' FROM User u WHERE JSON_SEARCH(u.requests, "all", :request)';
//
//        $query = $this->getEntityManager()->createNativeQuery($rawQuery, $rsm);
//
////        $query->setParameter('request', $request);
//        $query->setParameter('request', sprintf('"%s"', $request));
//        dump($query);
//        dump($query->getSQL());
//        dump($query->getResult());
//        die();
////        $query->setParameter('request', $request);
//        return $query->getResult();
//    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
