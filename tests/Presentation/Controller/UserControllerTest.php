<?php

namespace App\Tests\Presentation\Controller;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\SuccessDTO;
use App\Application\DTO\ResponseDTO\UserResponseDTO;
use App\Application\UseCase\User\CreateUserUseCase;
use App\Application\UseCase\User\DeleteUserUseCase;
use App\Application\UseCase\User\GetUserUseCase;
use App\Application\UseCase\User\UpdateUserUseCase;
use App\Presentation\Controller\UserController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserControllerTest extends TestCase
{
    private CreateUserUseCase $createUserUseCase;
    private GetUserUseCase $getUserUseCase;
    private UpdateUserUseCase $updateUserUseCase;
    private DeleteUserUseCase $deleteUserUseCase;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->createUserUseCase = $this->createMock(CreateUserUseCase::class);
        $this->getUserUseCase = $this->createMock(GetUserUseCase::class);
        $this->updateUserUseCase = $this->createMock(UpdateUserUseCase::class);
        $this->deleteUserUseCase = $this->createMock(DeleteUserUseCase::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testCreateUserSuccess(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $data = [
            'phone' => '+79998887766',
            'password' => 'password123',
            'email' => 'user@example.com',
            'surname' => 'Иванов',
            'name' => 'Иван',
            'patronymic' => 'Иванович',
            'is_moderator' => false
        ];

        $request = new Request([], [], [], [], [], [], json_encode($data));

        $userResponseDTO = new UserResponseDTO();
        $userResponseDTO->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $userResponseDTO->phone = $data['phone'];
        $userResponseDTO->email = $data['email'];
        $userResponseDTO->surname = $data['surname'];
        $userResponseDTO->name = $data['name'];
        $userResponseDTO->is_moderator = $data['is_moderator'];

        $this->createUserUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(UserRequestDTO::class))
            ->willReturn($userResponseDTO);

        $response = $controller->createUser($request, $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(201, $response->getStatusCode());
    }

    public function testCreateUserValidationError(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $userRequest = [
            'phone' => null,
            'password' => null,
        ];

        $constraintViolation = $this->createMock(ConstraintViolation::class);
        $constraintViolation
            ->expects($this->any())
            ->method('getPropertyPath')
            ->willReturn('phone');

        $constraintViolations = new ConstraintViolationList([$constraintViolation]);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->isInstanceOf(UserRequestDTO::class))
            ->willReturn($constraintViolations);

        $request = new Request([], [], [], [], [], [], json_encode($userRequest));

        $response = $controller->createUser($request, $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertStringContainsString("Validation failed", $response->getContent());
    }

    public function testGetUserByUuidSuccess(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $user_uuid = '123e4567-e89b-12d3-a456-426614174000';
        $userResponseDTO = new UserResponseDTO();
        $userResponseDTO->uuid = $user_uuid;

        $this->getUserUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn($userResponseDTO);

        $response = $controller->getUserByUuid($user_uuid, $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testGetUserByUuidNotFound(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $user_uuid = '123e4567-e89b-12d3-a456-426614174000';

        $this->getUserUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn(null);

        $response = $controller->getUserByUuid($user_uuid, $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testGetUserByUuidValidationError(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $user_uuid = 'invalid-uuid'; // неправильный UUID

        $constraintViolation = $this->createMock(ConstraintViolation::class);
        $constraintViolation
            ->expects($this->any())
            ->method('getPropertyPath')
            ->willReturn('uuid');

        $constraintViolations = new ConstraintViolationList([$constraintViolation]);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->isInstanceOf(UserRequestDTO::class))
            ->willReturn($constraintViolations);

        $response = $controller->getUserByUuid($user_uuid, $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertStringContainsString("Validation failed", $response->getContent());
    }

    public function testUpdateUserSuccess(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $user_uuid = '123e4567-e89b-12d3-a456-426614174000';
        $data = [
            'phone' => '+79998887766',
            'email' => 'user_new@example.com',
            'surname' => 'Иванов',
            'name' => 'Иван',
            'patronymic' => 'Иванович'
        ];

        $request = new Request([], [], [], [], [], [], json_encode($data));

        $userResponseDTO = new UserResponseDTO();
        $userResponseDTO->uuid = $user_uuid;

        $this->updateUserUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn($userResponseDTO);

        $response = $controller->updateUser($user_uuid, $request, $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testUpdateUserNotFound(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $user_uuid = '123e4567-e89b-12d3-a456-426614174000';

        $this->updateUserUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn(null);

        $response = $controller->updateUser($user_uuid, new Request(), $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testUpdateUserValidationError(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $user_uuid = 'invalid-uuid';

        $constraintViolation = $this->createMock(ConstraintViolation::class);
        $constraintViolation
            ->expects($this->any())
            ->method('getPropertyPath')
            ->willReturn('uuid');

        $constraintViolations = new ConstraintViolationList([$constraintViolation]);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn($constraintViolations);

        $response = $controller->updateUser($user_uuid, new Request(), $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertStringContainsString("Validation failed", $response->getContent());
    }

    public function testDeleteUserSuccess(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $user_uuid = '123e4567-e89b-12d3-a456-426614174000';

        $this->deleteUserUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new SuccessDTO(true));

        $response = $controller->deleteUser($user_uuid, $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(204, $response->getStatusCode());
    }

    public function testDeleteUserNotFound(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $user_uuid = '123e4567-e89b-12d3-a456-426614174000';

        $this->deleteUserUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn(null);

        $response = $controller->deleteUser($user_uuid, $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testDeleteUserValidationError(): void
    {
        $controller = new UserController(
            $this->createUserUseCase,
            $this->getUserUseCase,
            $this->updateUserUseCase,
            $this->deleteUserUseCase
        );

        $user_uuid = 'invalid-uuid';

        $constraintViolation = $this->createMock(ConstraintViolation::class);
        $constraintViolation
            ->expects($this->any())
            ->method('getPropertyPath')
            ->willReturn('uuid');

        $constraintViolations = new ConstraintViolationList([$constraintViolation]);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn($constraintViolations);

        $response = $controller->deleteUser($user_uuid, $this->validator);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertStringContainsString("Validation failed", $response->getContent());
    }
}
