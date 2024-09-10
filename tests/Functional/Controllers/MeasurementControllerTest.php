<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\BaseClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MeasurementControllerTest extends BaseClient
{
    private const ENDPOINT = '/api/measurements';

    public function testRegisterMeasurement(): void
    {
        $payload = json_encode([
            "year" => 2021,
            "color" => "Red",
            "graduation" => 11.8,
            "temperature" => 22.40,
            "ph" => 3.5,
            "sensor" => 1,
            "wine" => 1
        ]);

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], $payload);
        $response = self::$baseClient->getResponse();

        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

        $responseContent = json_decode($response->getContent(), true);
        self::assertArrayHasKey('message', $responseContent);
        self::assertEquals('Measurement with id 2 created', $responseContent['message']);
    }

    public function testRegisterMeasurementWithInvalidData(): void
    {
        $payload = json_encode([
            "year" => 2021,
            "color" => "Red",
            "graduation" => 11.8,
            "temperature" => 22.40,
            "ph" => 3.5,
            "sensor" => 1,
            "wine" => 0 // Invalid wine id
        ]);

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], $payload);
        $response = self::$baseClient->getResponse();

        self::assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseContent = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $responseContent);
        self::assertEquals('Sensor or wine not found', $responseContent['error']);
    }
}
