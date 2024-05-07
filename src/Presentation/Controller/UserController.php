<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\ErrorDTO;
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
    public function createUser(Request $request, ValidatorInterface $validator): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);

            $requestDTO = new UserRequestDTO();
            $requestDTO->phone = $data['phone'] ?? null;
            $requestDTO->password = $data['password'] ?? null;
            $requestDTO->email = $data['email'] ?? null;
            $requestDTO->surname = $data['surname'] ?? null;
            $requestDTO->name = $data['name'] ?? null;
            $requestDTO->patronymic = $data['patronymic'] ?? null;
            $requestDTO->avatar = $data['avatar'] ?? null;
            $requestDTO->is_moderator = $data['is_moderator'] ?? null;

            $errors = $validator->validate($requestDTO);

            if (count($errors) > 0) {
                throw new \Exception("Validation failed", 400);
            }

            $responseDTO = $this->createUserUseCase->execute($requestDTO);

            return new JsonResponse($responseDTO, 201);
        } catch (\Exception $e) {
            $errorDTO = new ErrorDTO();
            $errorDTO->error_code = $e->getCode() ?: 500;
            $errorDTO->message = $e->getMessage() ?: "Internal Server Error";
            return new JsonResponse($errorDTO, $errorDTO->error_code);
        }
    }

    #[Route('/users/{user_uuid}', name: 'get_user', methods: ['GET'])]
    public function getUserByUuid(string $user_uuid, ValidatorInterface $validator): JsonResponse
    {
        try{
            $requestDTO = new UserRequestDTO();
            $requestDTO->uuid = $user_uuid;

            $errors = $validator->validate($requestDTO);

            foreach ($errors as $error) {
                if ($error->getPropertyPath() === 'uuid') {
                    throw new \Exception("Validation failed", 400);
                }
            }

            $responseDTO = $this->getUserUseCase->execute($requestDTO);

            if ($responseDTO === null) {
                throw new \Exception("User not found", 404);
            }

            return new JsonResponse($responseDTO, 200);
        } catch (\Exception $e) {
            $errorDTO = new ErrorDTO();
            $errorDTO->error_code = $e->getCode() ?: 500;
            $errorDTO->message = $e->getMessage() ?: "Internal Server Error";
            return new JsonResponse($errorDTO, $errorDTO->error_code);
        }
    }

    #[Route('/users/{user_uuid}', name: 'update_user', methods: ['PATCH'])]
    public function updateUser(string $user_uuid, Request $request, ValidatorInterface $validator): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);

            $requestDTO = new UserRequestDTO();
            $requestDTO->uuid = $user_uuid;
            $requestDTO->phone = $data['phone'] ?? null;
            $requestDTO->password = $data['password'] ?? null;
            $requestDTO->email = $data['email'] ?? null;
            $requestDTO->surname = $data['surname'] ?? null;
            $requestDTO->name = $data['name'] ?? null;
            $requestDTO->patronymic = $data['patronymic'] ?? null;
            $requestDTO->avatar = $data['avatar'] ?? null;
            $requestDTO->is_moderator = $data['is_moderator'] ?? null;

            $errors = $validator->validate($requestDTO);

            foreach ($errors as $error) {
                if ($error->getPropertyPath() === 'uuid') {
                    throw new \Exception("Validation failed", 400);
                }
            }

            $responseDTO = $this->updateUserUseCase->execute($requestDTO);

            if ($responseDTO === null) {
                throw new \Exception("User not found", 404);
            }
            return new JsonResponse($responseDTO, 200);
        } catch (\Exception $e){
            $errorDTO = new ErrorDTO();
            $errorDTO->error_code = $e->getCode() ?: 500;
            $errorDTO->message = $e->getMessage() ?: "Internal Server Error";
            return new JsonResponse($errorDTO, $errorDTO->error_code);
        }
    }

    #[Route('/users/{user_uuid}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(string $user_uuid, ValidatorInterface $validator): JsonResponse
    {
        try{
            $requestDTO = new UserRequestDTO();
            $requestDTO->uuid = $user_uuid;

            $errors = $validator->validate($requestDTO);

            foreach ($errors as $error) {
                if ($error->getPropertyPath() === 'uuid') {
                    throw new \Exception("Validation failed", 400);
                }
            }

            $responseDTO = $this->deleteUserUseCase->execute($requestDTO);

            if ($responseDTO === null) {
                throw new \Exception("User not found", 404);
            }

            return new JsonResponse($responseDTO, 204);
        } catch (\Exception $e){
            $errorDTO = new ErrorDTO();
            $errorDTO->error_code = $e->getCode() ?: 500;
            $errorDTO->message = $e->getMessage() ?: "Internal Server Error";
            return new JsonResponse($errorDTO, $errorDTO->error_code);
        }
    }
}
