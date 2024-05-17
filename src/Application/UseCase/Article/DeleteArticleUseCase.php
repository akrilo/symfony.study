<?php

declare(strict_types=1);

namespace App\Application\UseCase\Article;

use App\Application\DTO\ErrorDTO;
use App\Application\DTO\SuccessDTO;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use Symfony\Component\Uid\Uuid;

class DeleteArticleUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) { }

    public function execute(string $uuid): SuccessDTO|ErrorDTO
    {
        try{
            if (!Uuid::isValid($uuid)) {
                throw new ValidationException;
            }

            $response = $this->articleRepository->removeByUuid($uuid);

            if ($response === null) {
                throw new NotFoundException;
            }

            return SuccessDTO::toDTO($response);
        } catch (ValidationException | NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}