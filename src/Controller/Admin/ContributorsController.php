<?php

namespace App\Controller\Admin;

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

        //$contributors = $this->doctrine->getRepository(Contributor::class)->findAll();

        return $this->render('admin/contributors/index.html.twig', [
            //'contributors' => $contributors,
        ]);
    }
}
