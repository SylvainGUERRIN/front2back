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
        //dump($user);
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
//        dump($post);
//        dump($post[0]->getTag()->toArray()[0]->getId());
        $userStats = new UserStats;
        $userStats->setPostsCounter([$post[0]->getId() => 1]);
        $userStats->setTagsCounter([$post[0]->getTag()->toArray()[0]->getId() => 1]);
        $userStats->setUser($user);
        $this->entityManager->persist($userStats);
        $this->entityManager->flush();
    }

    public function updateUserStats($user, $post): void
    {
        dump('update');
        dump($post[0]);

        //get user stats
        $userStats = $user->getStats();
        //dump($userStats);

        //update post view count
        dump($userStats->getPostsCounter());
        $userStatsOnPosts = $userStats->getPostsCounter();

        //dump(array_key_exists($post[0]->getId(), $userStatsOnPosts));
        //dump($post[0]->getId());
        dump($userStatsOnPosts);

        if (array_key_exists($post[0]->getId(), $userStatsOnPosts) === true) {
            dump($post[0]->getId());
            //update value on array
            //$userStatsOnPosts[] = [$post[0]->getId() => 1];
            $userStatsOnPosts[$post[0]->getId()]++;
        } else {
            $userStatsOnPosts[$post[0]->getId()] = 1;
        }

//        foreach ($userStatsOnPosts as $postStatsKey => $postStats) {
//            dump($post[0]->getId());
//            dump($postStatsKey);
//            if ($postStatsKey === $post[0]->getId()) {
//                dump($post[0]->getId());
//            }
////            }else{
////                $userStatsOnPosts[] = [$post[0]->getId() => 1];
////            }
//        }

        //update tag view count
//        dump($userStats->getTagsCounter());
//        dump($post[0]->getTag()->toArray());

        //update final
        dump($userStatsOnPosts);
        $userStats->setPostsCounter($userStatsOnPosts);
        //$userStats->setTagsCounter();
        $this->entityManager->flush();
    }

    /**
     * @throws ORMException
     */
    public function updateTagsCounter($user, $post): void
    {
        //vérifier si l'utilisateur n'a pas de statistiques rattachées à lui
        $existenceOfUserStats = $this->verifyIfUserHasStats($user);
        //dump($existenceOfUserStats);
        if ($existenceOfUserStats === false) {
            $this->createUserStats($user, $post);
        } else {
            $this->updateUserStats($user, $post);
        }

        dd('terminate');
        //return nothing
    }
}
