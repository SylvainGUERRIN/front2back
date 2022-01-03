<?php

namespace App\Controller;

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

        $comments = $post[0]->getComments();

        $form = $this->createForm(CreateCommentType::class);
        $form->handleRequest($request);

        return $this->render('blog/show.html.twig', [
            'post' => $post[0],
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }
}
