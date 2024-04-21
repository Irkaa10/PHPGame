<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/players')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_player_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $users = $userRepository->findAll();
        $usersSerializer = $serializerInterface->serialize($users, 'json', ['groups' => 'user']);

        return new JsonResponse($usersSerializer);
    }

    #[Route('/', name: 'app_player_new', methods: ['POST'])]
    public function new(EntityManagerInterface $em, SerializerInterface $serializerInterface, Request $request, ValidatorInterface $validatorInterface, UserPasswordHasherInterface $passwordEncoder): JsonResponse
    {
        $userData = json_decode($request->getContent(), true);

        // Create a new User instance
        $user = new User();
        $user->setFirstName($userData['firstName']);
        $user->setLastName($userData['lastName']);
        $user->setUsername($userData['username']);
        $user->setEmailAdress($userData['emailAddress']);
        $user->setStatus($userData['status']);
        
        // Hash the password
        $hashedPassword = $passwordEncoder->hashPassword($user, $userData['password']);
        $user->setPassword($hashedPassword);

        // Validate the user entity
        $errors = $validatorInterface->validate($user);
        if (count($errors) > 0) {
            $errorString = (string) $errors;
            return new JsonResponse(['error' => $errorString], Response::HTTP_BAD_REQUEST);
        }

        // Persist the user entity
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'L\'utilisateur a bien été ajouté.'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_player_show', methods: ['GET'])]
    public function show($id, UserRepository $userRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'L\'utilisateur n\'existe pas.']);
        }
        $tournamentsSerializer = $serializerInterface->serialize($user, 'json', ['groups' => 'user']);

        return new JsonResponse($tournamentsSerializer);
    }

    #[Route('/edit/{id}', name: 'app_player_edit', methods: ['PUT'])]
    public function edit($id, UserRepository $userRepository, SerializerInterface $serializerInterface, ValidatorInterface $validatorInterface, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'L\'utilisateur n\'existe pas.'], 404);
        }

        $serializerInterface->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);

        $errors = $validatorInterface->validate($user);
        if (count($errors) > 0) {
            $errorString = (string) $errors;
            return new JsonResponse(['error' => $errorString], Response::HTTP_BAD_REQUEST);
        }

        $em->flush();
        return new JsonResponse(['message' => 'L\'utilisateur a bien été modifié.'], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'app_player_delete', methods: ['DELETE'])]
    public function delete($id, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'L\' utilisateur n\'existe pas.']);
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(['message' => 'L\'utilisateur a bien été supprimé.'], Response::HTTP_CREATED);
    }
}