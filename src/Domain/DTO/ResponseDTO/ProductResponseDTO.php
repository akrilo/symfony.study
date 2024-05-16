<?php

declare(strict_types=1);

namespace App\Domain\DTO\ResponseDTO;



use App\Domain\Product\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;


class ProductResponseDTO implements JsonSerializable
{
    public string $uuid = '';
    public string $name = '';
    public string $code = '';
    public string $description = '';
    public string $previewImage = '';
    public float $price = 0.0;
    public ?CategoryDTO $category = null;
    public ?Collection $gallery = null;
    public ?Collection $files = null;
    public ?Collection $articles = null;
    public bool $isFavorite = false;
    public function __construct()
    {
        $this->gallery = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public static function toDTO(Product $product): ?ProductResponseDTO
    {
        $dto = new ProductResponseDTO();
        $dto->uuid = (string)$product->getUuid();
        $dto->name = $product->getName();
        $dto->code = $product->getCode();
        $dto->description = $product->getDescription();
        $dto->previewImage = $product->getPreviewImage();
        $dto->price = $product->getPrice();
        $dto->category = CategoryDTO::toDTO($product->getCategory());
        foreach ($product->getGalleries() as $gallery) {
            $dto->gallery->add(FileDTO::toDTO($gallery));
        }
        foreach ($product->getFiles() as $file) {
            $dto->files->add(FileDTO::toDTO($file));
        }
        foreach ($product->getArticles() as $article) {
            $dto->articles->add(ArticleDTO::toDTO($article));
        }
        $dto->isFavorite = $product->getUsers()->count() > 0;

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'preview_image' => $this->previewImage,
            'price' => $this->price,
            'category' => $this->category,
            'gallery' => $this->gallery->toArray(),
            'files' => $this->files->toArray(),
            'articles' => $this->articles->toArray(),
            'is_favorite' => $this->isFavorite,
        ];
    }
}