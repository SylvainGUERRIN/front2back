<?php


namespace App\Tests\Functional\UserAccount;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase
{
    public function testIfFormIsCorrectlyFill(): void
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/register");

    }
}
