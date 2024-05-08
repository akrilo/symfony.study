<?php

namespace App\Application\DTOMapper;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\UserResponseDTO;
use App\Domain\User\User;

class UserResponseMapper
{
    public static function toDTO(User $user): ?UserResponseDTO
    {
        $dto = new UserResponseDTO();
        $dto->uuid = $user->getUuid();
        $dto->phone = $user->getPhone();
        $dto->email = $user->getEmail();
        $dto->surname = $user->getSurname();
        $dto->name = $user->getName();
        $dto->patronymic = $user->getPatronymic();
        $dto->avatar = $user->getAvatar();
        $dto->is_moderator = $user->getIsModerator();
        $dto->created_at = $user->getCreatedAt()->format('Y-m-d\TH:i:s\Z');
        $dto->updated_at = $user->getUpdatedAt()->format('Y-m-d\TH:i:s\Z');

        return $dto;
    }

    public static function toEntity(UserRequestDTO $dto, User $user): ?User
    {
        if (isset($dto->phone)) {
            $user->setPhone($dto->phone);
        }

        if (isset($dto->password)) {
            $user->setPassword($dto->password);
        }

        if (isset($dto->email)) {
            $user->setEmail($dto->email);
        }

        if (isset($dto->surname)) {
            $user->setSurname($dto->surname);
        }

        if (isset($dto->name)) {
            $user->setName($dto->name);
        }

        if (isset($dto->patronymic)) {
            $user->setPatronymic($dto->patronymic);
        }

        if (isset($dto->avatar)) {
            $user->setAvatar($dto->avatar);
        }

        if (isset($dto->is_moderator)) {
            $user->setIsModerator($dto->is_moderator);
        }

        return $user;
    }
}