<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controllers;

use App\Controller\RegistrationController;
use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationControllerTest extends TestCase
{
    private MockObject|Request $request;
    private MockObject|UserRepository $userRepository;
    private MockObject|UserPasswordHasherInterface $passwordHasher;
    private RegistrationController $registrationController;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->passwordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->registrationController = new RegistrationController();
    }

    public function testRegisterUserWithValidCredentials(): void
    {
        $this->request
            ->method('getContent')
            ->willReturn('{
                            "name": "Jon",
                            "surnames": "Doe",
                            "email": "jondoe101@email.com",
                            "password": "mypassword"
                        }');
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('registerUser')
            ->with(
                $this->equalTo(json_decode($this->request->getContent())),
                $this->isInstanceOf(UserPasswordHasherInterface::class)
            )
            ->willReturn('jondoe101@email.com');

        $response = $this->registrationController->registerUser(
            $this->request,
            $this->userRepository,
            $this->passwordHasher
        );
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode(['message' => 'User jondoe101@email.com created']),
            $response->getContent()
        );
        self::assertEquals(201, $response->getStatusCode());
    }

    public function testRegisterUserWithInvalidCredentials(): void
    {
        $this->request
            ->method('getContent')
            ->willReturn('{
                            "name": "Jon",
                            "surnames": "Doe",
                            "email": "jondoe101@email.com",
                            "password": "mypassword"
                        }');
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('registerUser')
            ->with(
                $this->equalTo(json_decode($this->request->getContent())),
                $this->isInstanceOf(UserPasswordHasherInterface::class)
            )
            ->willReturn(null);

        $response = $this->registrationController->registerUser(
            $this->request,
            $this->userRepository,
            $this->passwordHasher
        );
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Email alredy exists']),
            $response->getContent()
        );
        self::assertEquals(500, $response->getStatusCode());
    }
}
