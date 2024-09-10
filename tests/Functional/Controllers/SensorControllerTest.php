<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\BaseClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SensorControllerTest extends BaseClient
{
    private const ENDPOINT = '/api/sensors';

    public function testGetAllSensors(): void
    {
        $responseData = json_encode(
            [
                'sensors' => [['name' => 'Sensor0']]
            ]
        );

        self::$baseClient->request(Request::METHOD_GET, self::ENDPOINT);
        $response = self::$baseClient->getResponse();

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString($responseData, $response->getContent());

        $responseContent = json_decode($response->getContent(), true);
        self::assertArrayHasKey('sensors', $responseContent);
    }

    public function testRegisterSensor(): void
    {
        $payload = json_encode([
            'name' => 'NewSensor'
        ]);

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], $payload);
        $response = self::$baseClient->getResponse();

        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

        $responseContent = json_decode($response->getContent(), true);
        self::assertArrayHasKey('message', $responseContent);
        self::assertEquals('Sensor with id 2 created', $responseContent['message']);
    }

    public function testRegisterSensorWithInvalidData(): void
    {
        $payload = json_encode([
            'name' => ''
        ]);

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], $payload);
        $response = self::$baseClient->getResponse();

        self::assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseContent = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $responseContent);
        self::assertEquals('Error inserting sensor', $responseContent['error']);
    }
}
