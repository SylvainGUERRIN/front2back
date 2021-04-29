<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Account\EditPasswordType;
use App\Form\Account\EditProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->doctrine = $managerRegistry;
    }

    /**
     * @Route ("/profile", name="account_profile")
     */
    public function profile(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $avatar = $user->getAvatar();

        $form = $this->createForm(EditProfileType::class, $user)->handleRequest($request);
        //$form->get('avatar')->get('validatedAt')->setData(false);

        if ($form->isSubmitted() && $form->isValid()) {
//            dd($form->getData()->getAvatar());
            //dump($form->getData()->getAvatar()->getUrl());
            //$user->getAvatar()->setValidatedAt(false);
//            $form->get('avatar')->get('validatedAt')->setData(false);
            //dd($form->getData()->getAvatar());
            //$this->doctrine->getManager()->persist($user);
            $this->doctrine->getManager()->flush();

            $this->addFlash(
                'success',
                'Votre profil a bien été modifié.'
            );

            return $this->redirectToRoute('account_profile');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'avatar' => $avatar,
        ]);
    }

    /**
     * @Route ("/edit-password", name="account_edit_password")
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

            return $this->redirectToRoute('account_profile');
        }

        return $this->render('user/edit-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
