<?php

declare(strict_types=1);

namespace App\Application\UseCase\Product;

use App\Application\DTO\ErrorDTO;
use App\Domain\DTO\RequestDTO\ProductRequestDTO;
use App\Domain\DTO\ResponseDTO\ProductResponseDTO;
use App\Domain\Exception\ValidationException;
use App\Domain\Product\ProductRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ValidatorInterface $validator
    ) { }

    public function execute(ProductRequestDTO $requestDTO): ProductResponseDTO|ErrorDTO
    {
        try{
            $errors = $this->validator->validate($requestDTO);

            if (count($errors) > 0) {
                throw new ValidationException;
            }

            return $this->productRepository->persist($requestDTO);
        } catch (ValidationException $e) {
            return ErrorDTO::toDTO($e);
        }

    }
}