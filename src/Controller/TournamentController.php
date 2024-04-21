<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Repository\UserRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tournaments')]
class TournamentController extends AbstractController
{
    #[Route('/', name: 'app_tournament_index', methods: ['GET'])]
    public function index(TournamentRepository $tournamentRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $tournaments = $tournamentRepository->findAll();
        $tournamentsSerializer = $serializerInterface->serialize($tournaments, 'json', ['groups' => 'tournament']);

        return new JsonResponse($tournamentsSerializer);
    }

    #[Route('/', name: 'app_tournament_new', methods: ['POST'])]
    public function new(EntityManagerInterface $em, SerializerInterface $serializerInterface, Request $request, ValidatorInterface $validatorInterface, UserRepository $userRepository): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Récupérer l'utilisateur organisateur
        $organizerId = $requestData['organizer'];
        $organizer = $userRepository->find($organizerId);

        if (!$organizer) {
            return new JsonResponse(['error' => 'Utilisateur organisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // Désérialiser le tournoi
        $tournament = $serializerInterface->deserialize($request->getContent(), Tournament::class, 'json');

        // Associer l'utilisateur organisateur au tournoi
        $tournament->setOrganizer($organizer);

        // Valider le tournoi
        $errors = $validatorInterface->validate($tournament);
        if (count($errors) > 0) {
            $errorString = (string) $errors;
            return new JsonResponse(['error' => $errorString], Response::HTTP_BAD_REQUEST);
        }

        // Persistez le tournoi
        $em->persist($tournament);
        $em->flush();

        return new JsonResponse(['message' => 'Le tournoi a bien été ajouté.'], Response::HTTP_CREATED);
    }

    #[Route('/get/{id}', name: 'app_tournament_show', methods: ['GET'])]
    public function show($id, TournamentRepository $tournamentRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $tournament = $tournamentRepository->find($id);

        if (!$tournament) {
            return new JsonResponse(['message' => 'Le tournois n\'existe pas.']);
        }
        $tournamentsSerializer = $serializerInterface->serialize($tournament, 'json', ['groups' => 'tournament']);

        return new JsonResponse($tournamentsSerializer);
    }

    #[Route('/edit/{id}', name: 'app_tournament_edit', methods: ['PUT'])]
    public function edit($id, TournamentRepository $tournamentRepository, SerializerInterface $serializerInterface, ValidatorInterface $validatorInterface, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $tournament = $tournamentRepository->find($id);

        if (!$tournament) {
            return new JsonResponse(['message' => 'Le tournoi n\'existe pas.'], 404);
        }

        $serializerInterface->deserialize($request->getContent(), Tournament::class, 'json', ['object_to_populate' => $tournament]);

        $errors = $validatorInterface->validate($tournament);
        if (count($errors) > 0) {
            $errorString = (string) $errors;
            return new JsonResponse(['error' => $errorString], Response::HTTP_BAD_REQUEST);
        }

        $em->flush();
        return new JsonResponse(['message' => 'Le tournoi a bien été modifié.'], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'app_tournament_delete', methods: ['DELETE'])]
    public function delete($id, TournamentRepository $tournamentRepository, EntityManagerInterface $em): JsonResponse
    {
        $tournament = $tournamentRepository->find($id);

        if (!$tournament) {
            return new JsonResponse(['message' => 'Le tournois n\'existe pas.']);
        }

        $em->remove($tournament);
        $em->flush();

        return new JsonResponse(['message' => 'Le tournoi a bien été supprimé.'], Response::HTTP_CREATED);
    }
}
