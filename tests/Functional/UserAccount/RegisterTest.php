<?php

namespace App\Tests\Functional\UserAccount;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterTest extends WebTestCase
{
    public function testIfRedirectOnLoginPageAfterSubmitWithGoodCredentialsOnRegisterForm(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

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
}
