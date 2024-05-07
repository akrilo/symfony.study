<?php

namespace App\Application\UseCase\User;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\SuccessDTO;
use App\Domain\Service\UserService;
use PHPUnit\TestFixture\Success;

class DeleteUserUseCase
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function execute(UserRequestDTO $requestDTO): ?SuccessDTO
    {
        return $this->userService->deleteUser($requestDTO);
    }
}