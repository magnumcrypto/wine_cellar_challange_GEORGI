<?php

namespace App\Tests\Functional\Controllers;

use App\Tests\Functional\BaseClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RegistrationControllerTest extends BaseClient
{
    private const ENDPOINT = '/api/registration';

    public function testRegiterUser(): void
    {
        $payload = json_encode([
            'name' => 'User1',
            'surnames' => 'Testing',
            'email' => 'test@hotmail.com',
            'password' => 'password'
        ]);

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], $payload);
        $response = self::$baseClient->getResponse();

        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

        $responseContent = json_decode($response->getContent(), true);
        self::assertArrayHasKey('message', $responseContent);
        self::assertEquals('User test@hotmail.com created', $responseContent['message']);
    }

    public function testRegiterUserWithInvalidData(): void
    {
        $payload = json_encode([
            'name' => 'User1',
            'surnames' => 'Testing',
            'email' => '',
            'password' => 'password'
        ]);

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], $payload);
        $response = self::$baseClient->getResponse();

        self::assertEquals(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testRegisterUserWithRepeatedEmail(): void
    {
        $payload = json_encode([
            'name' => 'User1',
            'surnames' => 'Testing',
            'email' => 'user0@hotmail.com',
            'password' => 'password'
        ]);

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], $payload);
        $response = self::$baseClient->getResponse();

        self::assertEquals(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        $responseContent = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $responseContent);
        self::assertEquals('Email alredy exists', $responseContent['error']);
    }
}
