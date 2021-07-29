<?php

namespace App\Controller\Admin;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 *
 * @Route ("/admin/veilles")
 */
class PostController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->doctrine = $managerRegistry;
    }

    /**
     * @Route ("/dashboard", name="admin_posts_dashboard")
     */
    public function dashboard(Request $request): Response
    {
        return $this->render('admin/dashboard.html.twig', [
//            'form' => $form->createView(),
//            'avatar' => $avatar,
        ]);
    }

    /**
     * @Route ("/create", name="admin_posts_create")
     */
    public function create(): Response
    {
        return $this->render('admin/posts/create.html.twig');
    }

    /**
     * @Route ("/edit", name="admin_posts_edit")
     */
    public function edit(): Response
    {
        return $this->render('admin/posts/edit.html.twig');
    }
}
