<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api')]
class LoginController extends AbstractController
{
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user)
    {
        if (null === $user) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(
            [
                'user' => $user->getUserIdentifier()
            ],
            Response::HTTP_OK
        );
    }
}
