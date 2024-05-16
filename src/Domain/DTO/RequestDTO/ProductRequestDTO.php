<?php

declare(strict_types=1);

namespace App\Domain\DTO\RequestDTO;
use App\Domain\Product\Category;
use App\Domain\Product\Product;
use Symfony\Component\Validator\Constraints as Assert;

class ProductRequestDTO
{
    #[Assert\NotBlank(message: "Name cannot be blank.")]
    public string $name;
    #[Assert\NotBlank(message: "Description cannot be blank.")]
    public string $code;
    #[Assert\NotBlank(message: "Description cannot be blank.")]
    public string $description;
    #[Assert\NotBlank(message: "Preview image cannot be blank.")]
    public string $previewImage;
    #[Assert\NotBlank(message: "Price cannot be blank.")]
    #[Assert\Type(type: 'float', message: "Price should be a float.")]
    #[Assert\GreaterThanOrEqual(value: 0, message: "Price should be greater than or equal to 0.")]
    public float $price;
    public ?string $categoryUuid = null;

    public static function toDTO(array $data): ?ProductRequestDTO
    {
        $dto = new ProductRequestDTO();
        $dto->name = $data['name'];
        $dto->code = $data['code'];
        $dto->description = $data['description'];
        $dto->previewImage = $data['preview_image'];
        $dto->price = $data['price'];
        $dto->categoryUuid = $data['category_uuid'] ?? null;

        return $dto;
    }

    public static function toEntity(Product $product, ProductRequestDTO $dto, ?Category $category): Product
    {
        $product->setName($dto->name);
        $product->setCode($dto->code);
        $product->setDescription($dto->description);
        $product->setPreviewImage($dto->previewImage);
        $product->setPrice((float)$dto->price);
        if ($category) {
            $product->setCategory($category);
        }

        return $product;
    }
}