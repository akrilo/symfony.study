<?php

declare(strict_types=1);

namespace App\Domain\DTO\RequestDTO;

use App\Domain\User\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserRequestDTO
{
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

    public ?bool $isModerator = null;

    public static function toDTO(array $data): ?UserRequestDTO
    {
        $dto = new UserRequestDTO();
        $dto->phone = $data['phone'] ?? null;
        $dto->password = $data['password'] ?? null;
        $dto->email = $data['email'] ?? null;
        $dto->surname = $data['surname'] ?? null;
        $dto->name = $data['name'] ?? null;
        $dto->patronymic = $data['patronymic'] ?? null;
        $dto->avatar = $data['avatar'] ?? null;
        $dto->isModerator = $data['is_moderator'] ?? null;

        return $dto;
    }

    public static function toEntity(User $user, UserRequestDTO $dto): User
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

        if (isset($dto->isModerator)) {
            $user->setIsModerator($dto->isModerator);
        }

        return $user;
    }
}
