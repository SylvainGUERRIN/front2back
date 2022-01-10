<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @throws \Exception
     */
    public function index(PaginatorInterface $paginator, CommentRepository $commentRepository): Response
    {
        $comments = $paginator->paginate(
            $commentRepository->findAllRecent(),
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            10
        );

        return $this->render('admin/comments/dashboard.html.twig', [
            'comments' => $comments,
//            'comments' => $this->doctrine->getRepository(Comment::class)->findAll(),
        ]);
    }

    /**
     * @Route("/ajax/valid-comment", name="valid-comment")
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @return Response
     * @throws \JsonException
     */
    public function validComment(Request $request, CommentRepository $commentRepository): Response
    {
        $response = new Response();
        $action = [ null, "Le commentaire a déjà été validé."];

        if ($request->isXMLHttpRequest()) {
            $id = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $comment = $commentRepository->find($id['id']);

            if ($comment !== null && $comment->getApproval() === false) {
                $comment->setApproval(true);
                $this->doctrine->getManager()->flush();
                $action[0] = $id['id'];
                $action[1] = "Le commentaire vient d'être validé.";
            }
        }

        $response->setContent(json_encode($action, JSON_THROW_ON_ERROR));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');//set all origins but only for test
        return $response;
    }

    /**
     * @Route("/ajax/unvalid-comment", name="unvalid-comment")
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @return Response
     * @throws \JsonException
     */
    public function unvalidComment(Request $request, CommentRepository $commentRepository): Response
    {
        $response = new Response();
        $action = [ null, "Le commentaire a déjà été retiré."];

        if ($request->isXmlHttpRequest()) {
            $id = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $comment = $commentRepository->find($id['id']);

            if ($comment !== null && $comment->getApproval() === true) {
                $comment->setApproval(false);
                $this->doctrine->getManager()->flush();
                $action[0] = $id['id'];
                $action[1] = "Le commentaire vient d'être retiré.";
            }
        }

        $response->setContent(json_encode([$action,], JSON_THROW_ON_ERROR));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');//set all origins but only for test
        return $response;
    }

    /**
     * @Route("/delete/{id}", name="admin_comments_delete")
     */
    public function delete(Comment $comment): Response
    {
        if ($comment->getAuthor() === null) {
            $message = "Le commentaire a été supprimé.";
        } else {
            $message = "Le commentaire de " . $comment->getAuthor()->getFirstname() . " a bien été supprimé.";
        }
        $this->doctrine->getManager()->remove($comment);
        $this->doctrine->getManager()->flush();

        $this->addFlash(
            'success',
            $message
        );

        return $this->redirectToRoute('admin_comments_dashboard');
    }
}
