<?php

namespace App\Domain\DTO\ResponseDTO;

use App\Domain\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FavoriteResponseDTO implements \JsonSerializable
{
    public string $uuid;
    public ?Collection $list = null;
    public function __construct()
    {
        $this->list = new ArrayCollection();
    }

    public static function toDTO(User $user): FavoriteResponseDTO
    {
        $dto = new FavoriteResponseDTO();
        $dto->uuid = (string)$user->getUuid();
        foreach ($user->getFavoriteProducts() as $product) {
            $dto->list->add(ProductResponseDTO::toDTO($product));
        }

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'list' => $this->list->toArray(),
        ];
    }
}