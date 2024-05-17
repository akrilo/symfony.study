<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\UseCase\Favorite\CreateFavoriteUseCase;
use App\Application\UseCase\Favorite\DeleteFavoriteUseCase;
use App\Application\UseCase\Favorite\GetFavoriteUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/v1')]
class FavoriteController extends AbstractController
{
    public function __construct(
        private GetFavoriteUseCase $getFavoriteUseCase,
        private CreateFavoriteUseCase $createFavoriteUseCase,
        private DeleteFavoriteUseCase $deleteFavoriteUseCase,
    ) { }

    #[Route('/favorites', name: 'get_favorite', methods: ['GET'])]
    public function getFavorite(Request $request): JsonResponse
    {
        $userUuid = $request->query->get('user_uuid');
        $responseDTO = $this->getFavoriteUseCase->execute($userUuid);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/favorites', name: 'create_favorite', methods: ['POST'])]
    public function createFavorite(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $responseDTO = $this->createFavoriteUseCase->execute($data['user_uuid'], $data['product_uuid']);
        $statusCode = $responseDTO->errorCode ?? 201;

        return new JsonResponse($responseDTO, $statusCode);
    }

    #[Route('/favorites', name: 'delete_favorite', methods: ['DELETE'])]
    public function deleteFavorite(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $responseDTO = $this->deleteFavoriteUseCase->execute($data['user_uuid'], $data['product_uuid']);
        $statusCode = $responseDTO->errorCode ?? 200;

        return new JsonResponse($responseDTO, $statusCode);
    }
}
