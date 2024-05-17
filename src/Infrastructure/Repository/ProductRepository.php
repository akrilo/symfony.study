<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\DTO\RequestDTO\ProductListRequestDTO;
use App\Domain\DTO\ResponseDTO\PaginationDTO;
use App\Domain\DTO\ResponseDTO\ProductListResponseDTO;
use App\Domain\DTO\RequestDTO\ProductRequestDTO;
use App\Domain\DTO\ResponseDTO\ProductResponseDTO;
use App\Domain\Product\Category;
use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Product::class);
        $this->entityManager = $entityManager;
    }

    public function findByUuid(string $uuid): ?ProductResponseDTO
    {
        $product = $this->findOneBy(['uuid' => $uuid]);

        if (!$product) {
            return null;
        }

        return ProductResponseDTO::toDTO($product);
    }

    public function persist(ProductRequestDTO $requestDTO): ?ProductResponseDTO
    {
        $category = null;
        if (isset($requestDTO->categoryUuid)){
            $category = $this->entityManager->getRepository(Category::class)->findOneBy(['uuid' => $requestDTO->categoryUuid]);
        }

        $product = ProductRequestDTO::toEntity(new Product(), $requestDTO, $category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return ProductResponseDTO::toDTO($product);
    }

    public function getList(ProductListRequestDTO $requestDTO): ?ProductListResponseDTO
    {
        $page = $requestDTO->page;
        $limit = $requestDTO->limit;
        $sortBy = $requestDTO->sortBy;
        $sortOrder = $requestDTO->sortOrder;

        $qb = $this->createQueryBuilder('p')
            ->setFirstResult($page - 1)
            ->setMaxResults($limit);

        if ($sortBy && $sortOrder) {
            $qb->orderBy('p.' . $sortBy, $sortOrder);
        }

        $products = $qb->getQuery()->getResult();

        $pagination = PaginationDTO::toDTO($page, count($products), $limit);

        $responseDTO = new ProductListResponseDTO();
        $responseDTO->pagination = $pagination;
        foreach ($products as $product) {
            $responseDTO->addProduct(ProductResponseDTO::toDTO($product));
        }

        return $responseDTO;
    }

    public function removeByUuid(string $uuid): ?bool
    {
        $product = $this->findOneBy(['uuid' => $uuid]);

        if (!$product) {
            return null;
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return true;
    }
}
