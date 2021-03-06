<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Account\RegistrationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private SessionInterface $session;

    public function __construct(ManagerRegistry $managerRegistry, SessionInterface $session)
    {
        $this->doctrine = $managerRegistry;
        $this->session = $session;
    }

    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route ("/register", name="security_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashPass = $userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashPass);
            $user->setRegisteredAt(new \DateTimeImmutable('now'));
            $user->setRoles(['ROLE_USER']);
            $user->setActivate(true);

            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $this->session->getFlashBag()->add(
                'success',
                'Votre compte a bien ??t?? cr???? ! Vous pouvez maintenant vous connecter !'
            );

            return new RedirectResponse('login');
        }

        return $this->render('site/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
//        throw new \LogicException(
//            'This method can be blank - it will be intercepted by the logout key on your firewall.
//        ');
    }
}
