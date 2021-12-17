<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route ("/", name="admin_users_index")
     * @throws \Exception
     */
    public function index(PaginatorInterface $paginator, UserRepository $userRepository): Response
    {
        $users = $paginator->paginate(
            $userRepository->findAllUsersWithoutAdminRole(),
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            10
        );

        //$users = $this->doctrine->getRepository(User::class)->findAllUsersWithoutAdminRole();

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }
}
