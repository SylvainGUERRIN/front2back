<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginTest
 * @package App\Tests\Functional
 */
class LoginTest extends WebTestCase
{
    public function testIfLoginSuccesfull(): void
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/login");
        $form = $crawler->filter("form[name=login]")->form([
            "email" => "ain@email.com",
            "password" => "password"
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame("index");
    }

    public function testIfEmailDoesNotExist(): void
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/login");
        $form = $crawler->filter("form[name=login]")->form([
            "email" => "fail@email.com",
            "password" => "password"
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame("security_login");
    }

    public function testIfPasswordIsWrong(): void
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/login");
        $form = $crawler->filter("form[name=login]")->form([
            "email" => "ain@email.com",
            "password" => "pass"
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame("security_login");
    }

    public function testIfWrongCsrfToken(): void
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/login");
        $form = $crawler->filter("form[name=login]")->form([
            "email" => "ain@email.com",
            "password" => "password",
            "_csrf_token" => "fail"
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame("security_login");
    }
}
