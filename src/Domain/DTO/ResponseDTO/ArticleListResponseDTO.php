<?php

declare(strict_types=1);

namespace App\Domain\DTO\ResponseDTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ArticleListResponseDTO implements \JsonSerializable
{
    public Collection $list;

    public function __construct()
    {
        $this->list = new ArrayCollection();
    }

    public function addArticle(ArticleResponseDTO $article): void
    {
        $this->list->add($article);
    }

    public static function toDTO(Collection $list): ?ArticleListResponseDTO
    {
        $dto = new ArticleListResponseDTO();
        $dto->list = $list;

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return [
            'list' => $this->list->toArray(),
        ];
    }
}