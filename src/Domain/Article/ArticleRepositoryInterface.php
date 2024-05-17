<?php

declare(strict_types=1);

namespace App\Domain\Article;

use App\Domain\DTO\RequestDTO\ArticleRequestDTO;
use App\Domain\DTO\ResponseDTO\ArticleListResponseDTO;
use App\Domain\DTO\ResponseDTO\ArticleResponseDTO;

interface ArticleRepositoryInterface
{
    public function findByUuid(string $uuid): ?ArticleResponseDTO;
    public function persist(ArticleRequestDTO $requestDTO): ?ArticleResponseDTO;
    public function updateByUuid(string $uuid, ArticleRequestDTO $requestDTO): ?ArticleResponseDTO;
    public function removeByUuid(string $uuid): ?bool;
    public function getList(): ?ArticleListResponseDTO;

}