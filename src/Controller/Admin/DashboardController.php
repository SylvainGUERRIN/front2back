<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController.
 *
 * @Route ("/admin")
 */
class DashboardController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->doctrine = $managerRegistry;
    }

    /**
     * @Route ("/dashboard", name="admin_dashboard")
     * @throws \Exception
     */
    public function dashboard(
        Request $request,
        PostRepository $postRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository
    ): Response {
        $veilles = $postRepository->findAll();
        $countUnvalidatedVeilles = 0;
        $topRated = [];
        //dump($veilles);
        foreach ($veilles as $veille) {
            if ($veille->isValidatedAt() === false) {
                $countUnvalidatedVeilles++;
            }
            if ($veille->getViewsCount() > 0) {
                $topRated[$veille->getTitle()] = $veille->getViewsCount();
            }
        }
        arsort($topRated, SORT_NUMERIC);
        $countUnaprovedComments = count($commentRepository->findAllUnapproved());
        $users = $userRepository->findAll();
        $unvalidatedUsers = 0;
        $countContributors = 0;
        $countUnvalidatedContributors = 0;
        foreach ($users as $user) {
            if ($user->isActivate() === false) {
                $unvalidatedUsers++;
            }
            if ($user->getRoles()[0] === 'ROLE_CONTRIBUTOR') {
                $countContributors++;
            }
            //dump(isset($user->getRequests()[0]));
//            if ($user->getRequests() !== null) {
            if ($user->getRequests() !== null && isset($user->getRequests()["contributor"])) {
                if ($user->getRequests()["contributor"] === "requesting") {
                    $countUnvalidatedContributors++;
                }
                //dump($user->getRequests()["contributor"]);
            }
        }

        return $this->render('admin/dashboard.html.twig', [
            'countVeilles' => count($veilles),
            'countUnvalidatedVeilles' => $countUnvalidatedVeilles,
            'countUnaprovedComments' => $countUnaprovedComments,
            'topRated' => $topRated,
            'countUsers' => count($users),
            'countUnvalidatedUsers' => $unvalidatedUsers,
            'countContributors' => $countContributors,
            'countUnvalidatedContributors' => $countUnvalidatedContributors,
        ]);
    }
}
