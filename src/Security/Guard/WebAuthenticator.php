<?php

declare(strict_types=1);

namespace App\Security\Guard;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class WebAuthenticator extends AbstractLoginFormAuthenticator
//class WebAuthenticator extends AbstractLoginFormAuthenticator implements PasswordAuthenticatedUserInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'security_login';

//    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;
//    private CsrfTokenManagerInterface $csrfTokenManager;
//    private UserPasswordHasherInterface $passwordEncoder;
//    private UserRepository $userRepository;

    public function __construct(
//        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
//        CsrfTokenManagerInterface $csrfTokenManager,
//        UserPasswordHasherInterface $passwordEncoder,
//        UserRepository $userRepository
    ) {
//        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
//        $this->csrfTokenManager = $csrfTokenManager;
//        $this->passwordEncoder = $passwordEncoder;
//        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): bool
    {
        //dd($request->headers->has('X-AUTH-TOKEN'));
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
//        return $request->query->has('_route')
            //&& $request->headers->has('X-AUTH-TOKEN')
            && $request->isMethod('POST');
    }


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function authenticate(Request $request): Passport
    {
        $password = $request->request->get('password');
        $username = $request->request->get('email');
        $csrfToken = $request->request->get('csrf_token');

        //dd($csrfToken);
        // ... validate no parameter is empty
        $email = $request->request->get('email', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password),
            [new CsrfTokenBadge('authenticate', $csrfToken)]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
//        dump($request->getSession());
//        dump($firewallName);
//        dump($this->getTargetPath($request->getSession(), $firewallName));
//        dd('terminate');
//        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
//            return new RedirectResponse($targetPath);
//        }
//        return null;
        $pathKey = explode('/', $request->getPathInfo());
        //dd($request->getPathInfo());
        if ('home' === $pathKey[2]) {
            return new RedirectResponse($this->urlGenerator->generate('home'));
        }

        return new RedirectResponse($this->urlGenerator->generate('account_profile'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

//    public function getPassword(): ?string
//    {
//        // TODO: Implement getPassword() method.
//    }
}
