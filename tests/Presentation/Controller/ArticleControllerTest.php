<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Application\DTO\SuccessDTO;
use App\Application\UseCase\Article\CreateArticleUseCase;
use App\Application\UseCase\Article\DeleteArticleUseCase;
use App\Application\UseCase\Article\GetArticleListUseCase;
use App\Application\UseCase\Article\GetArticleUseCase;
use App\Application\UseCase\Article\UpdateArticleUseCase;
use App\Domain\DTO\RequestDTO\ArticleRequestDTO;
use App\Domain\DTO\ResponseDTO\ArticleListResponseDTO;
use App\Domain\DTO\ResponseDTO\ArticleResponseDTO;
use App\Presentation\Controller\ArticleController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleControllerTest extends TestCase
{
    private $createArticleUseCase;
    private $getArticleUseCase;
    private $updateArticleUseCase;
    private $deleteArticleUseCase;
    private $getArticleListUseCase;

    protected function setUp(): void
    {
        $this->createArticleUseCase = $this->createMock(CreateArticleUseCase::class);
        $this->getArticleUseCase = $this->createMock(GetArticleUseCase::class);
        $this->updateArticleUseCase = $this->createMock(UpdateArticleUseCase::class);
        $this->deleteArticleUseCase = $this->createMock(DeleteArticleUseCase::class);
        $this->getArticleListUseCase = $this->createMock(GetArticleListUseCase::class);
    }

    public function testCreateArticle(): void
    {
        $expectedResponse = new ArticleResponseDTO();
        $expectedResponse->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $expectedResponse->name = 'Article 1';
        $expectedResponse->code = 'ART001';
        $expectedResponse->text = 'Article 1 text';
        $expectedResponse->userUuid = 'user-uuid';

        $this->createArticleUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new ArticleController(
            $this->createArticleUseCase,
            $this->getArticleUseCase,
            $this->updateArticleUseCase,
            $this->deleteArticleUseCase,
            $this->getArticleListUseCase
        );

        $request = new Request([], [], [], [], [], [], json_encode([
            'name' => 'Article 1',
            'code' => 'ART001',
            'text' => 'Article 1 text',
            'user_uuid' => 'user-uuid',
            'product_uuids' => ['product-uuid-1', 'product-uuid-2'],
        ]));

        $response = $controller->createArticle($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $responseData['uuid']);
        $this->assertEquals('Article 1', $responseData['name']);
    }

    public function testGetArticleByUuid(): void
    {
        $expectedResponse = new ArticleResponseDTO();
        $expectedResponse->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $expectedResponse->name = 'Article 1';
        $expectedResponse->code = 'ART001';
        $expectedResponse->text = 'Article 1 text';
        $expectedResponse->userUuid = 'user-uuid';

        $this->getArticleUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new ArticleController(
            $this->createArticleUseCase,
            $this->getArticleUseCase,
            $this->updateArticleUseCase,
            $this->deleteArticleUseCase,
            $this->getArticleListUseCase
        );

        $response = $controller->getArticleByUuid('123e4567-e89b-12d3-a456-426614174000');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $responseData['uuid']);
        $this->assertEquals('Article 1', $responseData['name']);
    }

    public function testUpdateArticle(): void
    {
        $expectedResponse = new ArticleResponseDTO();
        $expectedResponse->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $expectedResponse->name = 'Updated Article';
        $expectedResponse->code = 'ART001';
        $expectedResponse->text = 'Updated Article text';
        $expectedResponse->userUuid = 'user-uuid';

        $this->updateArticleUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new ArticleController(
            $this->createArticleUseCase,
            $this->getArticleUseCase,
            $this->updateArticleUseCase,
            $this->deleteArticleUseCase,
            $this->getArticleListUseCase
        );

        $request = new Request([], [], [], [], [], [], json_encode([
            'name' => 'Updated Article',
            'code' => 'ART001',
            'text' => 'Updated Article text',
            'user_uuid' => 'user-uuid',
            'product_uuids' => ['product-uuid-1', 'product-uuid-2'],
        ]));

        $response = $controller->updateArticle('123e4567-e89b-12d3-a456-426614174000', $request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $responseData['uuid']);
        $this->assertEquals('Updated Article', $responseData['name']);
    }

    public function testDeleteArticle(): void
    {
        $expectedResponse = new SuccessDTO();
        $expectedResponse->success = true;

        $this->deleteArticleUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new ArticleController(
            $this->createArticleUseCase,
            $this->getArticleUseCase,
            $this->updateArticleUseCase,
            $this->deleteArticleUseCase,
            $this->getArticleListUseCase
        );

        $response = $controller->deleteArticle('123e4567-e89b-12d3-a456-426614174000');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
    }

    public function testGetArticleList(): void
    {
        $expectedResponse = new ArticleListResponseDTO();
        $article = new ArticleResponseDTO();
        $article->uuid = '123e4567-e89b-12d3-a456-426614174000';
        $article->name = 'Article 1';
        $article->code = 'ART001';
        $article->text = 'Article 1 text';
        $article->userUuid = 'user-uuid';
        $expectedResponse->addArticle($article);

        $this->getArticleListUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResponse);

        $controller = new ArticleController(
            $this->createArticleUseCase,
            $this->getArticleUseCase,
            $this->updateArticleUseCase,
            $this->deleteArticleUseCase,
            $this->getArticleListUseCase
        );

        $response = $controller->getArticleList();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(1, $responseData['list']);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $responseData['list'][0]['uuid']);
    }
}
