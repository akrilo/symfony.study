<?php

declare(strict_types=1);

namespace App\Application\UseCase\Article;

use App\Application\DTO\ErrorDTO;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\DTO\ResponseDTO\ArticleResponseDTO;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use Symfony\Component\Uid\Uuid;

class GetArticleUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) { }

    public function execute(string $userUuid): ArticleResponseDTO|ErrorDTO
    {
        try{
            if (!Uuid::isValid($userUuid)) {
                throw new ValidationException;
            }

            $response = $this->articleRepository->findByUuid($userUuid);

            if ($response === null) {
                throw new NotFoundException;
            }

            return $response;
        } catch (ValidationException|NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}