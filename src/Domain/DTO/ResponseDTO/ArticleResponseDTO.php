<?php

declare(strict_types=1);

namespace App\Domain\DTO\ResponseDTO;

use App\Domain\Article\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ArticleResponseDTO implements \JsonSerializable
{
    public string $uuid;
    public string $name;
    public string $code;
    public string $text;
    public string $createdAt = '';
    public string $updatedAt = '';
    public string $userUuid = '';
    public Collection $products;
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
    public static function toDTO(Article $article): ArticleResponseDTO
    {
        $dto = new ArticleResponseDTO();
        $dto->uuid = (string)$article->getUuid();
        $dto->name = $article->getName();
        $dto->code = $article->getCode();
        $dto->text = $article->getText();
        $dto->createdAt = $article->getCreatedAt()->format('Y-m-d\TH:i:s\Z');
        $dto->updatedAt = $article->getUpdatedAt()->format('Y-m-d\TH:i:s\Z');
        $dto->userUuid = (string)$article->getUser()->getUuid();
        foreach ($article->getProducts() as $product) {
            $dto->products->add(ProductResponseDTO::toDTO($product));
        }

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'code' => $this->code,
            'text' => $this->text,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'user_uuid' => $this->userUuid,
            'products' => $this->products->toArray(),
        ];
    }
}