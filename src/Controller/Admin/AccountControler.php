<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Account\EditPasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    /**
     * @Route ("/edit-password", name="account_admin_edit_password")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {

        $form = $this->createForm(EditPasswordType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $user->setPassword($userPasswordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');

            return $this->redirectToRoute('account_admin_profile');
        }

        return $this->render('admin/account/edit-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
