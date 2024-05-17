<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Application\DTO\SuccessDTO;
use App\Application\UseCase\Favorite\CreateFavoriteUseCase;
use App\Application\UseCase\Favorite\DeleteFavoriteUseCase;
use App\Application\UseCase\Favorite\GetFavoriteUseCase;
use App\Domain\DTO\ResponseDTO\FavoriteResponseDTO;
use App\Domain\DTO\ResponseDTO\ProductResponseDTO;
use App\Presentation\Controller\FavoriteController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FavoriteControllerTest extends TestCase
{
    private $getFavoriteUseCase;
    private $createFavoriteUseCase;
    private $deleteFavoriteUseCase;

    protected function setUp(): void
    {
        $this->getFavoriteUseCase = $this->createMock(GetFavoriteUseCase::class);
        $this->createFavoriteUseCase = $this->createMock(CreateFavoriteUseCase::class);
        $this->deleteFavoriteUseCase = $this->createMock(DeleteFavoriteUseCase::class);
    }

    public function testGetFavorite(): void
    {
        $expectedResponse = new FavoriteResponseDTO();
        $expectedResponse->uuid = 'user-uuid-123';
        $product = new ProductResponseDTO();
        $product->uuid = 'product-uuid-456';
        $expectedResponse->list->add($product);

        $this->getFavoriteUseCase->expects($this->once())
            ->method('execute')
            ->with('user-uuid-123')
            ->willReturn($expectedResponse);

        $controller = new FavoriteController(
            $this->getFavoriteUseCase,
            $this->createFavoriteUseCase,
            $this->deleteFavoriteUseCase
        );

        $request = new Request(['user_uuid' => 'user-uuid-123']);
        $response = $controller->getFavorite($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('user-uuid-123', $responseData['uuid']);
        $this->assertCount(1, $responseData['list']);
        $this->assertEquals('product-uuid-456', $responseData['list'][0]['uuid']);
    }

    public function testCreateFavorite(): void
    {
        $expectedResponse = new SuccessDTO();
        $expectedResponse->success = true;

        $this->createFavoriteUseCase->expects($this->once())
            ->method('execute')
            ->with('user-uuid-123', 'product-uuid-456')
            ->willReturn($expectedResponse);

        $controller = new FavoriteController(
            $this->getFavoriteUseCase,
            $this->createFavoriteUseCase,
            $this->deleteFavoriteUseCase
        );

        $request = new Request([], [], [], [], [], [], json_encode([
            'user_uuid' => 'user-uuid-123',
            'product_uuid' => 'product-uuid-456'
        ]));

        $response = $controller->createFavorite($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
    }

    public function testDeleteFavorite(): void
    {
        $expectedResponse = new SuccessDTO();
        $expectedResponse->success = true;

        $this->deleteFavoriteUseCase->expects($this->once())
            ->method('execute')
            ->with('user-uuid-123', 'product-uuid-456')
            ->willReturn($expectedResponse);

        $controller = new FavoriteController(
            $this->getFavoriteUseCase,
            $this->createFavoriteUseCase,
            $this->deleteFavoriteUseCase
        );

        $request = new Request([], [], [], [], [], [], json_encode([
            'user_uuid' => 'user-uuid-123',
            'product_uuid' => 'product-uuid-456'
        ]));

        $response = $controller->deleteFavorite($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
    }
}
