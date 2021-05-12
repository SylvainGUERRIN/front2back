<?php

declare(strict_types=1);

namespace App\Tests\Functional\AdminAccount;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class LoginTest.
 */
class LoginAdminTest extends WebTestCase
{
    public function testIfLoginSuccessfully(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_admin_login'));
//        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'ain0@email.com',
            'password' => 'password0',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('admin_dashboard');
    }

    public function testIfEmailDoesNotExist(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_admin_login'));

//        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'fail@email.com',
            'password' => 'password0',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('security_admin_login');
    }

    public function testIfPasswordIsWrong(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_admin_login'));

//        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'ain0@email.com',
            'password' => 'pass',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('security_admin_login');
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

//        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

//        $client->followRedirect();
//        dd($client->followRedirect());
        //self::assertRouteSame('security_admin_login');
    }
}
