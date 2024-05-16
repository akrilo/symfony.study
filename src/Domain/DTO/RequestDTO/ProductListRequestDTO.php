<?php

declare(strict_types=1);

namespace App\Domain\DTO\RequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

class ProductListRequestDTO
{
    #[Assert\GreaterThan(value: 0, message: "Page should be greater than 0.")]
    public int $page;
    #[Assert\GreaterThan(value: 0, message: "Limit should be greater than 0.")]
    public int $limit;
    #[Assert\Choice(choices: ['name', 'price'], message: "Sort by should be either 'name' or 'price'.")]
    public ?string $sortBy = null;
    #[Assert\Choice(choices: ['asc', 'desc'], message: "Sort order should be either 'asc' or 'desc'.")]
    public ?string $sortOrder = null;

    public static function toDTO(int $page, int $limit, ?string $sortBy, ?string $sortOrder): ?ProductListRequestDTO
    {
        $dto = new ProductListRequestDTO();
        $dto->page = $page;
        $dto->limit = $limit;
        $dto->sortBy = $sortBy ?? null;
        $dto->sortOrder = $sortOrder ?? null;

        return $dto;
    }
}