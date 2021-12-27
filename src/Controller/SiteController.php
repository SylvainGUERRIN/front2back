<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route ("/", name="home")
     * @throws \Exception
     */
    public function home(): Response
    {
        $news = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findLatestWithLimit(3);

        return $this->render('site/home.html.twig', [
            'news' => $news,
        ]);
    }
}
