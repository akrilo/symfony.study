<?php

namespace App\Domain\Service;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\SuccessDTO;
use App\Application\DTO\ResponseDTO\UserResponseDTO;
use App\Domain\User\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        return $this->userRepository->getUser($requestDTO);
    }

    public function createUser(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        return $this->userRepository->createUser($requestDTO);

    }

    public function updateUser(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        return $this->userRepository->updateUser($requestDTO);
    }

    public function deleteUser(UserRequestDTO $requestDTO): ?SuccessDTO
    {
        return $this->userRepository->deleteUser($requestDTO);
    }

}