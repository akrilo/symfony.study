<?php

namespace App\Domain\User;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\SuccessDTO;
use App\Application\DTO\ResponseDTO\UserResponseDTO;

interface UserRepositoryInterface
{
    public function getUser(UserRequestDTO $requestDTO): ?UserResponseDTO;

    public function createUser(UserRequestDTO $requestDTO): ?UserResponseDTO;

    public function updateUser(UserRequestDTO $requestDTO): ?UserResponseDTO;

    public function deleteUser(UserRequestDTO $requestDTO): ?SuccessDTO;
}