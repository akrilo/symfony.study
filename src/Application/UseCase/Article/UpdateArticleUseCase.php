<?php

namespace App\Application\UseCase\Article;

use App\Application\DTO\ErrorDTO;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\DTO\RequestDTO\ArticleRequestDTO;
use App\Domain\DTO\ResponseDTO\ArticleResponseDTO;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use Symfony\Component\Uid\Uuid;

class UpdateArticleUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
    ) { }

    public function execute(string $uuid, ArticleRequestDTO $requestDTO): ArticleResponseDTO|ErrorDTO
    {
        try{
            if (!Uuid::isValid($uuid) || !Uuid::isValid($requestDTO->userUuid)) {
                throw new ValidationException;
            }

            $responseDTO = $this->articleRepository->updateByUuid($uuid, $requestDTO);
            if ($responseDTO === null) {
                throw new NotFoundException;
            }
            return $responseDTO;
        } catch (ValidationException | NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}