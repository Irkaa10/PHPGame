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
    private $registrationRepository;

    public function __construct(UserRepository $userRepository, TournamentRepository $tournamentRepository, RegistrationRepository $registrationRepository)
    {
        $this->userRepository = $userRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->registrationRepository = $registrationRepository;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();
        $tournaments = $this->tournamentRepository->findAll();
        $registrations = $this->registrationRepository->findAll();

        $userIds = [];
        $tournamentIds = [];

        foreach ($users as $user) {
            $userIds[] = $user->getId();
        }

        foreach ($tournaments as $tournament) {
            $tournamentIds[] = $tournament->getId();
        }

        foreach ($tournaments as $tournament) {
            // Randomly select users for registration
            $selectedUsers = $this->getRandomUsers($users);

            foreach ($selectedUsers as $user) {
                // Check if the user is already registered for this tournament
                if ($registrations->get) {
                    $registration = new Registration();
                    $registration->setTournament($tournament);
                    $registration->addPlayer($user);
                    // Assuming registration date is the current date
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
            // Ajoutez d'autres dépendances si nécessaire
        );
    }
}
