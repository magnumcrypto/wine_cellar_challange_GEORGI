<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\BaseClient;
use App\Tests\Functional\WineBaseClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WineControllerTest extends WineBaseClient
{
    private const ENDPOINT = '/api/wines';

    public function testGetAllWines(): void
    {
        $responseData = json_encode(
            [
                'wines' => [
                    [
                        'name' => 'Chardonnay',
                        'year' => 2018,
                        'measurements' => [
                            [
                                'year' => 2018,
                                'color' => 'red',
                                'graduation' => 12.50,
                                'temperature' => 15.45,
                                'ph' => 3.50
                            ]
                        ]
                    ]
                ]
            ]
        );

        self::$wineBaseClient->request(Request::METHOD_GET, self::ENDPOINT);
        $response = self::$wineBaseClient->getResponse();

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString($responseData, $response->getContent());

        $responseContent = json_decode($response->getContent(), true);
        self::assertArrayHasKey('wines', $responseContent);
    }
}
