<?php

namespace App\Application\UseCase\User;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\ErrorDTO;
use App\Application\DTO\ResponseDTO\SuccessDTO;
use App\Application\DTOMapper\ErrorMapper;
use App\Domain\Service\UserService;
use PHPUnit\TestFixture\Success;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DeleteUserUseCase
{
    private UserService $userService;
    private ValidatorInterface $validator;


    public function __construct(UserService $userService, ValidatorInterface $validator)
    {
        $this->userService = $userService;
        $this->validator = $validator;
    }

    public function execute(UserRequestDTO $requestDTO): SuccessDTO|ErrorDTO
    {
        try{
            $errors = $this->validator->validate($requestDTO);

            foreach ($errors as $error) {
                if ($error->getPropertyPath() === 'uuid') {
                    throw new \Exception("Validation failed", 400);
                }
            }

            $responseDTO = $this->userService->deleteUser($requestDTO);
            if ($responseDTO === null) {
                throw new \Exception("User not found", 404);
            }
            return $responseDTO;
        } catch (\Exception $e) {
            return ErrorMapper::toDTO($e);
        }
    }
}