<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 *
 * @Route ("/admin/contributors")
 */
class ContributorsController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private RequestStack $request;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $request)
    {
        $this->doctrine = $managerRegistry;
        $this->request = $request;
    }

    /**
     * @Route ("/", name="admin_contributors_index")
     */
    public function index(): Response
    {
        //pour être contributeur, il faut faire la demande d'adhésion via le formulaire
        //ensuite l'administrateur doit valider la demande

        //change function to get contributors
        $contributors = $this->doctrine->getRepository(User::class)->findAll();

        return $this->render('admin/contributors/index.html.twig', [
            'contributors' => $contributors,
        ]);
    }

    /**
     * @param $id
     * @param User $user
     * @return Response
     * @Route ("/{id}/accept", name="admin_accept_new_contributor")
     */
    public function validateUserContributingRequest($id, User $user): Response
    {
        //change function to get contributors
        $userWhoRequestForContributing = $this->doctrine->getRepository(User::class)->findBy(['id' => $id]);

        //change requesting array to done

        return $this->render('admin/contributors/index.html.twig', [
//            'contributors' => $contributors,
        ]);
    }
}
