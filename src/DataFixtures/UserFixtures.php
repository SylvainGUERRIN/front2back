<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
//        $user = (new User())->setEmail("ain@email.com");
        for ($i = 0; $i <= 5; ++$i) {
            $user = new User();
            $user->setFirstname('ain'.$i);
            $user->setEmail('ain'.$i.'@email.com');
            $user->setRegisteredAt(new \DateTimeImmutable('now'));
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'password'.$i));

            $manager->persist($user);
        }
//        $user = new User();
//        $user->setFirstname('ain');
//        $user->setEmail('ain@email.com');
//        $user->setRegisteredAt(new \DateTimeImmutable('now'));
//
//        $manager->persist($user->setPassword($this->userPasswordEncoder->encodePassword($user, 'password')));

        $manager->flush();
    }
}