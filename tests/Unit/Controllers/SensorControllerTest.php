<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controllers;

use App\Controller\SensorController;
use App\Repository\SensorRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class SensorControllerTest extends TestCase
{
    private MockObject|SensorRepository $sensorRepository;
    private MockObject|Request $request;
    private SensorController $sensorController;

    public function setUp(): void
    {
        parent::setUp();

        $this->sensorRepository = $this->getMockBuilder(SensorRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sensorController = new SensorController();
    }

    public function testGetAllSensors(): void
    {
        $expectedData = [
            "sensors" => [
                ["name" => "Humidity sensor"],
                ["name" => "pH electrode"],
                ["name" => "Pressure sensor"],
                ["name" => "Sensor name"],
                ["name" => "Temperature sensor"]
            ]
        ];
        $this->sensorRepository
            ->expects($this->exactly(1))
            ->method('getSensorsOrderedByName')
            ->willReturn($expectedData);

        $response = $this->sensorController->getAll($this->sensorRepository);
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode($expectedData),
            $response->getContent()
        );
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testFailureGetAllSensors(): void
    {
        $this->sensorRepository
            ->expects($this->exactly(1))
            ->method('getSensorsOrderedByName')
            ->willThrowException(new \Exception());

        $response = $this->sensorController->getAll($this->sensorRepository);
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode(['error' => 'An error occurred while fetching sensors. Please try again later.']),
            $response->getContent()
        );
        self::assertEquals(500, $response->getStatusCode());
    }

    public function testRegisterSensor(): void
    {
        $this->request
            ->method('getContent')
            ->willReturn('{"name": "New sensor"}');
        $this->sensorRepository
            ->expects($this->exactly(1))
            ->method('insertSensor')
            ->with(json_decode($this->request->getContent()))
            ->willReturn(1);

        $response = $this->sensorController->registerSensor(
            $this->request,
            $this->sensorRepository
        );
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Sensor with id 1 created']),
            $response->getContent()
        );
        self::assertEquals(201, $response->getStatusCode());
    }

    public function testRegisterSensorWithEmptyData(): void
    {
        $this->request
            ->method('getContent')
            ->willReturn('');
        $response = $this->sensorController->registerSensor(
            $this->request,
            $this->sensorRepository
        );
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode(['error' => 'No data received']),
            $response->getContent()
        );
        self::assertEquals(400, $response->getStatusCode());
    }

    public function testRegisterSensorWithExistingData(): void
    {
        $this->request
            ->method('getContent')
            ->willReturn('{"name": "New sensor"}');
        $this->sensorRepository
            ->expects($this->exactly(1))
            ->method('insertSensor')
            ->with(json_decode($this->request->getContent()))
            ->willReturn(null);

        $response = $this->sensorController->registerSensor(
            $this->request,
            $this->sensorRepository
        );
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Error inserting sensor']),
            $response->getContent()
        );
        self::assertEquals(400, $response->getStatusCode());
    }
}
