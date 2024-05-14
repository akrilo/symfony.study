<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\UseCase\User\CreateUserUseCase;
use App\Application\UseCase\User\DeleteUserUseCase;
use App\Application\UseCase\User\GetUserUseCase;
use App\Application\UseCase\User\UpdateUserUseCase;
use App\Domain\DTO\RequestDTO\UserRequestDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class UserController extends AbstractController
{
    public function __construct(
        private CreateUserUseCase $createUserUseCase,
        private GetUserUseCase $getUserUseCase,
        private UpdateUserUseCase $updateUserUseCase,
        private DeleteUserUseCase $deleteUserUseCase,

    ) { }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $requestDTO = UserRequestDTO::toDTO($data);

        $responseDTO = $this->createUserUseCase->execute($requestDTO);
        $statusCode = $responseDTO->errorCode ?? 201;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/users/{uuid}', name: 'get_user', methods: ['GET'])]
    public function getUserByUuid(string $uuid): JsonResponse
    {
        $responseDTO = $this->getUserUseCase->execute($uuid);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/users/{uuid}', name: 'update_user', methods: ['PATCH'])]
    public function updateUser(string $uuid, Request $request): JsonResponse
    {
        $data = $request->toArray();
        $requestDTO = UserRequestDTO::toDTO($data);

        $responseDTO = $this->updateUserUseCase->execute($uuid, $requestDTO);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/users/{uuid}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(string $uuid): JsonResponse
    {
        $responseDTO = $this->deleteUserUseCase->execute($uuid);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }
}
