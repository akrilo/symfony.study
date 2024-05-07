<?php

namespace App\Application\DTO\RequestDTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserRequestDTO
{
    #[Assert\Uuid(message: "Invalid UUID format.")]
    public ?string $uuid = null;

    #[Assert\NotBlank(message: "Phone cannot be blank.")]
    #[Assert\Length(min: 10, max: 15)]
    public ?string $phone = null;

    #[Assert\NotBlank(message: "Password cannot be blank.")]
    public ?string $password = null;

    #[Assert\Email(message: "Invalid email address.")]
    public ?string $email = null;

    #[Assert\NotBlank(message: "Surname cannot be blank.")]
    public ?string $surname = null;

    #[Assert\NotBlank(message: "Name cannot be blank.")]
    public ?string $name = null;

    public ?string $patronymic = null;

    public ?string $avatar = null;

    public ?bool $is_moderator = null;
}
