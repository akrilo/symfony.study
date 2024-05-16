<?php

declare(strict_types=1);

namespace App\Domain\DTO\ResponseDTO;

use App\Domain\Product\Category;

class CategoryDTO
{
    public string $uuid;
    public string $name;

    public static function toDTO(?Category $category): ?CategoryDTO
    {
        if ($category === null) {
            return null;
        }

        $dto = new CategoryDTO();
        $dto->uuid = (string)$category->getUuid();
        $dto->name = $category->getName();

        return $dto;
    }
}