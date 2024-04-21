<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Entity\User;
use App\Repository\RegistrationRepository;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GameFixtures extends Fixture implements DependentFixtureInterface
{
    private $tournamentRepository;
    private $userRepository;
    private $registrationRepository;

    public function __construct(TournamentRepository $tournamentRepository, UserRepository $userRepository, RegistrationRepository $registrationRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->userRepository = $userRepository;
        $this->registrationRepository = $registrationRepository;
    }

    public function load(ObjectManager $manager)
    {
        $tournaments = $this->tournamentRepository->findAll();
        $registrations = $this->registrationRepository->findAll();

        foreach ($tournaments as $tournament) {
            $participants = $tournament->getRegistrations();
            $numParticipants = count($participants);

            // Ensure there are at least 2 participants for a game
            if ($numParticipants < 2) {
                continue;
            }

            // Generate games for the tournament
            for ($i = 0; $i < $numParticipants - 1; $i++) {
                for ($j = $i + 1; $j < $numParticipants; $j++) {
                    $registration1 = $participants[$i];
                    $registration2 = $participants[$j];

                    // Get the first player from the first registration
                    $player1 = $registration1->getPlayer()->first();
                    // Get the second player from the second registration
                    $player2 = $registration2->getPlayer()->first();

                    // Check if players exist
                    if (!$player1 || !$player2) {
                        continue;
                    }

                    $game = new Game();
                    $game->setTournament($tournament);
                    $game->setGameDate(new \DateTime());
                    $game->setStatus("en cours");
                    $game->setPlayer1($player1);
                    $game->setPlayer2($player2);

                    // Optionally, you can set scores for players
                    // $game->setScorePlayer1(...);
                    // $game->setScorePlayer2(...);

                    $manager->persist($game);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            TournamentFixtures::class,
            RegistrationFixtures::class,
            // Add other dependencies if necessary
        ];
    }
}
