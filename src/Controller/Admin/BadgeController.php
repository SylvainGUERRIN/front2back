<?php

namespace App\Controller\Admin;

use App\Entity\Badge;
use App\Entity\User;
use App\Form\Badges\BadgeType;
use App\Form\Posts\PostType;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route ("/create", name="admin_badges_create")
     */
    public function create(): Response
    {
        $badge = new Badge();
        $form = $this->createForm(BadgeType::class, $badge)->handleRequest($this->request->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            //dump($form->getData());
            //die();
            $badge->setBadgeCreatedAt(new \DateTime('now'));
            $this->doctrine->getManager()->persist($badge);
            $this->doctrine->getManager()->flush();

            $this->addFlash(
                'success',
                "Le badge <strong>{$badge->getName()}</strong> a bien été crée !"
            );

            return $this->redirectToRoute('admin_badges_index');
        }

        return $this->render('admin/badges/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Badge $badge
     * @return Response
     * @Route("/edit/{id}", name="admin_badges_edit")
     */
    public function edit(Badge $badge) : Response
    {
        $form = $this->createForm(BadgeType::class, $badge)->handleRequest($this->request->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var User $user */
            //$user = $this->getUser();

            //$post->setPostCreatedAt(new \DateTime('now'));
            $badge->setBadgeModifiedAt(new \DateTime('now'));
            //$post->setAuthor($user);
            //$badge->setValidatedAt(true);
            $this->doctrine->getManager()->flush();

            $this->addFlash(
                'success',
                "Le badge <strong>{$badge->getName()}</strong> a bien été mis à jour !"
            );

            return $this->redirectToRoute('admin_badges_index');
        }
        return $this->render('admin/badges/edit.html.twig', [
            'form' => $form->createView(),
            'badge' => $badge,
        ]);
    }

    /**
     * @param Badge $badge
     * @return RedirectResponse
     * @Route("/delete/{id}", name="admin_badges_delete")
     */
    public function delete(Badge $badge): RedirectResponse
    {
        $this->doctrine->getManager()->remove($badge);
        $this->doctrine->getManager()->flush();

        $this->addFlash(
            'success',
            "Le badge <strong>{$badge->getName()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_badges_index');
    }

    // penser à limiter les choix de l'administrateur sur le nom de l'action et le delimiteur de l'action
    // faire deux select pour ses champs
    // nom de l'action: limiter à comments, posts, favorite, user (mail alert, activate, contributor), badge
    // delimiteur de l'action: limiter à true, false ou un entier
}
