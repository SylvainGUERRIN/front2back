<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Account\EditPasswordType;
use App\Form\Account\EditProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\JsonResponse;
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
//        dump($this->createForm(EditProfileType::class, $user));
//        die();

        if ($form->isSubmitted() && $form->isValid()) {
            //manage contributor request
            $userRequests = $user->getRequests();
            if ($form->get('requests')->getData() === true) {
                if ($userRequests === null || !array_key_exists('contributor', $userRequests)) {
                    $user->setRequests(['contributor' => 'requesting']);
                } else {
                    $userRequests['contributor'] = 'requesting';
                }
            } else {
                if (array_key_exists('contributor', $userRequests)) {
                    $userRequests['contributor'] = 'denied';
                }
            }
            $user->setRequests($userRequests);

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
            'user' => $user,
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

    /**
     * @Route ("/ajax/check/contributing", name="account_ajax_check_contributing")
     * @throws \JsonException
     */
    public function checkIfUserIsAlreadyRequestingContributing(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userRequests = $user->getRequests();
        $requestingContributing = false;
        $response = new Response();

        //change condition to check if user is already requesting contributor
        if (($userRequests !== null && array_key_exists('contributor', $userRequests))
            || $userRequests['contributor'] === 'requesting' || $userRequests['contributor'] === 'done') {
            //do a another response
            $requestingContributing = true;
        }

        $response->setContent(json_encode([$requestingContributing], JSON_THROW_ON_ERROR));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');//set all origins but only for test
        return $response;
    }
}
