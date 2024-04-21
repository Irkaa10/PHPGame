<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tournaments')]
class GameController extends AbstractController
{
    // MARCHE
    #[Route('/{id}/games', name: 'app_game_show', methods: ['GET'])]
    public function index($id, SerializerInterface $serializerInterface, GameRepository $gameRepository): JsonResponse
    {
        $games = $gameRepository->findBy(['tournament' => $id]);

        if (empty($games)) {
            return new JsonResponse(['message' => 'Aucun match trouvé pour ce tournoi'], 404);
        }

        $gamesSerialized = $serializerInterface->serialize($games, 'json', ['groups' => 'game']);

        return new JsonResponse($gamesSerialized);
    }

    // MARCHE
    #[Route('/{id}/games', name: 'app_create_game_tournament', methods: ['POST'])]
    public function createGame($id, Request $request, UserRepository $userRepository, TournamentRepository $TournamentRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $tournament = $TournamentRepository->find($id);
        #$tournament = $this->getDoctrine()->getRepository(Tournament::class)->find($id);

        if (!$tournament) {
            return new JsonResponse(['message' => 'Le tournoi n\'existe pas'], 404);
        }

        // Récupérer les données de la requête
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['player1'])) {
            return new JsonResponse(['message' => 'L\'identifiant du joueur 1 est requis'], 400);
        }

        if (!isset($requestData['player2'])) {
            return new JsonResponse(['message' => 'L\'identifiant du joueur 2 est requis'], 400);
        }

        // Récupérer les joueurs à inscrire
        $player1 = $userRepository->find($requestData['player1']);
        $player2 = $userRepository->find($requestData['player2']);

        if ($player1 == $player2) {
            return new JsonResponse(['message' => 'Erreur'], Response::HTTP_BAD_REQUEST);
        }

        if (!$player1) {
            return new JsonResponse(['message' => 'Le joueur 1 n\'existe pas'], 404);
        }

        if (!$player2) {
            return new JsonResponse(['message' => 'Le joueur 2 n\'existe pas'], 404);
        }

        $status = $requestData['status'];

        // Créer un nouveau match
        $game = new Game();
        $game->setPlayer1($player1);
        $game->setPlayer2($player2);
        $game->setTournament($tournament);
        $game->setGameDate(new \DateTime());
        $game->setStatus($status);

        // Enregistrer le match dans la base de données
        $entityManager->persist($game);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Match créé avec succès.'], 201);
    }

    // MARCHE
    #[Route('/{idTournament}/games/{idGame}', name: 'app_delete_game', methods: ['DELETE'])]
    public function delete($idTournament, $idGame, GameRepository $gameRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer le match
        $game = $gameRepository->find($idGame);
        #$registration = $this->getDoctrine()->getRepository(Registration::class)->find($idRegistration);

        if (!$game) {
            return new JsonResponse(['message' => 'Le match n\'existe pas'], 404);
        }

        if ($game->getTournament()->getId() != $idTournament) {
            return new JsonResponse(['message' => 'Le match ne correspond pas à ce tournoi'], 400);
        }

        $entityManager->remove($game);
        $entityManager->flush();

        return new JsonResponse(['message' => 'match annulée avec succès'], 200);
    }


    // MARCHE
    #[Route('/{idTournament}/games/{idGame}', name: 'app_show_game', methods: ['GET'])]
    public function show($idTournament, $idGame, GameRepository $gameRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        // Récupérer le match
        $game = $gameRepository->find($idGame);
        #$registration = $this->getDoctrine()->getRepository(Registration::class)->find($idRegistration);

        if (!$game) {
            return new JsonResponse(['message' => 'Le match n\'existe pas'], 404);
        }

        if ($game->getTournament()->getId() != $idTournament) {
            return new JsonResponse(['message' => 'Le tournoi n\'existe pas.'], 400);
        }

        $gameSerialized = $serializerInterface->serialize($game, 'json', ['groups' => 'game']);

        return new JsonResponse($gameSerialized);
    }

    // MARCHE
    #[Route('/edit/{idTournament}/games/{idGame}', name: 'app_edit_tournament', methods: ['PUT'])]
    public function edit($idTournament, $idGame, Request $request, GameRepository $gameRepository, TournamentRepository $TournamentRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $tournament = $TournamentRepository->find($idTournament);
        #$tournament = $this->getDoctrine()->getRepository(Tournament::class)->find($id);

        if (!$tournament) {
            return new JsonResponse(['message' => 'Le tournoi n\'existe pas'], 404);
        }

        $game = $gameRepository->find($idGame);

        if (!$game) {
            return new JsonResponse(['message' => 'Le match n\'existe pas'], 404);
        }

        // Récupérer les données de la requête
        $requestData = json_decode($request->getContent(), true);

        // if (!isset($requestData['player1'])) {
        //     return new JsonResponse(['message' => 'L\'identifiant du joueur 1 est requis'], 400);
        // }

        // if (!isset($requestData['player2'])) {
        //     return new JsonResponse(['message' => 'L\'identifiant du joueur 2 est requis'], 400);
        // }

        // Récupérer les joueurs à inscrire
        // $player1 = $userRepository->find($requestData['player1']);
        // $player2 = $userRepository->find($requestData['player2']);

        // if ($player1 == $player2) {
        //     return new JsonResponse(['message' => 'Erreur'], Response::HTTP_BAD_REQUEST);
        // }

        // if (!$player1) {
        //     return new JsonResponse(['message' => 'Le joueur 1 n\'existe pas'], 404);
        // }

        // if (!$player2) {
        //     return new JsonResponse(['message' => 'Le joueur 2 n\'existe pas'], 404);
        // }

        $scorePlayer1 = $requestData['scorePlayer1'];
        $scorePlayer2 = $requestData['scorePlayer2'];

        // Modifier un nouveau match
        $game->setScorePlayer1($scorePlayer1);
        $game->setScorePlayer2($scorePlayer2);

        // Enregistrer le match dans la base de données
        $entityManager->persist($game);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Match modifié avec succès.'], 201);
    }
}
