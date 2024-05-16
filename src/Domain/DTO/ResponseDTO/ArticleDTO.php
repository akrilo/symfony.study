<?php

declare(strict_types=1);

namespace App\Domain\DTO\ResponseDTO;

class ArticleDTO
{
    public string $uuid;
    public string $name;
    public string $code;
    public string $text;
    public string $userUuid;
    public string $createdAt;
    public string $updatedAt;

    public static function toDTO($article): ArticleDTO
    {
        $dto = new ArticleDTO();
        $dto->uuid = (string)$article->getUuid();
        $dto->name = $article->getName();
        $dto->code = $article->getCode();
        $dto->text = $article->getText();
        $dto->userUuid = (string)$article->getUser()->getUuid();
        $dto->createdAt = $article->getCreatedAt()->format('Y-m-d\TH:i:s\Z');
        $dto->updatedAt = $article->getUpdatedAt()->format('Y-m-d\TH:i:s\Z');

        return $dto;
    }
}