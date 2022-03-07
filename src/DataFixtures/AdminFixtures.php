<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordEncoder;

    public function __construct(UserPasswordHasherInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('admin');
        $user->setEmail('admin@email.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setRegisteredAt(new \DateTimeImmutable('now'));
        $user->setPassword($this->userPasswordEncoder->hashPassword($user, 'password'));
        $user->setActivate(true);

        $manager->persist($user);

        $manager->flush();
    }
}
