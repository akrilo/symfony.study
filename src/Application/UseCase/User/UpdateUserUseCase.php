<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Application\DTO\ErrorDTO;
use App\Application\Exception\NotFoundException;
use App\Application\Exception\ValidationException;
use App\Domain\DTO\RequestDTO\UserRequestDTO;
use App\Domain\DTO\ResponseDTO\UserResponseDTO;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class UpdateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) { }

    public function execute(string $uuid, UserRequestDTO $requestDTO): UserResponseDTO|ErrorDTO
    {
        try{
            if (!Uuid::isValid($uuid)) {
                throw new ValidationException;
            }

            $responseDTO = $this->userRepository->updateById($uuid, $requestDTO);
            if ($responseDTO === null) {
                throw new NotFoundException;
            }
            return $responseDTO;
        } catch (ValidationException | NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}