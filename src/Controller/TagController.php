<?php

namespace App\Controller;

use App\Repository\FavoriteRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Services\TagStatsManager;
use App\Services\UserStatsManager;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    private PostRepository $postRepository;
    private TagRepository $tagRepository;
    private RequestStack $request;
    private TagStatsManager $tagStatsManager;
    private UserStatsManager $userStatsManager;
    private ManagerRegistry $doctrine;

    public function __construct(
        RequestStack $request,
        PostRepository $postRepository,
        TagRepository $tagRepository,
        TagStatsManager $tagStatsManager,
        UserStatsManager $userStatsManager,
        ManagerRegistry $managerRegistry
    ) {
//        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->request = $request;
//        $this->tagStatsManager = $tagStatsManager;
//        $this->userStatsManager = $userStatsManager;
//        $this->doctrine = $managerRegistry;
    }

    /**
     * @param $slug
     * @param Request $request
//     * @param FavoriteRepository $favoriteRepository
     * @return Response
     * @Route("/tags/{slug}", name="tag_show")
     * @throws \Exception
     */
    public function show(
        $slug,
        Request $request,
        PaginatorInterface $paginator
//        FavoriteRepository $favoriteRepository
    ): Response {
        //do favorite logic

        //get tag
        $tag = $this->tagRepository->findBySlug($slug);

        //get posts from tag with pagination
        //$posts = $tag->getPosts();
        $elementPerPage = 6;
        $posts = $paginator->paginate(
            $tag->getPosts(),
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            $elementPerPage
        );
        $pageCount = ceil($posts->getTotalItemCount() / $elementPerPage);

        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
            'posts' => $posts,
            'pageCount' => $pageCount
        ]);
    }
}
