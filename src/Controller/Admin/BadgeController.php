<?php

namespace App\Controller\Admin;

use App\Entity\Badge;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 *
 * @Route ("/admin/badges")
 */
class BadgeController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private RequestStack $request;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $request)
    {
        $this->doctrine = $managerRegistry;
        $this->request = $request;
    }

    /**
     * @Route ("/", name="admin_badges_index")
     */
    public function index(PaginatorInterface $paginator): Response
    {

        $badges = $this->doctrine->getRepository(Badge::class)->findAll();


        $paginateBadges = $paginator->paginate(
            $badges,
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            10
        );

        //change function to get contributors
        //$contributors = $this->doctrine->getRepository(User::class)->findAll();

        return $this->render('admin/badges/index.html.twig', [
            'badges' => $paginateBadges,
        ]);
    }

    /**
     * @Route ("/create", name="admin_posts_create")
     */
    public function create(): Response
    {
        $post = new Badge();
        //$form = $this->createForm(PostType::class, $post)->handleRequest($this->request->getCurrentRequest());

        /*if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            /*$user = $this->getUser();

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
        }*/

        return $this->render('admin/badges/create.html.twig', [
//            'form' => $form->createView(),
        ]);
    }

    // penser à limiter les choix de l'administrateur sur le nom de l'action et le delimiteur de l'action
    // faire deux select pour ses champs
    // nom de l'action: limiter à comments, posts, favorite, user (mail alert, activate, contributor), badge
    // delimiteur de l'action: limiter à true, false ou un entier
}
