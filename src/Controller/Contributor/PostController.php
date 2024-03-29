<?php

namespace App\Controller\Contributor;

use App\Entity\Post;
use App\Entity\User;
use App\Form\Posts\BadgeType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 *
 * @Route ("/contributor/veilles")
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
     * @Route ("/dashboard", name="contributor_posts_dashboard")
     *
     * @throws \Exception
     */
    public function dashboard(PaginatorInterface $paginator, PostRepository $postRepository): Response
    {
        $contributor = $this->getUser();

        $posts = $paginator->paginate(
            $postRepository->findAllRecentByContributor($contributor),
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            10
        );

        return $this->render('contributor/posts/dashboard.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route ("/create", name="contributor_posts_create")
     */
    public function create(): Response
    {
        $post = new Post();
        $form = $this->createForm(BadgeType::class, $post)->handleRequest($this->request->getCurrentRequest());

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

            return $this->redirectToRoute('contributor_posts_dashboard');
        }

        return $this->render('contributor/posts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/edit/{slug}", name="contributor_posts_edit")
     */
    public function edit(Post $post): Response
    {
        $form = $this->createForm(BadgeType::class, $post)->handleRequest($this->request->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setPostModifiedAt(new \DateTime('now'));
            $post->setValidatedAt(true);
            $this->doctrine->getManager()->flush();

            $this->addFlash(
                'success',
                "L'article <strong>{$post->getTitle()}</strong> a bien été mis à jour !"
            );

            return $this->redirectToRoute('contributor_posts_dashboard');
        }

        return $this->render('contributor/posts/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * @Route ("/delete/{slug}", name="contributor_posts_delete")
     */
    public function delete(Post $post, PostRepository $postRepository): Response
    {
        if ($post->getAuthor()->getId() === $this->getUser()->getId()) {
            $this->doctrine->getManager()->remove($post);
            $this->doctrine->getManager()->flush();

            $this->addFlash(
                'success',
                "L'article <strong>{$post->getTitle()}</strong> a bien été supprimé !"
            );
        } else {
            $this->addFlash(
                'danger',
                "Vous n'êtes pas autorisé à supprimer cet article !"
            );
        }

        return $this->redirectToRoute('contributor_posts_dashboard');
    }
}
