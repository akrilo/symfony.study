<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Application\DTO\ErrorDTO;
use App\Application\DTO\SuccessDTO;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class DeleteUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) { }

    public function execute(string $uuid): SuccessDTO|ErrorDTO
    {
        try{
            if (!Uuid::isValid($uuid)) {
                throw new ValidationException;
            }

            $response = $this->userRepository->removeById($uuid);

            if ($response === null) {
                throw new NotFoundException;
            }

            return SuccessDTO::toDTO($response);
        } catch (ValidationException | NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}