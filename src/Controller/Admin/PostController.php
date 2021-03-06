<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\Posts\PostType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
    private $request;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $request)
    {
        $this->doctrine = $managerRegistry;
        $this->request = $request;
    }

    /**
     * @Route ("/dashboard", name="admin_posts_dashboard")
     */
    public function dashboard(): Response
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
        $post = new Post();
        $form = $this->createForm(PostType::class, $post)->handleRequest($this->request->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            $this->addFlash(
                'success',
                'Votre article de veille a bien été créé.'
            );

            return $this->redirectToRoute('admin_posts_dashboard');
        }

        return $this->render('admin/posts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/edit", name="admin_posts_edit")
     */
    public function edit(): Response
    {
        return $this->render('admin/posts/edit.html.twig');
    }
}
