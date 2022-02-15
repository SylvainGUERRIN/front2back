<?php

namespace App\Services;

use App\Entity\UserStats;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\RequestStack;

class UserStatsManager
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
//    private RequestStack $request;

    public function __construct(
        //        RequestStack $request,
        PostRepository $postRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
//        $this->request = $request;
    }

    public function verifyIfUserHasStats($user): bool
    {
        dump($user);
        //$currentUser = $user->getStats();
//        $currentUser = $this->userRepository->find($user->getId());
        //dd($currentUser);
        return $user->getStats() !== null;
    }

    /**
     * @throws ORMException
     */
    public function createUserStats($user, $post): void
    {
        dump('create');
        dump($post);
        $userStats = new UserStats;
        $userStats->setTagsCounter([$post[0]->getId() => 1]);
        $userStats->setUser($user);
        $this->entityManager->persist($userStats);
        $this->entityManager->flush();
    }

    public function updateUserStats($user, $post): void
    {
        dump('update');
        //lier des tags aux articles avant de continuer
        dump($post[0]->getTag());
    }

    /**
     * @throws ORMException
     */
    public function updateTagsCounter($user, $post): void
    {
        //vérifier si l'utilisateur n'a pas de statistiques rattachées à lui
        $existenceOfUserStats = $this->verifyIfUserHasStats($user);
        dump($existenceOfUserStats);
        if ($existenceOfUserStats === false) {
            $this->createUserStats($user, $post);
        } else {
            $this->updateUserStats($user, $post);
        }

        dd('terminate');
        //return nothing
    }
}
