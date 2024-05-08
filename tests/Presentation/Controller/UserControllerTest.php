<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Application\DTO\ResponseDTO\SuccessDTO;
use App\Presentation\Controller\UserController;
use App\Application\UseCase\User\CreateUserUseCase;
use App\Application\UseCase\User\GetUserUseCase;
use App\Application\UseCase\User\UpdateUserUseCase;
use App\Application\UseCase\User\DeleteUserUseCase;
use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\UserResponseDTO;
use App\Application\DTO\ResponseDTO\ErrorDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends TestCase
{
    private $createUserUseCase;
    private $getUserUseCase;
    private $updateUserUseCase;
    private $deleteUserUseCase;

    protected function setUp(): void
    {
        $this->createUserUseCase = $this->createMock(CreateUserUseCase::class);
        $this->getUserUseCase = $this->createMock(GetUserUseCase::class);
        $this->updateUserUseCase = $this->createMock(UpdateUserUseCase::class);
        $this->deleteUserUseCase = $this->createMock(DeleteUserUseCase::class);
    }

    public function testCreateUser(): void
    {
        $expectedResponse = new UserResponseDTO();
        $expectedResponse->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $expectedResponse->phone = '+79998887766';
        $expectedResponse->email = 'user@example.com';
        $expectedResponse->surname = 'Иванов';
        $expectedResponse->name = 'Иван';
        $expectedResponse->is_moderator = false;

        $this->createUserUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $request = new Request([], [], [], [], [], [], json_encode([
            'phone' => '+79998887766',
            'password' => 'password123',
            'email' => 'user@example.com',
            'surname' => 'Иванов',
            'name' => 'Иван',
            'is_moderator' => false,
        ]));

        $response = $controller->createUser($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($expectedResponse->uuid, $responseData['uuid']);
        $this->assertEquals($expectedResponse->phone, $responseData['phone']);
    }

    public function testGetUserByUuid(): void
    {
        $expectedResponse = new UserResponseDTO();
        $expectedResponse->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $expectedResponse->phone = '+79998887766';

        $this->getUserUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $response = $controller->getUserByUuid('123e4567-e89b-12d3-a456-426614174000');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($expectedResponse->uuid, $responseData['uuid']);
    }

    public function testUpdateUser(): void
    {
        $expectedResponse = new UserResponseDTO();
        $expectedResponse->uuid = '123e4567-e89b-12d3-a456-426614174000';

        $this->updateUserUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $request = new Request([], [], [], [], [], [], json_encode([
            'phone' => '+79998887766',
            'email' => 'user_new@example.com',
            'surname' => 'Иванов',
            'name' => 'Иван',
        ]));

        $response = $controller->updateUser('123e4567-e89b-12d3-a456-426614174000', $request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($expectedResponse->uuid, $responseData['uuid']);
    }

    public function testDeleteUser(): void
    {
        $expectedResponse = new SuccessDTO();
        $expectedResponse->success = true;

        $this->deleteUserUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $response = $controller->deleteUser('123e4567-e89b-12d3-a456-426614174000');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
