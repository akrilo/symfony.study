<?php

declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\DTO\RequestDTO\ProductListRequestDTO;
use App\Domain\DTO\RequestDTO\ProductRequestDTO;
use App\Domain\DTO\ResponseDTO\ProductListResponseDTO;
use App\Domain\DTO\ResponseDTO\ProductResponseDTO;

interface ProductRepositoryInterface
{
    public function findByUuid(string $uuid): ?ProductResponseDTO;
    public function persist(ProductRequestDTO $requestDTO): ?ProductResponseDTO;
    public function getList(ProductListRequestDTO $requestDTO): ?ProductListResponseDTO;
    public function removeByUuid(string $uuid): ?bool;
}