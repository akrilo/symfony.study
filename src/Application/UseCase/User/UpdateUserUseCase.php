<?php

namespace App\Application\UseCase\User;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\UserResponseDTO;
use App\Domain\Service\UserService;

class UpdateUserUseCase
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function execute(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        return $this->userService->updateUser($requestDTO);
    }
}