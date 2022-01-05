<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\Comments\CreateCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @return Response
     * @Route("/veilles", name="veilles_index")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
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
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(['slug' => $slug]);

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
