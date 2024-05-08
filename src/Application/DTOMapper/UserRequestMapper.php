<?php

namespace App\Application\DTOMapper;

use App\Application\DTO\RequestDTO\UserRequestDTO;

class UserRequestMapper
{
    public static function toDTO(mixed $data, string $user_uuid = null): ?UserRequestDTO
    {
        $dto = new UserRequestDTO();
        $dto->uuid = $user_uuid;
        $dto->phone = $data['phone'] ?? null;
        $dto->password = $data['password'] ?? null;
        $dto->email = $data['email'] ?? null;
        $dto->surname = $data['surname'] ?? null;
        $dto->name = $data['name'] ?? null;
        $dto->patronymic = $data['patronymic'] ?? null;
        $dto->avatar = $data['avatar'] ?? null;
        $dto->is_moderator = $data['is_moderator'] ?? null;

        return $dto;
    }
}