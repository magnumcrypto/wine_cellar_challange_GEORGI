<?php

namespace App\Controller;

use App\Repository\MeasurementRepository;
use App\Repository\SensorRepository;
use App\Repository\WineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MeasurementController extends AbstractController
{
    #[Route('/measurement/new', name: 'app_new_measurement', methods: ['POST'])]
    public function registerMeasurement(Request $request, MeasurementRepository $measurementRepository, SensorRepository $sensorRepository, WineRepository $wineRepository): JsonResponse
    {
        if (!$request->getContent()) {
            return new JsonResponse(['error' => 'No data received'], Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent());
        $sensor = $sensorRepository->findOneBy(['id' => $data->sensor]);
        $wine = $wineRepository->findOneBy(['id' => $data->wine]);
        if (is_null($sensor) || is_null($wine)) {
            return new JsonResponse(['error' => 'Sensor or wine not found'], Response::HTTP_BAD_REQUEST);
        }
        $measurementId = $measurementRepository->insertMeasurement($data, $wine, $sensor);
        if (is_null($measurementId)) {
            return new JsonResponse(['error' => 'Error inserting measurement'], Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse(
            ['message' => 'Measurement with id ' . $measurementId . ' created'],
            Response::HTTP_CREATED
        );
    }
}
