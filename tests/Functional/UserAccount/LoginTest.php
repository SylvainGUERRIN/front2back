<?php

declare(strict_types=1);

namespace App\Tests\Functional\UserAccount;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class LoginTest.
 */
class LoginTest extends WebTestCase
{
    public function testIfLoginSuccessfully(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_login'));
//        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'ain@email.com',
            'password' => 'password',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('index');
    }

    public function testIfEmailDoesNotExist(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_login'));

//        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'fail@email.com',
            'password' => 'password',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('security_login');
    }

    public function testIfPasswordIsWrong(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_login'));

//        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'ain@email.com',
            'password' => 'pass',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('security_login');
    }

    public function testIfWrongCsrfToken(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_login'));

//        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'ain@email.com',
            'password' => 'password',
            '_csrf_token' => 'fail',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('security_login');
    }
}
