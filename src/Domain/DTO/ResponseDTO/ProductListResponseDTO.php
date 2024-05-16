<?php

declare(strict_types=1);

namespace App\Domain\DTO\ResponseDTO;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

class ProductListResponseDTO implements JsonSerializable
{
    public PaginationDTO $pagination;
    public Collection $list;

    public function __construct()
    {
        $this->list = new ArrayCollection();
    }

    public function addProduct(ProductResponseDTO $product): void
    {
        $this->list->add($product);
    }

    public static function toDTO(PaginationDTO $pagination, Collection $list): ?ProductListResponseDTO
    {
        $dto = new ProductListResponseDTO();
        $dto->pagination = $pagination;
        $dto->list = $list;

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return [
            'pagination' => $this->pagination,
            'list' => $this->list->toArray(),
        ];
    }
}