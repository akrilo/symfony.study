<?php

namespace App\Infrastructure\Repository;

use App\Application\DTO\RequestDTO\UserRequestDTO;
use App\Application\DTO\ResponseDTO\SuccessDTO;
use App\Application\DTO\ResponseDTO\UserResponseDTO;
use App\Application\DTOMapper\SuccessMapper;
use App\Application\DTOMapper\UserResponseMapper;
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
        $user = UserResponseMapper::toEntity($requestDTO, $user);

        $now = new \DateTimeImmutable();
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return UserResponseMapper::toDTO($user);
    }

    public function getUser(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        $user = $this->findOneBy(['uuid' => $requestDTO->uuid]);

        if (!$user) {
            return null;
        }

        return UserResponseMapper::toDTO($user);
    }

    public function updateUser(UserRequestDTO $requestDTO): ?UserResponseDTO
    {
        $user = $this->findOneBy(['uuid' => $requestDTO->uuid]);

        if (!$user) {
            return null;
        }

        $user = UserResponseMapper::toEntity($requestDTO, $user);

        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return UserResponseMapper::toDTO($user);
    }

    public function deleteUser(UserRequestDTO $requestDTO): ?SuccessDTO
    {
        $user = $this->findOneBy(['uuid' => $requestDTO->uuid]);

        if (!$user) {
            return null;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return SuccessMapper::toDTO();
    }
}
