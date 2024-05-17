<?php

declare(strict_types=1);

namespace App\Application\UseCase\Article;

use App\Application\DTO\ErrorDTO;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\DTO\RequestDTO\ArticleRequestDTO;
use App\Domain\DTO\ResponseDTO\ArticleResponseDTO;
use App\Domain\Exception\ValidationException;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateArticleUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ValidatorInterface $validator
    ) { }

    public function execute(ArticleRequestDTO $requestDTO): ArticleResponseDTO|ErrorDTO
    {
        try{
            $errors = $this->validator->validate($requestDTO);

            if (count($errors) > 0 || !Uuid::isValid($requestDTO->userUuid)) {
                throw new ValidationException;
            }

            return $this->articleRepository->persist($requestDTO);
        } catch (ValidationException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}