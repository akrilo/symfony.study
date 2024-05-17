<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Application\DTO\ErrorDTO;
use App\Domain\DTO\ResponseDTO\UserResponseDTO;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class GetUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) { }

    public function execute(string $uuid): UserResponseDTO|ErrorDTO
    {
        try{
            if (!Uuid::isValid($uuid)) {
                throw new ValidationException;
            }

            $responseDTO = $this->userRepository->findByUuid($uuid);

            if ($responseDTO === null) {
                throw new NotFoundException;
            }

            return $responseDTO;
        } catch (ValidationException | NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}