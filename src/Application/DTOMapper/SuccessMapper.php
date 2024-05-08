<?php

namespace App\Application\DTOMapper;

use App\Application\DTO\ResponseDTO\SuccessDTO;

class SuccessMapper
{
    public static function toDTO(): ?SuccessDTO
    {
        $dto = new SuccessDTO();
        $dto->success = true;

        return $dto;
    }
}