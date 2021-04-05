<?php

namespace App\Tests\Functional\UserAccount;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegisterTest extends WebTestCase
{
    public function testIfRedirectOnLoginPageAfterSubmitWithGoodCredentialsOnRegisterForm(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

//        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form[name=account]')->form([
            'account[firstname]' => 'ain',
            'account[email]' => 'ain@email.com',
            'account[password][first]' => 'password',
            'account[password][second]' => 'password',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('security_login');
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenFirstnameFieldIsBlank(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=account]')->form([
            'account[firstname]' => '',
            'account[email]' => 'ain@email.com',
            'account[password][first]' => 'password',
            'account[password][second]' => 'password',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'form[name=account] > div > ul > li',
            'Cette valeur ne doit pas être vide.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenFirstnameFieldValueIsLessThanThree(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=account]')->form([
            'account[firstname]' => 'ai',
            'account[email]' => 'ain@email.com',
            'account[password][first]' => 'password',
            'account[password][second]' => 'password',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'form[name=account] > div > ul > li',
            'Cette chaîne est trop courte. Elle doit avoir au minimum 3 caractères.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenEmailFieldIsBlank(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=account]')->form([
            'account[firstname]' => 'ain',
            'account[email]' => '',
            'account[password][first]' => 'password',
            'account[password][second]' => 'password',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
//        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains(
            'form[name=account] > div > ul > li',
            'Cette valeur ne doit pas être vide.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenEmailIsNotValid(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=account]')->form([
            'account[firstname]' => 'ain',
            'account[email]' => 'ain.com',
            'account[password][first]' => 'password',
            'account[password][second]' => 'password',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'form[name=account] > div > ul > li',
            'Cet email n\'est pas valide.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenTwoPasswordFieldsAreNotIdentically(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=account]')->form([
            'account[firstname]' => 'ain',
            'account[email]' => 'ain@email.com',
            'account[password][first]' => 'password',
            'account[password][second]' => 'pass',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
//
//        $client->followRedirect();
//
//        self::assertRouteSame('security_register');

        self::assertSelectorTextContains(
            'form[name=account] > div > ul > li',
            'Les mots de passe ne correspondent pas.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenTwoPasswordFieldsAreLessThanEight(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=account]')->form([
            'account[firstname]' => 'ain',
            'account[email]' => 'ain@email.com',
            'account[password][first]' => 'passwor',
            'account[password][second]' => 'passwor',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'form[name=account] > div > ul > li',
            'Cette chaîne est trop courte. Elle doit avoir au minimum 8 caractères.'
        );
    }
}
