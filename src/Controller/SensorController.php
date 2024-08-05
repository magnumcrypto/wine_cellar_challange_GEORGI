<?php

namespace App\Controller;

use App\Repository\SensorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SensorController extends AbstractController
{
    #[Route('/sensors', name: 'app_sensors', methods: ['GET'])]
    public function getAll(SensorRepository $sensorRepository): JsonResponse
    {
        try {
            $sensors = $sensorRepository->getSensorsOrderedByName();
            return new JsonResponse($sensors, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/sensor/new', name: 'app_register_sensor', methods: ['POST'])]
    public function registerSensor(Request $request, SensorRepository $sensorRepository): JsonResponse
    {
        if (!$request->getContent()) {
            return new JsonResponse(['error' => 'No data received'], Response::HTTP_BAD_REQUEST);
        }
        $dataSensor = json_decode($request->getContent());
        $sensorId = $sensorRepository->insertSensor($dataSensor);
        if (is_null($sensorId)) {
            return new JsonResponse(['error' => 'Error inserting sensor'], Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse(
            [
                'message' => 'Sensor with id ' . $sensorId . ' created',
            ],
            Response::HTTP_CREATED
        );
    }
}
