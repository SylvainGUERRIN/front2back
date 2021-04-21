<?php

declare(strict_types=1);

namespace App\Tests\Functional\UserAccount;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AvatarTest extends WebTestCase
{
    public function testAddAvatarWithImageOnGoodFormat(): void
    {
        // trait with methods for testing
        //$trait = WebTestAssertionsTrait::;

        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $uploadedFile = new UploadedFile(
            __DIR__.'./../../../src/DataFixtures/ain.jpg',
            //            __DIR__.'/../fixtures/ain.jpg',
            'ain.jpg',
            'image/jpg',
            null
        );

        /** @var User $user */
        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $crawler = $client->request('GET', $urlGenerator->generate('account_profile'));

        //self::assertResponseIsSuccessful();

//        $client->request('POST', $urlGenerator->generate('account_profile'), [], [
//            'file' => $uploadedFile,
//        ]);

        $form = $crawler->filter('form[name=edit_profile]')->form([
            'edit_profile[avatar][imageFile]' => $uploadedFile,
            'edit_profile[firstname]' => 'ain0',
            'edit_profile[email]' => 'ain0@email.com',
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
//        self::assertResponseIsSuccessful();
    }

    public function testAddAvatarWithWrongFormatForImage(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $uploadedFile = new UploadedFile(
            __DIR__.'./../../../src/DataFixtures/newspaper.svg',
            'newspaper.svg',
            'image/svg',
            null
        );

        /** @var User $user */
        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $crawler = $client->request('GET', $urlGenerator->generate('account_profile'));

        $form = $crawler->filter('form[name=edit_profile]')->form([
            'edit_profile[avatar][imageFile]' => $uploadedFile,
            'edit_profile[firstname]' => 'ain0',
            'edit_profile[email]' => 'ain0@email.com',
        ]);

        $client->submit($form);

        //self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'ul > li',
            'Ce fichier n\'est pas une image valide.'
        );
    }

    public function testAddAvatarWithImageLengthMoreThanSixMegaOctet(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $uploadedFile = new UploadedFile(
            __DIR__.'./../../../src/DataFixtures/test-bigger-images.jpg',
            'test-bigger-images.jpg',
            'image/jpg',
            null
        );

        /** @var User $user */
        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $crawler = $client->request('GET', $urlGenerator->generate('account_profile'));

        $form = $crawler->filter('form[name=edit_profile]')->form([
            'edit_profile[avatar][imageFile]' => $uploadedFile,
            'edit_profile[firstname]' => 'ain0',
            'edit_profile[email]' => 'ain0@email.com',
        ]);

        $client->submit($form);

        //self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorTextContains(
            'ul > li',
            'Le fichier est trop volumineux (7.23 MB). Sa taille ne doit pas dépasser 6 MB.'
        );
    }
}
