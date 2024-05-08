<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\ErrorDTO;
use App\Application\DTOMapper\UserRequestMapper;
use App\Application\UseCase\User\CreateUserUseCase;
use App\Application\UseCase\User\DeleteUserUseCase;
use App\Application\UseCase\User\GetUserUseCase;
use App\Application\UseCase\User\UpdateUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private CreateUserUseCase $createUserUseCase;
    private GetUserUseCase $getUserUseCase;
    private UpdateUserUseCase $updateUserUseCase;
    private DeleteUserUseCase $deleteUserUseCase;


    public function __construct(
        CreateUserUseCase $createUserUseCase,
        GetUserUseCase $getUserUseCase,
        UpdateUserUseCase $updateUserUseCase,
        DeleteUserUseCase $deleteUserUseCase,

    ) {
        $this->createUserUseCase = $createUserUseCase;
        $this->getUserUseCase = $getUserUseCase;
        $this->updateUserUseCase = $updateUserUseCase;
        $this->deleteUserUseCase = $deleteUserUseCase;

    }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $requestDTO = UserRequestMapper::toDTO($data);

        $responseDTO = $this->createUserUseCase->execute($requestDTO);
        $statusCode = $responseDTO->error_code ?? 201;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/users/{user_uuid}', name: 'get_user', methods: ['GET'])]
    public function getUserByUuid(string $user_uuid): JsonResponse
    {
        $requestDTO = UserRequestMapper::toDTO( null, $user_uuid);

        $responseDTO = $this->getUserUseCase->execute($requestDTO);
        $statusCode = $responseDTO->error_code ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/users/{user_uuid}', name: 'update_user', methods: ['PATCH'])]
    public function updateUser(string $user_uuid, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $requestDTO = UserRequestMapper::toDTO($data, $user_uuid);

        $responseDTO = $this->updateUserUseCase->execute($requestDTO);
        $statusCode = $responseDTO->error_code ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/users/{user_uuid}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(string $user_uuid): JsonResponse
    {
        $requestDTO = new UserRequestDTO();
        $requestDTO->uuid = $user_uuid;

        $responseDTO = $this->deleteUserUseCase->execute($requestDTO);
        $statusCode = $responseDTO->error_code ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }
}
