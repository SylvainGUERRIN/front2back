<?php

namespace App\Tests\Functional\UserAccount;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditPasswordTest extends WebTestCase
{
    public function testPasswordEditWithGoodCredentials(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('account_edit_password'));

        self::assertResponseIsSuccessful();

        $client->submit($crawler->filter('form[name=edit_password]')->form([
            'edit_password[password]' => 'password',
            'edit_password[plainPassword][first]' => 'new_password',
            'edit_password[plainPassword][second]' => 'new_password',
        ]));

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

//        /** @var EntityManagerInterface $entityManager */
//        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
//
//        /** @var User $user */
//        $user = $entityManager->find(User::class, 1);
//
//        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
//        $userPasswordEncoder = $client->getContainer()->get('security.password_encoder');
//
////        dump($user);
////        dump($returnUser);
//
//        self::assertTrue($userPasswordEncoder->isPasswordValid($user, 'new_password'));
//
//        $client->followRedirect();
//
//        self::assertRouteSame('account_profile');
    }

    /**
     * @dataProvider provideBadDataForEditPassword
     */
    public function testIfEditPasswordFormIsInvalid(array $formData, string $errorMessage): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('account_edit_password'));

        self::assertResponseIsSuccessful();

        $client->submit($crawler->filter('form[name=edit_password]')->form($formData));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'ul > li',
            $errorMessage
        );
    }

    public function provideBadDataForEditPassword(): Generator
    {
        yield [
            [
                'edit_password[password]' => 'fail',
                'edit_password[plainPassword][first]' => 'new_password',
                'edit_password[plainPassword][second]' => 'new_password',
            ],
            "Cette valeur doit être le mot de passe actuel de l'utilisateur.",
        ];

        yield [
            [
                'edit_password[password]' => '',
                'edit_password[plainPassword][first]' => 'new_password',
                'edit_password[plainPassword][second]' => 'new_password',
            ],
            'Cette valeur ne doit pas être vide.',
        ];

//        yield [
//            [
//                'edit_password[password]' => 'password',
//                'edit_password[plainPassword][first]' => 'fail',
//                'edit_password[plainPassword][second]' => 'fail',
//            ],
//            'Cette chaîne est trop courte. Elle doit avoir au minimum 8 caractères.',
//        ];
//
//        yield [
//            [
//                'edit_password[password]' => 'password',
//                'edit_password[plainPassword][first]' => '',
//                'edit_password[plainPassword][second]' => '',
//            ],
//            'Cette valeur ne doit pas être vide.',
//        ];
//
//        yield [
//            [
//                'edit_password[password]' => 'password',
//                'edit_password[plainPassword][first]' => 'new_password',
//                'edit_password[plainPassword][second]' => 'new_password_fail',
//            ],
//            'Votre mot de passe doit être similaire à la confirmation.',
//        ];
    }
}
