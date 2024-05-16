<?php

namespace App\Domain\DTO\ResponseDTO;

class PaginationDTO
{
    public int $currentPage;
    public int $pages;
    public int $pageSize;

    public static function toDTO(int $currentPage, int $pages, int $pageSize): PaginationDTO
    {
        $dto = new PaginationDTO();
        $dto->currentPage = $currentPage;
        $dto->pages = $pages;
        $dto->pageSize = $pageSize;

        return $dto;
    }
}