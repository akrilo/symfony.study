<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Application\DTO\SuccessDTO;
use App\Application\UseCase\Product\CreateProductUseCase;
use App\Application\UseCase\Product\DeleteProductUseCase;
use App\Application\UseCase\Product\GetProductListUseCase;
use App\Application\UseCase\Product\GetProductUseCase;
use App\Domain\DTO\ResponseDTO\PaginationDTO;
use App\Domain\DTO\ResponseDTO\ProductListResponseDTO;
use App\Domain\DTO\ResponseDTO\ProductResponseDTO;
use App\Presentation\Controller\ProductController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductControllerTest extends TestCase
{
    private $getProductUseCase;
    private $createProductUseCase;
    private $getProductListUseCase;
    private $deleteProductUseCase;

    protected function setUp(): void
    {
        $this->getProductUseCase = $this->createMock(GetProductUseCase::class);
        $this->createProductUseCase = $this->createMock(CreateProductUseCase::class);
        $this->getProductListUseCase = $this->createMock(GetProductListUseCase::class);
        $this->deleteProductUseCase = $this->createMock(DeleteProductUseCase::class);
    }

    public function testGetProductList(): void
    {
        $expectedResponse = new ProductListResponseDTO();
        $expectedResponse->pagination = new PaginationDTO();
        $expectedResponse->pagination->total = 10;
        $expectedResponse->pagination->page = 1;
        $expectedResponse->pagination->limit = 4;
        $product = new ProductResponseDTO();
        $product->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $product->name = 'Product 1';
        $product->price = 100.0;
        $expectedResponse->addProduct($product);

        $this->getProductListUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new ProductController(
            $this->getProductUseCase,
            $this->createProductUseCase,
            $this->getProductListUseCase,
            $this->deleteProductUseCase
        );

        $request = new Request([], [], [], [], [], [], []);

        $response = $controller->getProductList($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('pagination', $responseData);
        $this->assertArrayHasKey('list', $responseData);
        $this->assertEquals(10, $responseData['pagination']['total']);
        $this->assertCount(1, $responseData['list']);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $responseData['list'][0]['uuid']);
    }

    public function testCreateProduct(): void
    {
        $expectedResponse = new ProductResponseDTO();
        $expectedResponse->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $expectedResponse->name = 'Product 1';
        $expectedResponse->price = 100.0;

        $this->createProductUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new ProductController(
            $this->getProductUseCase,
            $this->createProductUseCase,
            $this->getProductListUseCase,
            $this->deleteProductUseCase
        );

        $request = new Request([], [], [], [], [], [], json_encode([
            'name' => 'Product 1',
            'code' => 'PROD001',
            'description' => 'Product 1 description',
            'preview_image' => 'product1.jpg',
            'price' => 100.0,
        ]));

        $response = $controller->createProduct($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $responseData['uuid']);
        $this->assertEquals('Product 1', $responseData['name']);
        $this->assertEquals(100.0, $responseData['price']);
    }

    public function testGetProductByUuid(): void
    {
        $expectedResponse = new ProductResponseDTO();
        $expectedResponse->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $expectedResponse->name = 'Product 1';
        $expectedResponse->price = 100.0;

        $this->getProductUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new ProductController(
            $this->getProductUseCase,
            $this->createProductUseCase,
            $this->getProductListUseCase,
            $this->deleteProductUseCase
        );

        $response = $controller->getProductByUuid('123e4567-e89b-12d3-a456-426614174000');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $responseData['uuid']);
        $this->assertEquals('Product 1', $responseData['name']);
        $this->assertEquals(100.0, $responseData['price']);
    }

    public function testDeleteProduct(): void
    {
        $expectedResponse = new SuccessDTO();
        $expectedResponse->success = true;

        $this->deleteProductUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new ProductController(
            $this->getProductUseCase,
            $this->createProductUseCase,
            $this->getProductListUseCase,
            $this->deleteProductUseCase
        );

        $response = $controller->deleteProduct('123e4567-e89b-12d3-a456-426614174000');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
    }
}
