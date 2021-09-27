<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Form\Posts\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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
    private RequestStack $request;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $request)
    {
        $this->doctrine = $managerRegistry;
        $this->request = $request;
    }

    /**
     * @Route ("/dashboard", name="admin_posts_dashboard")
     * @throws \Exception
     */
    public function dashboard(PaginatorInterface $paginator, PostRepository $postRepository): Response
    {
        $posts = $paginator->paginate(
            $postRepository->findAllRecent(),
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            10
        );

        return $this->render('admin/posts/dashboard.html.twig', [
            'posts' => $posts,
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
            /** @var User $user */
            $user = $this->getUser();

            $post->setPostCreatedAt(new \DateTime('now'));
            $post->setAuthor($user);
            $post->setValidatedAt(true);
            $this->doctrine->getManager()->persist($post);
            $this->doctrine->getManager()->flush();

            $this->addFlash(
                'success',
                "L'article <strong>{$post->getTitle()}</strong> a bien été crée !"
            );

            return $this->redirectToRoute('admin_posts_dashboard');
        }

        return $this->render('admin/posts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/edit/{slug}", name="admin_posts_edit")
     */
    public function edit(Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post)->handleRequest($this->request->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            //$user = $this->getUser();

            //$post->setPostCreatedAt(new \DateTime('now'));
            $post->setPostModifiedAt(new \DateTime('now'));
            //$post->setAuthor($user);
            $post->setValidatedAt(true);
            $this->doctrine->getManager()->flush();

            $this->addFlash(
                'success',
                "L'article <strong>{$post->getTitle()}</strong> a bien été mis à jour !"
            );

            return $this->redirectToRoute('admin_posts_dashboard');
        }

        return $this->render('admin/posts/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * @Route ("/delete/{slug}", name="admin_posts_delete")
     */
    public function delete(Post $post): Response
    {
        $this->doctrine->getManager()->remove($post);
        $this->doctrine->getManager()->flush();

        $this->addFlash(
            'success',
            "La veille <strong>{$post->getTitle()}</strong> a  bien été supprimée !"
        );
        return $this->redirectToRoute('admin_posts_dashboard');
    }
}
