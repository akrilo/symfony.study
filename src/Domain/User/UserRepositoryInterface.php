<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DTO\RequestDTO\UserRequestDTO;
use App\Domain\DTO\ResponseDTO\FavoriteResponseDTO;
use App\Domain\DTO\ResponseDTO\UserResponseDTO;

interface UserRepositoryInterface
{
    public function findByUuid(string $uuid): ?UserResponseDTO;
    public function persist(UserRequestDTO $requestDTO): ?UserResponseDTO;
    public function updateByUuid(string $uuid, UserRequestDTO $requestDTO): ?UserResponseDTO;
    public function removeByUuid(string $uuid): ?bool;

    public function persistFavorite(string $userUuid, string $productUuid): ?bool;
    public function removeFavorite(string $userUuid, string $productUuid): ?bool;
    public function findFavorite(string $userUuid): ?FavoriteResponseDTO;

}