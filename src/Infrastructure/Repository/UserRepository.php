<?php

namespace App\Infrastructure\Repository;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\SuccessDTO;
use App\Application\DTO\ResponseDTO\UserResponseDTO;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function createUser(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        $user = new User();
        $user->setPhone($requestDTO->phone);
        $user->setPassword($requestDTO->password);
        $user->setEmail($requestDTO->email);
        $user->setSurname($requestDTO->surname);
        $user->setName($requestDTO->name);
        $user->setPatronymic($requestDTO->patronymic);
        $user->setAvatar($requestDTO->avatar);
        $user->setIsModerator($requestDTO->is_moderator);

        $now = new \DateTimeImmutable();
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->convertToResponseDTO($user);
    }

    public function getUser(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        $user = $this->findOneBy(['uuid' => $requestDTO->uuid]);

        if (!$user) {
            return null;
        }

        return $this->convertToResponseDTO($user);
    }

    public function updateUser(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        $user = $this->findOneBy(['uuid' => $requestDTO->uuid]);

        if (!$user) {
            return null;
        }

        if (isset($requestDTO->phone)) {
            $user->setPhone($requestDTO->phone);
        }

        if (isset($requestDTO->password)) {
            $user->setPassword($requestDTO->password);
        }

        if (isset($requestDTO->email)) {
            $user->setEmail($requestDTO->email);
        }

        if (isset($requestDTO->surname)) {
            $user->setSurname($requestDTO->surname);
        }

        if (isset($requestDTO->name)) {
            $user->setName($requestDTO->name);
        }

        if (isset($requestDTO->patronymic)) {
            $user->setPatronymic($requestDTO->patronymic);
        }

        if (isset($requestDTO->avatar)) {
            $user->setAvatar($requestDTO->avatar);
        }

        if (isset($requestDTO->is_moderator)) {
            $user->setIsModerator($requestDTO->is_moderator);
        }

        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return $this->convertToResponseDTO($user);
    }

    public function deleteUser(UserRequestDTO $requestDTO): ?SuccessDTO
    {
        $user = $this->findOneBy(['uuid' => $requestDTO->uuid]);

        if (!$user) {
            return null;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $successDTO = new SuccessDTO();
        $successDTO->success = true;

        return $successDTO;
    }

    private function convertToResponseDTO(User $user): UserResponseDTO
    {
        $userDTO = new UserResponseDTO();

        $userDTO->uuid = $user->getUuid();
        $userDTO->phone = $user->getPhone();
        $userDTO->email = $user->getEmail();
        $userDTO->surname = $user->getSurname();
        $userDTO->name = $user->getName();
        $userDTO->patronymic = $user->getPatronymic();
        $userDTO->avatar = $user->getAvatar();
        $userDTO->created_at = $user->getCreatedAt()->format('Y-m-d\TH:i:s\Z');
        $userDTO->updated_at = $user->getUpdatedAt()->format('Y-m-d\TH:i:s\Z');
        $userDTO->is_moderator = $user->getIsModerator();

        return $userDTO;
    }
}
