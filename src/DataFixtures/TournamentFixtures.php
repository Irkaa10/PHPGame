<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Tournament;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TournamentFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        // Fetch all users from the repository
        $users = $this->userRepository->findAll();

        // Initialize an empty array to store user IDs
        $userIds = [];

        // Iterate through each user and extract their ID
        foreach ($users as $user) {
            $userIds[] = $user->getId();
        }

        // Create 10 tournaments
        for ($i = 1; $i <= 5; $i++) {
            $tournament = new Tournament();
            $tournament->setTournamentName('Tournament ' . $i);
            // Set other properties like startDate, endDate, location, description, maxParticipants, etc.
            $tournament->setStartDate(new \DateTime('2024-04-' . $i));
            $tournament->setEndDate(new \DateTime('2024-04-' . ($i + 2)));
            $tournament->setLocation('Location ' . $i);
            $tournament->setDescription('Description ' . $i);
            $tournament->setMaxParticipants(rand(10, 50)); // Example: Random number between 10 and 50
            $tournament->setStatus(true); // Example: Active status
            $tournament->setGame('Game ' . $i);

            // Set organizer randomly from the fetched user IDs
            $randomOrganizerId = $userIds[array_rand($userIds)];
            $organizer = $manager->getRepository(User::class)->find($randomOrganizerId);
            $tournament->setOrganizer($organizer);

            // Optionally, set a winner
            // $winner = $manager->getRepository(User::class)->find(2); // Assuming user with ID 2 is the winner
            // $tournament->setWinner($winner);

            $manager->persist($tournament);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            // Ajoutez d'autres dépendances si nécessaire
        );
    }
}
