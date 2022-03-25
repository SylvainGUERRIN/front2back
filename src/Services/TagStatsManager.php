<?php

namespace App\Services;

use App\Entity\Stats;
//use App\Repository\PostRepository;
//use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class TagStatsManager
{
//    private PostRepository $postRepository;
//    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
//    private RequestStack $request;

    public function __construct(
        //        RequestStack $request,
        //        PostRepository $postRepository,
        //        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
//        $this->postRepository = $postRepository;
//        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
//        $this->request = $request;
    }

    /**
     * @param $tags
     * @param $option
     * @return void
     */
    public function updateTagsStats($tags, $option): void
    {
        //dump($post);
        //dump($tags);
        foreach ($tags as $tag) {
            if ($tag->getStats() === null) {
                $this->createTagStats($tag);
            } else {
                $this->updateTagStats($tag, $option);
            }
        }

        //dd('terminate');
    }

    /**
     * @param $tag
     * @return void
     */
    public function createTagStats($tag): void
    {
        //dump('create');

        $tagStats = new Stats;
        $tagStats->setFavoriteCounter(0);
        $tagStats->setNumberOfViews(1);

        $this->entityManager->persist($tagStats);
        $this->entityManager->flush();

        $tag->setStats($tagStats);
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    /**
     * @param $tag
     * @param $option
     * @return void
     */
    public function updateTagStats($tag, $option): void
    {
        //dump('update');
        $tagStats = $tag->getStats();
        //dump($tagStats);
        if ($option === "view-count") {
            $tagStats->setNumberOfViews($tagStats->getNumberOfViews() + 1);
        }
        if ($option === "favorite-count-positive") {
            $tagStats->setFavoriteCounter($tagStats->getFavoriteCounter() + 1);
        }
        if ($option === "favorite-count-negative") {
            $tagStats->setFavoriteCounter($tagStats->getFavoriteCounter() - 1);
        }

        $this->entityManager->persist($tagStats);
        $this->entityManager->flush();
    }
}
