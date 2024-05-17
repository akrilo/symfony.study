<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\UseCase\Article\CreateArticleUseCase;
use App\Application\UseCase\Article\DeleteArticleUseCase;
use App\Application\UseCase\Article\GetArticleListUseCase;
use App\Application\UseCase\Article\GetArticleUseCase;
use App\Application\UseCase\Article\UpdateArticleUseCase;
use App\Application\UseCase\User\CreateUserUseCase;
use App\Application\UseCase\User\DeleteUserUseCase;
use App\Application\UseCase\User\GetUserUseCase;
use App\Application\UseCase\User\UpdateUserUseCase;
use App\Domain\DTO\RequestDTO\ArticleRequestDTO;
use App\Domain\DTO\RequestDTO\UserRequestDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class ArticleController extends AbstractController
{
    public function __construct(
        private CreateArticleUseCase $createArticleUseCase,
        private GetArticleUseCase $getArticleUseCase,
        private UpdateArticleUseCase $updateArticleUseCase,
        private DeleteArticleUseCase $deleteArticleUseCase,
        private GetArticleListUseCase $getArticleListUseCase,
    ) { }

    #[Route('/articles', name: 'create_article', methods: ['POST'])]
    public function createArticle(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $requestDTO = ArticleRequestDTO::toDTO($data);

        $responseDTO = $this->createArticleUseCase->execute($requestDTO);
        $statusCode = $responseDTO->errorCode ?? 201;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/articles/{uuid}', name: 'get_article', methods: ['GET'])]
    public function getArticleByUuid(string $uuid): JsonResponse
    {
        $responseDTO = $this->getArticleUseCase->execute($uuid);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/articles/{uuid}', name: 'update_article', methods: ['PATCH'])]
    public function updateArticle(string $uuid, Request $request): JsonResponse
    {
        $data = $request->toArray();
        $requestDTO = ArticleRequestDTO::toDTO($data);

        $responseDTO = $this->updateArticleUseCase->execute($uuid, $requestDTO);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/articles/{uuid}', name: 'delete_article', methods: ['DELETE'])]
    public function deleteArticle(string $uuid): JsonResponse
    {
        $responseDTO = $this->deleteArticleUseCase->execute($uuid);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/articles', name: 'get_article_list', methods: ['GET'])]
    public function getArticleList(): JsonResponse
    {
        $responseDTO = $this->getArticleListUseCase->execute();
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }
}
