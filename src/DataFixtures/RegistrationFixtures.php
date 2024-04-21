<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Tournament;
use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RegistrationFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    private $tournamentRepository;

    public function __construct(UserRepository $userRepository, TournamentRepository $tournamentRepository)
    {
        $this->userRepository = $userRepository;
        $this->tournamentRepository = $tournamentRepository;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();
        $tournaments = $this->tournamentRepository->findAll();

        foreach ($tournaments as $tournament) {
            // Randomly select users for registration
            $selectedUsers = $this->getRandomUsers($users);

            foreach ($selectedUsers as $user) {
                // Check if the user is already registered for this tournament
                $existingRegistration = $tournament->getRegistrations()->filter(function ($registration) use ($user) {
                    return $registration->getPlayer()->contains($user);
                })->first();

                if (!$existingRegistration) {
                    $registration = new Registration();
                    $registration->setTournament($tournament);
                    $registration->addPlayer($user);
                    $registration->setStatus("Confirmée");
                    $registration->setRegistrationDate(new \DateTime());

                    $manager->persist($registration);
                }
            }
        }

        $manager->flush();
    }

    // Function to get a random subset of users
    private function getRandomUsers($users, $count = 10)
    {
        $randomUsers = [];
        $keys = array_rand($users, min($count, count($users)));

        if (!is_array($keys)) {
            $keys = [$keys];
        }

        foreach ($keys as $key) {
            $randomUsers[] = $users[$key];
        }

        return $randomUsers;
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            TournamentFixtures::class,
            // Add other dependencies if necessary
        );
    }
}
