<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\DTO\RequestDTO\UserRequestDTO;
use App\Domain\DTO\ResponseDTO\UserResponseDTO;
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

    public function persist(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        $user = UserRequestDTO::toEntity(new User(), $requestDTO);

        $now = new \DateTimeImmutable();
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return UserResponseDTO::toDTO($user);
    }

    public function findById(string $uuid): ?UserResponseDTO
    {
        $user = $this->findOneBy(['uuid' => $uuid]);

        if (!$user) {
            return null;
        }

        return UserResponseDTO::toDTO($user);
    }

    public function updateById(string $uuid, UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        $user = $this->findOneBy(['uuid' => $uuid]);

        if (!$user) {
            return null;
        }

        $user = UserRequestDTO::toEntity($user, $requestDTO);

        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return UserResponseDTO::toDTO($user);
    }

    public function removeById(string $uuid): ?bool
    {
        $user = $this->findOneBy(['uuid' => $uuid]);

        if (!$user) {
            return null;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return true;
    }
}
