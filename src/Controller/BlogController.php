<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\Comments\CreateCommentType;
use App\Repository\FavoriteRepository;
use App\Repository\PostRepository;
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

class BlogController extends AbstractController
{
    private PostRepository $postRepository;
    private RequestStack $request;
    private TagStatsManager $tagStatsManager;
    private UserStatsManager $userStatsManager;
    private ManagerRegistry $doctrine;

    public function __construct(
        RequestStack $request,
        PostRepository $postRepository,
        TagStatsManager $tagStatsManager,
        UserStatsManager $userStatsManager,
        ManagerRegistry $managerRegistry
    ) {
        $this->postRepository = $postRepository;
        $this->request = $request;
        $this->tagStatsManager = $tagStatsManager;
        $this->userStatsManager = $userStatsManager;
        $this->doctrine = $managerRegistry;
    }

    /**
     * @param PaginatorInterface $paginator
     * @return Response
     * @throws \Exception
     * @Route("/veilles", name="veilles_index")
     */
    public function index(PaginatorInterface $paginator): Response
    {
        $elementPerPage = 6;
        $posts = $paginator->paginate(
            $this->postRepository->findAllRecent(),
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            $elementPerPage
        );
        $pageCount = ceil($posts->getTotalItemCount() / $elementPerPage);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'pageCount' => $pageCount
        ]);
    }

    /**
     * @param $slug
     * @param Request $request
     * @param FavoriteRepository $favoriteRepository
     * @return Response
     * @throws ORMException
     * @Route("/veilles/{slug}", name="veilles_show")
     */
    public function show(
        $slug,
        Request $request,
        FavoriteRepository $favoriteRepository
    ): Response {
        $entityManager = $this->doctrine->getManager();

        //update post view count
        $post = $this->postRepository->findBy(['slug' => $slug]);
        $modifyPost = $post[0];
        $modifyPost->setViewsCount($modifyPost->getViewsCount() + 1);
        $entityManager->flush();

        //update tag view count
        //vérifier si le post a un ou plusieurs tags
        //si c'est le cas lancer le tagStatsManager
        $postTags = $post[0]->getTag()->toArray();
        if (!empty($postTags)) {
            $this->tagStatsManager->updateTagsStats($postTags, "view-count");
        }

        $favorite = false;
        //update user stats
        if ($this->getUser() !== null) {
            $this->userStatsManager->updateTagsAndPostCounter($this->getUser(), $post);
            if ($favoriteRepository->
                findFavoriteWithUserIdAndPostId($this->getUser()->getId(), $post[0]->getId()) !== null
            ) {
                $favorite = true;
            }
        }

        $comments = $post[0]->getComments()->toArray();

        //dd($comments);

        $form = $this->createForm(CreateCommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$commentFromForm = $form->getData();
            $comment = new Comment();
            //dd($comment);
            if ($this->getUser()) {
                $comment->setAuthor($this->getUser());
                $comment->setEmail($this->getUser()->getEmail());
            } else {
                $comment->setAuthor(null);
                $comment->setEmail($form->get('email')->getData());
            }
            $comment->setPost($post[0]);
            $comment->setCommentedAt(new \DateTime());
            $comment->setApproval(false);
            $comment->setContent($form->get('content')->getData());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a bien été pris en compte !"
            );

            return $this->redirectToRoute('veilles_show', ['slug' => $slug]);
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post[0],
            'comments' => $comments,
            'form' => $form->createView(),
            'favorite' => $favorite,
        ]);
    }
}
