<?php

declare(strict_types=1);

namespace App\Tests\Functional\UserAccount;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
//use PHPUnit\Framework\MockObject\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProfileTest extends WebTestCase
{
    public function testProfileFillWithGoodData(): void
    {
        // trait with methods for testing
        //$trait = WebTestAssertionsTrait::;

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
            'edit_profile[firstname]' => 'ain0',
            'edit_profile[email]' => 'ain0@email.com',
        ]);

        $client->submit($form);

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            '.container > .alert > p',
            'Votre profil a bien été modifié.'
        );
    }

    /**
     * @dataProvider providerForBadDatasInEditProfileForm
     */
    public function testProfileFillWithEmptyFirstname(array $formData, array $errorMessage): void
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
        //echo $formData[0];

        $form = $crawler->filter('form[name=edit_profile]')->form($formData);

        $client->submit($form);

//        $client->submit($crawler->filter('form[name=edit_profile]')->form($formData));

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

//        self::assertSelectorTextContains(
//            '.container > .alert > p',
//            'Votre profil a bien été modifié.'
//        );
        self::assertSelectorTextContains(
            'ul > li',
            $errorMessage[0]
        );

        //echo $errorMessage[1];

//        self::assertSelectorTextContains(
//            'ul > li',
//            $errorMessage[1]
//        );
    }

    public function providerForBadDatasInEditProfileForm(): Generator
    {
        yield [
            [
                'edit_profile[firstname]' => 'ain',
                'edit_profile[email]' => 'ain2@email.com',
            ],
            [
                'Cette valeur est déjà utilisée.',
            ],
        ];

        yield [
            [
                'edit_profile[firstname]' => '',
                'edit_profile[email]' => 'edit@email.com',
            ],
            [
                'Cette valeur ne doit pas être vide.',
                'Cette chaîne est trop courte. Elle doit avoir au minimum 3 caractères.',
            ],
        ];

        yield [
            [
                'edit_profile[firstname]' => 'Ain',
                'edit_profile[email]' => '',
            ],
            [
                'Cette valeur ne doit pas être vide.',
                'Cette chaîne est trop courte. Elle doit avoir au minimum 3 caractères.',
            ],
        ];
    }
}
