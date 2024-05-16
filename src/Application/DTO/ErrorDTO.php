<?php

declare(strict_types=1);

namespace App\Application\DTO;

use Exception;

class ErrorDTO
{
    public int $errorCode;
    public string $message;

    public static function toDTO(Exception $e): ?ErrorDTO
    {
        $dto = new ErrorDTO();
        $dto->errorCode = $e->getCode() ?: 500;
        $dto->message = $e->getMessage() ?: "Internal Server Error";

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return [
            'error_code' => $this->errorCode,
            'message' => $this->message,
        ];
    }
}