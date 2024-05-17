<?php

declare(strict_types=1);

namespace App\Domain\DTO\RequestDTO;

use App\Domain\Article\Article;
use App\Domain\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleRequestDTO
{
    #[Assert\NotBlank(message: "Name cannot be blank.")]
    public ?string $name = null;
    #[Assert\NotBlank(message: "Code cannot be blank.")]
    public ?string $code = null;
    #[Assert\NotBlank(message: "Text cannot be blank.")]
    public ?string $text = null;
    #[Assert\NotBlank(message: "User's uuid cannot be blank.")]
    public string $userUuid;
    #[Assert\NotBlank(message: "Product's uuids cannot be blank.")]
    public ?Collection $productUuids = null;
    public static function toDTO(array $data): ?ArticleRequestDTO
    {
        $dto = new ArticleRequestDTO();
        $dto->name = $data['name'] ?? null;
        $dto->code = $data['code'] ?? null;
        $dto->text = $data['text'] ?? null;
        $dto->userUuid = $data['user_uuid'];
        if(isset($data['product_uuids'])) {
            $dto->productUuids = new ArrayCollection($data['product_uuids']) ?? null;
        }

        return $dto;
    }

    public static function toEntity(Article $article, ArticleRequestDTO $dto, ?User $user, ?Collection $products): Article
    {
        if (isset($dto->name)) {
            $article->setName($dto->name);
        }
        if (isset($dto->code)) {
            $article->setCode($dto->code);
        }
        if (isset($dto->text)) {
            $article->setText($dto->text);
        }
        $article->setUser($user);
        if ($products) {
            foreach ($products as $product) {
                $article->addProduct($product);
            }
        }

        return $article;
    }
}