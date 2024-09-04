<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class RegistrationController extends AbstractController
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    #[Route('/registration', name: 'app_registration', methods: ['POST'])]
    public function registerUser(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        if (!$request->getContent()) {
            return new JsonResponse(['error' => 'No data provided'], Response::HTTP_BAD_REQUEST);
        }
        $dataUser = json_decode($request->getContent());
        $created = $userRepository->registerUser($dataUser, $passwordHasher);
        if (is_null($created)) {
            return new JsonResponse(['error' => 'Email alredy exists'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $this->sendEmail($dataUser);
        return new JsonResponse(['message' => 'User ' . $created . ' created'], Response::HTTP_CREATED);
    }

    public function sendEmail(object $dataUser): void
    {
        $subject = 'Your registration has been successful!';
        $template = 'mailer/index.html.twig';
        $this->emailService->sendEmail($dataUser->email, $subject, $template, $dataUser->name, $dataUser->surnames);
    }
}
