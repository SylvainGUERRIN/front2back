<?php

namespace App\Controller\Admin;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountControler.
 *
 * @Route ("/admin")
 */
class AccountControler extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->doctrine = $managerRegistry;
    }

    /**
     * @Route ("/profile", name="account_admin_profile")
     */
    public function profile(Request $request): Response
    {
        return $this->render('admin/account/profile.html.twig', [
//            'form' => $form->createView(),
//            'avatar' => $avatar,
        ]);
    }

    //add function to manage password edition
}
