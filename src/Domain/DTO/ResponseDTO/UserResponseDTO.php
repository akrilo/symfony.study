<?php

declare(strict_types=1);

namespace App\Domain\DTO\ResponseDTO;

use App\Domain\User\User;

class UserResponseDTO
{
    public string $uuid;
    public string $phone;
    public ?string $email;
    public string $surname;
    public string $name;
    public ?string $patronymic;
    public ?string $avatar;
    public bool $isModerator;
    public string $createdAt;
    public string $updatedAt;

    public static function toDTO(User $user): ?UserResponseDTO
    {
        $dto = new UserResponseDTO();
        $dto->uuid = (string)$user->getUuid();
        $dto->phone = $user->getPhone();
        $dto->email = $user->getEmail();
        $dto->surname = $user->getSurname();
        $dto->name = $user->getName();
        $dto->patronymic = $user->getPatronymic();
        $dto->avatar = $user->getAvatar();
        $dto->isModerator = $user->getIsModerator();
        $dto->createdAt = $user->getCreatedAt()->format('Y-m-d\TH:i:s\Z');
        $dto->updatedAt = $user->getUpdatedAt()->format('Y-m-d\TH:i:s\Z');

        return $dto;
    }
}