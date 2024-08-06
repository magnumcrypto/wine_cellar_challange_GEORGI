<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controller\MeasurementController;
use App\Entity\Sensor;
use App\Entity\Wine;
use App\Repository\MeasurementRepository;
use App\Repository\SensorRepository;
use App\Repository\WineRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MeasurementControllerTest extends TestCase
{
    private MockObject|Request $request;
    private MockObject|MeasurementRepository $measurementRepository;
    private MockObject|SensorRepository $sensorRepository;
    private MockObject|WineRepository $wineRepository;
    private MeasurementController $measurementController;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->measurementRepository = $this->getMockBuilder(MeasurementRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->sensorRepository = $this->getMockBuilder(SensorRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->wineRepository = $this->getMockBuilder(WineRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->measurementController = new MeasurementController();
    }

    public function testRegisterMeasurementWithValidData(): void
    {
        $this->request
            ->method('getContent')
            ->willReturn('{
                            "year": 2021,
                            "color": "Red",
                            "graduation": 11.8,
                            "temperature": 22.40,
                            "ph": 3.5,
                            "sensor": 6,
                            "wine": 1
                        }');
        $this->sensorRepository
            ->expects($this->exactly(1))
            ->method('findOneBy')
            ->with(['id' => 6])
            ->willReturn($this->createMock(Sensor::class));
        $this->wineRepository
            ->expects($this->exactly(1))
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($this->createMock(Wine::class));
        $this->measurementRepository
            ->expects($this->exactly(1))
            ->method('insertMeasurement')
            ->with(
                $this->equalTo(json_decode('{
                            "year": 2021,
                            "color": "Red",
                            "graduation": 11.8,
                            "temperature": 22.40,
                            "ph": 3.5,
                            "sensor": 6,
                            "wine": 1
                        }')),
                $this->isInstanceOf(Wine::class),
                $this->isInstanceOf(Sensor::class)
            )
            ->willReturn(1);

        $response = $this->measurementController->registerMeasurement(
            $this->request,
            $this->measurementRepository,
            $this->sensorRepository,
            $this->wineRepository
        );

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Measurement with id 1 created']),
            $response->getContent()
        );
        self::assertEquals(201, $response->getStatusCode());
    }

    public function testRegisterMeasurementWithInvalidData(): void
    {
        $this->request
            ->method('getContent')
            ->willReturn('');

        $response = $this->measurementController->registerMeasurement(
            $this->request,
            $this->measurementRepository,
            $this->sensorRepository,
            $this->wineRepository
        );

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode(['error' => 'No data received']),
            $response->getContent()
        );
        self::assertEquals(400, $response->getStatusCode());
    }
}
