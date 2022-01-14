<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\Comments\CreateCommentType;
use App\Repository\PostRepository;
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

    public function __construct(RequestStack $request, PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->request = $request;
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
     * @return Response
     * @Route("/veilles/{slug}", name="veilles_show")
     */
    public function show($slug, Request $request): Response
    {
        $post = $this->postRepository->findBy(['slug' => $slug]);

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

            $entityManager = $this->getDoctrine()->getManager();
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
        ]);
    }
}
