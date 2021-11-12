<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 *
 * @Route ("/admin/comments")
 */
class CommentController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private RequestStack $request;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $request)
    {
        $this->doctrine = $managerRegistry;
        $this->request = $request;
    }

    /**
     * @Route("/dashboard", name="admin_comments_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/comments/dashboard.html.twig', [
            'comments' => $this->doctrine->getRepository(Comment::class)->findAll(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_comments_delete")
     */
    public function delete(Comment $comment): Response
    {
        $this->doctrine->getManager()->remove($comment);
        $this->doctrine->getManager()->flush();

        $this->addFlash(
            'success',
            "Le commentaire de <strong>{$comment->getAuthor()->getFirstname()}</strong> a  bien été supprimé !"
        );

        return $this->redirectToRoute('admin_comments_dashboard');
    }
}
