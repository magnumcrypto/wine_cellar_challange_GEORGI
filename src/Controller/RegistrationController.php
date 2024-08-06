<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration', methods: ['POST'])]
    public function index(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        if (!$request->getContent()) {
            return new JsonResponse(['error' => 'No data provided'], Response::HTTP_BAD_REQUEST);
        }
        $dataUser = json_decode($request->getContent());
        $created = $userRepository->registerUser($dataUser, $passwordHasher);
        if (is_null($created)) {
            return new JsonResponse(['error' => 'Error creating user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse(['message' => 'User with ' . $created . ' created'], Response::HTTP_CREATED);
    }
}
