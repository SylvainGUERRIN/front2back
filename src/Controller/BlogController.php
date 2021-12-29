<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     * @Route("/veilles/{slug}", name="veilles_show")
     */
    public function show($slug): Response
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(['slug' => $slug]);


        return $this->render('blog/show.html.twig', [
            'post' => $post[0],
        ]);
    }
}
