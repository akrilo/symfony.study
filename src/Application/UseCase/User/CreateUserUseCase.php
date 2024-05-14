<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Application\DTO\ErrorDTO;
use App\Domain\DTO\RequestDTO\UserRequestDTO;
use App\Domain\DTO\ResponseDTO\UserResponseDTO;
use App\Domain\Exception\ValidationException;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ValidatorInterface $validator
    ) { }

    public function execute(UserRequestDTO $requestDTO): UserResponseDTO|ErrorDTO
    {
        try{
            $errors = $this->validator->validate($requestDTO);

            if (count($errors) > 0) {
                throw new ValidationException;
            }

            return $this->userRepository->persist($requestDTO);
        } catch (ValidationException $e) {
            return ErrorDTO::toDTO($e);
        }

    }
}