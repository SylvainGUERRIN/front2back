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

        $form = $crawler->filter('form[name=registration]')->form([
            'registration[firstname]' => 'ain',
            'registration[email]' => 'ain@email.com',
            'registration[password][first]' => 'password',
            'registration[password][second]' => 'password',
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

        $form = $crawler->filter('form[name=registration]')->form([
            'registration[firstname]' => '',
            'registration[email]' => 'ain0@email.com',
            'registration[password][first]' => 'password0',
            'registration[password][second]' => 'password0',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'form[name=registration] > div > ul > li',
            'Cette valeur ne doit pas être vide.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenFirstnameFieldValueIsLessThanThree(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=registration]')->form([
            'registration[firstname]' => 'ai',
            'registration[email]' => 'ain0@email.com',
            'registration[password][first]' => 'password0',
            'registration[password][second]' => 'password0',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'form[name=registration] > div > ul > li',
            'Cette chaîne est trop courte. Elle doit avoir au minimum 3 caractères.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenEmailFieldIsBlank(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=registration]')->form([
            'registration[firstname]' => 'ain0',
            'registration[email]' => '',
            'registration[password][first]' => 'password0',
            'registration[password][second]' => 'password0',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
//        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains(
            'form[name=registration] > div > ul > li',
            'Cette valeur ne doit pas être vide.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenEmailIsNotValid(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=registration]')->form([
            'registration[firstname]' => 'ain0',
            'registration[email]' => 'ain0.com',
            'registration[password][first]' => 'password0',
            'registration[password][second]' => 'password0',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'form[name=registration] > div > ul > li',
            'Cet email n\'est pas valide.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenTwoPasswordFieldsAreNotIdentically(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=registration]')->form([
            'registration[firstname]' => 'ain6',
            'registration[email]' => 'ain6@email.com',
            'registration[password][first]' => 'password0',
            'registration[password][second]' => 'pass',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
//
//        $client->followRedirect();
//
//        self::assertRouteSame('security_register');

        self::assertSelectorTextContains(
            'form[name=registration] > div > ul > li',
            'Les mots de passe ne correspondent pas.'
        );
    }

    public function testIfStayOnRegisterPageWithErrorMessageWhenTwoPasswordFieldsAreLessThanEight(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security_register'));

        $form = $crawler->filter('form[name=registration]')->form([
            'registration[firstname]' => 'ain6',
            'registration[email]' => 'ain6@email.com',
            'registration[password][first]' => 'passwor',
            'registration[password][second]' => 'passwor',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'form[name=registration] > div > ul > li',
            'Cette chaîne est trop courte. Elle doit avoir au minimum 8 caractères.'
        );
    }
}
