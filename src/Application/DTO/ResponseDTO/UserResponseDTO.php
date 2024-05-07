<?php

namespace App\Application\DTO\ResponseDTO;

class UserResponseDTO
{
    public string $uuid;
    public string $phone;
    public ?string $email;
    public string $surname;
    public string $name;
    public ?string $patronymic;
    public ?string $avatar;
    public bool $is_moderator;
    public string $created_at;
    public string $updated_at;
}