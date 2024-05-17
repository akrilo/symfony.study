<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\DTO\RequestDTO\ArticleRequestDTO;
use App\Domain\DTO\ResponseDTO\ArticleListResponseDTO;
use App\Domain\DTO\ResponseDTO\ArticleResponseDTO;
use App\Domain\Product\Product;
use App\Domain\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository implements ArticleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Article::class);
        $this->entityManager = $entityManager;
    }

    public function findByUuid(string $uuid): ?ArticleResponseDTO
    {
        $article = $this->findOneBy(['uuid' => $uuid]);

        if (!$article) {
            return null;
        }

        return ArticleResponseDTO::toDTO($article);
    }

    public function persist(ArticleRequestDTO $requestDTO): ?ArticleResponseDTO
    {
        $user = $this->entityManager->getRepository(User::class)->find($requestDTO->userUuid);
        $products = new ArrayCollection();

        if ($requestDTO->productUuids) {
            foreach ($requestDTO->productUuids as $productUuid) {
                $product = $this->entityManager->getRepository(Product::class)->find($productUuid);
                if ($product) {
                    $products->add($product);
                }
            }
        }

        $article = ArticleRequestDTO::toEntity(new Article(), $requestDTO, $user, $products);

        $now = new \DateTimeImmutable();
        $article->setCreatedAt($now);
        $article->setUpdatedAt($now);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return ArticleResponseDTO::toDTO($article);
    }

    public function updateByUuid(string $uuid, ArticleRequestDTO $requestDTO): ?ArticleResponseDTO
    {
        $article = $this->findOneBy(['uuid' => $uuid]);

        if (!$article) {
            return null;
        }

        $user = $this->entityManager->getRepository(User::class)->find($requestDTO->userUuid);
        $products = new ArrayCollection();

        if ($requestDTO->productUuids) {
            foreach ($requestDTO->productUuids as $productUuid) {
                $product = $this->entityManager->getRepository(Product::class)->find($productUuid);
                if ($product) {
                    $products->add($product);
                }
            }
        }

        $article->setUpdatedAt(new \DateTimeImmutable());

        $article = ArticleRequestDTO::toEntity($article, $requestDTO, $user, $products);

        $this->entityManager->flush();

        return ArticleResponseDTO::toDTO($article);
    }

    public function removeByUuid(string $uuid): ?bool
    {
        $article = $this->findOneBy(['uuid' => $uuid]);

        if (!$article) {
            return null;
        }

        $this->entityManager->remove($article);
        $this->entityManager->flush();

        return true;
    }

    public function getList(): ?ArticleListResponseDTO
    {
        $articles = $this->findAll();
        $articleDTOs = new ArrayCollection();

        foreach ($articles as $article) {
            $articleDTOs->add(ArticleResponseDTO::toDTO($article));
        }

        return ArticleListResponseDTO::toDTO($articleDTOs);
    }
}
