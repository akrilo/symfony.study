<?php

declare(strict_types=1);

namespace App\Application\DTO;

class SuccessDTO
{
    public bool $success;

    public static function toDTO(bool $success = false): ?SuccessDTO
    {
        $dto = new SuccessDTO();
        $dto->success = $success;

        return $dto;
    }
}