<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Entity\Registration;
use App\Repository\UserRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RegistrationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tournaments')]
class RegistrationController extends AbstractController
{
    // MARCHE
    #[Route('/{id}/registrations', name: 'app_registration_show', methods: ['GET'])]
    public function show($id, SerializerInterface $serializerInterface, RegistrationRepository $registrationRepository): JsonResponse
    {
        $registrations = $registrationRepository->findBy(['tournament' => $id]);

        if (empty($registrations)) {
            return new JsonResponse(['message' => 'Aucune inscription trouvée pour ce tournoi'], 404);
        }

        $registrationsSerialized = $serializerInterface->serialize($registrations, 'json', ['groups' => 'registration']);

        return new JsonResponse($registrationsSerialized);
    }

    // MARCHE
    #[Route('/{id}/registrations', name: 'app_register_player_to_tournament', methods: ['POST'])]
    public function registerPlayerToTournament($id, Request $request, UserRepository $userRepository, TournamentRepository $TournamentRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $tournament = $TournamentRepository->find($id);
        #$tournament = $this->getDoctrine()->getRepository(Tournament::class)->find($id);

        if (!$tournament) {
            return new JsonResponse(['message' => 'Le tournoi n\'existe pas'], 404);
        }

        // Récupérer les données de la requête
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['player'])) {
            return new JsonResponse(['message' => 'L\'identifiant du joueur est requis'], 400);
        }

        // Récupérer le joueur à inscrire
        $player = $userRepository->find($requestData['player']);

        if (!$player) {
            return new JsonResponse(['message' => 'Le joueur n\'existe pas'], 404);
        }

        $status = $requestData['status'];

        // Créer une nouvelle inscription
        $registration = new Registration();
        $registration->addPlayer($player);
        $registration->setTournament($tournament);
        $registration->setRegistrationDate(new \DateTime());
        $registration->setStatus($status);

        // Enregistrer l'inscription dans la base de données
        $entityManager->persist($registration);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Joueur inscrit avec succès au tournoi'], 201);
    }

    // MARCHE
    #[Route('/{idTournament}/registrations/{idRegistration}', name: 'app_cancel_registration', methods: ['DELETE'])]
    public function cancelRegistration($idTournament, $idRegistration, RegistrationRepository $RegistrationRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer l'inscription
        $registration = $RegistrationRepository->find($idRegistration);
        #$registration = $this->getDoctrine()->getRepository(Registration::class)->find($idRegistration);

        if (!$registration) {
            return new JsonResponse(['message' => 'L\'inscription n\'existe pas'], 404);
        }

        if ($registration->getTournament()->getId() != $idTournament) {
            return new JsonResponse(['message' => 'L\'inscription ne correspond pas à ce tournoi'], 400);
        }

        $entityManager->remove($registration);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Inscription annulée avec succès'], 200);
    }
}
