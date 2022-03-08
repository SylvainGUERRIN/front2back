<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    protected ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route ("/", name="home")
     * @throws \Exception
     */
    public function home(): Response
    {
        $news = $this->managerRegistry
            ->getRepository(Post::class)
            ->findLatestWithLimit(3);

        return $this->render('site/home.html.twig', [
            'news' => $news,
        ]);
    }
}
