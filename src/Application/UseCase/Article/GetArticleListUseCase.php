<?php

declare(strict_types=1);

namespace App\Application\UseCase\Article;

use App\Application\DTO\ErrorDTO;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\DTO\ResponseDTO\ArticleListResponseDTO;
use App\Domain\Exception\NotFoundException;

class GetArticleListUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) { }

    public function execute(): ArticleListResponseDTO|ErrorDTO
    {
        try{
            $responseDTO = $this->articleRepository->getList();

            if ($responseDTO === null) {
                throw new NotFoundException;
            }

            return $responseDTO;
        } catch (NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}