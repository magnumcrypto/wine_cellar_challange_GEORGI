<?php

declare(strict_types=1);

use App\Controller\WineController;
use App\Repository\MeasurementRepository;
use App\Repository\WineRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class WineControllerTest extends TestCase
{
    private MockObject|WineRepository $wineRepository;
    private MockObject|MeasurementRepository $measurementRepository;
    private WineController $wineController;

    public function setUp(): void
    {
        parent::setUp();

        $this->wineRepository = $this->getMockBuilder(WineRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->measurementRepository = $this->getMockBuilder(MeasurementRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->wineController = new WineController();
    }

    public function testGetAllWines(): void
    {
        $expectedData =
            [
                'wines' => [
                    'name' => 'Wine name',
                    'year' => 2021,
                    'measurements' => [
                        'year' => 2021,
                        'color' => 'red',
                        'graduation' => 13.5,
                        'temperature' => 18.2,
                        'ph' => 3.5
                    ]
                ]
            ];
        $this->wineRepository
            ->expects($this->exactly(1))
            ->method('getAllWines')
            ->with($this->measurementRepository)
            ->willReturn($expectedData);

        $response = $this->wineController->getAll(
            $this->wineRepository,
            $this->measurementRepository
        );
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode($expectedData),
            $response->getContent()
        );
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testFailureGetAllWines(): void
    {
        $this->wineRepository
            ->expects($this->exactly(1))
            ->method('getAllWines')
            ->with($this->measurementRepository)
            ->willThrowException(new \Exception());

        $response = $this->wineController->getAll(
            $this->wineRepository,
            $this->measurementRepository
        );
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode(['error' => 'An unexpected error occurred. Please try again later.']),
            $response->getContent()
        );
        self::assertEquals(500, $response->getStatusCode());
    }
}
