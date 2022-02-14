<?php

namespace App\Services;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class UserStatsManager
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;
//    private RequestStack $request;

    public function __construct(
        //        RequestStack $request,
        PostRepository $postRepository,
        UserRepository $userRepository
    ) {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
//        $this->request = $request;
    }

    public function verifyIfUserHasStats($user): void
    {
        dump($user);
        //$currentUser = $user->getStats();
//        $currentUser = $this->userRepository->find($user->getId());
        //dd($currentUser);
        if ($user->getStats() !== null) {
            dump($user->getStats());
        } else {
            $this->createUserStats($user);
        }
    }

    public function createUserStats($user): void
    {
        dump('create');
    }

    public function updateTagsCounter($user, $post): void
    {
        //vérifier si l'utilisateur n'a pas de statistiques rattachées à lui
        $this->verifyIfUserHasStats($user);
        dd('terminate');
        //return nothing
    }
}
