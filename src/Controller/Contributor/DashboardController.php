<?php

namespace App\Controller\Contributor;

use App\Repository\PostRepository;
use ArrayObject;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController.
 *
 * @Route ("/contributor")
 */
class DashboardController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->doctrine = $managerRegistry;
    }

    /**
     * @Route ("/dashboard", name="contributor_dashboard")
     * @throws \Exception
     */
    public function dashboard(Request $request, PostRepository $postRepository): Response
    {
        $veilles = $postRepository->findAllPostsFromContributor($this->getUser());
        $countComments = 0;
        $topRated = [];
        //dump($veilles);
        foreach ($veilles as $veille) {
            $commentsFromVeille = $veille->getComments()->toArray();
            if ($commentsFromVeille !== null) {
                $countComments += count($commentsFromVeille);
            }
            if ($veille->getViewsCount() > 0) {
                $topRated[$veille->getTitle()] = $veille->getViewsCount();
            }
            //dump($veille->getComments()->toArray());
        }
//        dump($topRated);
//        $topRatedArrayObject = new ArrayObject($topRated);
//        dump($topRatedArrayObject->asort(SORT_DESC));
//        dump($topRatedArrayObject);
        arsort($topRated, SORT_NUMERIC);
//        $topRatedSort = $topRated;
//        dump(sort($topRatedSort));
//        dump($topRated);

        return $this->render('contributor/dashboard.html.twig', [
            'countVeilles' => count($veilles),
            'countComments' => $countComments,
            'topRated' => $topRated,
        ]);
    }
}
