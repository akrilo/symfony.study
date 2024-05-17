<?php

declare(strict_types=1);

namespace App\Application\UseCase\Favorite;

use App\Application\DTO\ErrorDTO;
use App\Domain\DTO\ResponseDTO\FavoriteResponseDTO;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class GetFavoriteUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) { }

    public function execute(string $userUuid): FavoriteResponseDTO|ErrorDTO
    {
        try{
            if (!Uuid::isValid($userUuid)) {
                throw new ValidationException;
            }

            $response = $this->userRepository->findFavorite($userUuid);

            if ($response === null) {
                throw new NotFoundException;
            }

            return $response;
        } catch (ValidationException|NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}