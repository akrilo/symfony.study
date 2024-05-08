<?php

namespace App\Application\UseCase\User;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\ErrorDTO;
use App\Application\DTO\ResponseDTO\UserResponseDTO;
use App\Application\DTOMapper\ErrorMapper;
use App\Domain\Service\UserService;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserUseCase
{
    private UserService $userService;
    private ValidatorInterface $validator;

    public function __construct(UserService $userService, ValidatorInterface $validator)
    {
        $this->userService = $userService;
        $this->validator = $validator;
    }

    public function execute(UserRequestDTO $requestDTO): UserResponseDTO|ErrorDTO
    {
        try{
            $errors = $this->validator->validate($requestDTO);

            if (count($errors) > 0) {
                throw new \Exception("Validation failed", 400);
            }

            return $this->userService->createUser($requestDTO);
        } catch (\Exception $e) {
            return ErrorMapper::toDTO($e);
        }
    }
}