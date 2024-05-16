<?php

declare(strict_types=1);

namespace App\Domain\DTO\ResponseDTO;

class FileDTO
{
    public string $uuid;
    public string $name;
    public string $url;

    public static function toDTO($gallery): FileDTO
    {
        $dto = new FileDTO();
        $dto->uuid = (string)$gallery->getUuid();
        $dto->name = $gallery->getName();
        $dto->url = $gallery->getUrl();

        return $dto;
    }
}