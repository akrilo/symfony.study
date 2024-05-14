<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DTO\RequestDTO\UserRequestDTO;
use App\Domain\DTO\ResponseDTO\UserResponseDTO;

interface UserRepositoryInterface
{
    public function findById(string $uuid): ?UserResponseDTO;

    public function persist(UserRequestDTO $requestDTO): ?UserResponseDTO;

    public function updateById(string $uuid, UserRequestDTO $requestDTO): ?UserResponseDTO;

    public function removeById(string $uuid): ?bool;
}