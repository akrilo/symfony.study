<?php

namespace App\Application\DTOMapper;

use App\Application\DTO\ResponseDTO\ErrorDTO;

class ErrorMapper
{
    public static function toDTO(\Exception $e): ?ErrorDTO
    {
        $dto = new ErrorDTO();
        $dto->error_code = $e->getCode() ?: 500;
        $dto->message = $e->getMessage() ?: "Internal Server Error";

        return $dto;
    }
}