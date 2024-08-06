<?php

namespace App\Controller;

use App\Repository\MeasurementRepository;
use App\Repository\WineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class WineController extends AbstractController
{
    #[Route('/wines', name: 'app_wines', methods: ['GET'])]
    public function getAll(WineRepository $wineRepository, MeasurementRepository $measurementRepository): JsonResponse
    {
        try {
            $wines = $wineRepository->getAllWines($measurementRepository);
            return new JsonResponse($wines, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An unexpected error occurred. Please try again later.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
