<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\UseCase\Product\CreateProductUseCase;
use App\Application\UseCase\Product\DeleteProductUseCase;
use App\Application\UseCase\Product\GetProductListUseCase;
use App\Application\UseCase\Product\GetProductUseCase;
use App\Domain\DTO\RequestDTO\ProductListRequestDTO;
use App\Domain\DTO\RequestDTO\ProductRequestDTO;
use App\Domain\Product\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/v1')]
class ProductController extends AbstractController
{
    public function __construct(
        private GetProductUseCase $getProductUseCase,
        private CreateProductUseCase $createProductUseCase,
        private GetProductListUseCase $getProductListUseCase,
        private DeleteProductUseCase $deleteProductUseCase,
    ) { }

    #[Route('/products', name: 'get_product_list', methods: ['GET'])]
    public function getProductList(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 4);
        $sortBy = $request->query->get('sort_by');
        $sortOrder = $request->query->get('sort_order');
        $requestDTO = ProductListRequestDTO::toDTO($page, $limit, $sortBy, $sortOrder);
        $responseDTO = $this->getProductListUseCase->execute($requestDTO);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/products', name: 'create_product', methods: ['POST'])]
    public function createProduct(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $requestDTO = ProductRequestDTO::toDTO($data);

        $responseDTO = $this->createProductUseCase->execute($requestDTO);
        $statusCode = $responseDTO->errorCode ?? 201;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/products/{uuid}', name: 'get_product', methods: ['GET'])]
    public function getProductByUuid(string $uuid): JsonResponse
    {
        $responseDTO = $this->getProductUseCase->execute($uuid);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/products/{uuid}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(string $uuid): JsonResponse
    {
        $responseDTO = $this->deleteProductUseCase->execute($uuid);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }
}
