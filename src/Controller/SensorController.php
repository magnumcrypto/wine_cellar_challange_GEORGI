<?php

namespace App\Controller;

use App\Repository\SensorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SensorController extends AbstractController
{
    #[Route('/sensors', name: 'app_sensors', methods: ['GET'])]
    public function getAll(SensorRepository $sensorRepository): JsonResponse
    {
        $sensors = $sensorRepository->getSensorsOrderedByName();
        return new JsonResponse($sensors, Response::HTTP_OK);
    }
}
