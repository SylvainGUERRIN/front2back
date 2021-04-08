<?php

namespace App\Tests\Functional\UserAccount;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProfileTest extends WebTestCase
{
    public function testProfileFillWithGoodData(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $crawler = $client->request('GET', $urlGenerator->generate('account_profile'));

        self::assertResponseIsSuccessful();

//        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form[name=edit_profile]')->form([
            'edit_profile[firstname]' => 'ain',
            'edit_profile[email]' => 'ain@email.com',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            '.container > .alert > p',
            'Votre profil a bien été modifié.'
        );
    }
}
