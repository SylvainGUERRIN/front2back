<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Account\RegistrationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private RequestStack $session;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $session)
    {
        $this->doctrine = $managerRegistry;
        $this->session = $session;
    }

    /**
     * @Route("/login/{!slug}", name="security_login")
     *
     * @param string $slug
     */
    public function login(AuthenticationUtils $authenticationUtils, $slug = 'profile'): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route ("/register", name="security_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordEncoder): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashPass = $userPasswordEncoder->hashPassword($user, $user->getPassword());
            //$hashPass = $userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashPass);
            $user->setRegisteredAt(new \DateTimeImmutable('now'));
            $user->setRoles(['ROLE_USER']);
            $user->setActivate(true);
            $user->setMailAlert(false);

            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $this->session->getSession()->getFlashBag()->add(
                'success',
                'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !'
            );

            return new RedirectResponse('login/profile');
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
