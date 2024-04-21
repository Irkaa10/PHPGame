<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    // private $passwordEncoder;

    // public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    // {
    //     $this->passwordEncoder = $passwordEncoder;
    // }

    public function load(ObjectManager $manager)
    {
        // Create 10 users
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setLastName('Lastname'.$i);
            $user->setFirstName('Firstname'.$i);
            $user->setUsername('user'.$i);
            $user->setEmailAdress('user'.$i.'@example.com');
            $user->setPassword('password');
            $user->setStatus('actif');

            $manager->persist($user);
        }

        $manager->flush();
    }
}
