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
 * @Route ("/admin/users")
 */
class UsersController extends AbstractController
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
        //gestion des bio des utilisateurs

        $users = $this->doctrine->getRepository(User::class)->findAll();

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }
}
