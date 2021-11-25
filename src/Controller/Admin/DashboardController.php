<?php

namespace App\Controller\Admin;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController.
 *
 * @Route ("/admin")
 */
class DashboardController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->doctrine = $managerRegistry;
    }

    /**
     * @Route ("/dashboard", name="admin_dashboard")
     */
    public function dashboard(Request $request): Response
    {

        return $this->render('admin/dashboard.html.twig', [
//            'form' => $form->createView(),
//            'avatar' => $avatar,
        ]);
    }
}
