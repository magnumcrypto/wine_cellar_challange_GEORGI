<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\BaseClient;

class WineControllerTest extends BaseClient
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

        self::$baseClient->request('GET', self::ENDPOINT);
        $response = self::$baseClient->getResponse();

        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString($responseData, $response->getContent());

        $responseContent = json_decode($response->getContent(), true);
        self::assertArrayHasKey('wines', $responseContent);
    }
}
